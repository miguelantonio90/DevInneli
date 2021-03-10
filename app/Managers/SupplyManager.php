<?php

namespace App\Managers;

use App\Articles;
use App\Company;
use App\ExchangeRate;
use App\Notification;
use App\Sale;
use App\Supplier;
use App\Supply;
use App\SupplyState;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class SupplyManager extends BaseManager
{
    /**
     * @var BuyManager
     */
    private $buyManager;

    /**
     * SupplyManager constructor.
     * @param  BuyManager  $buyManager
     */
    public function __construct(BuyManager $buyManager)
    {
        $this->buyManager = $buyManager;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function findAllByCompany(): array
    {
        $received = [];
        if (auth()->user()['isAdmin'] === 1) {
            $supplies = Supply::latest()
                ->with('company')
                ->with('sale')
                ->with('state')
                ->orderBy('created_at', 'ASC')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $supplies = Supply::latest()
                ->where('company_id', '=', $company->id)
                ->when($this->getAccessPermit()[2]->actions->just_yours === true, function ($query) {
                    return $query->where('created_by', '=', cache()->get('userPin')['id']);
                })
                ->with('company')
                ->with('sale')
                ->with('from')
                ->with('state')
                ->orderBy('created_at', 'ASC')
                ->get();
            $received = Supply::latest()
                ->where('to_company', '=', $company->id)
                ->when($this->getAccessPermit()[2]->actions->just_yours === true, function ($query) {
                    return $query->where('created_by', '=', cache()->get('userPin')['id']);
                })
                ->with('company')
                ->with('sale')
                ->with('state')
                ->orderBy('created_at', 'ASC')
                ->get();

            foreach ($received as $key => $supply) {
                $supply['from'] = DB::table('companies')
                    ->leftJoin('users', 'users.company_id', '=', 'companies.id')
                    ->leftJoin('suppliers', 'suppliers.email', '=', 'users.email')
                    ->leftJoin('sales', 'sales.provider_id', '=', 'suppliers.id')
                    ->where('sales.id', '=', $supply['sale']['id'])
                    ->select('companies.*')
                    ->first();

                $supply = $this->supplyExtraData($supply);
            }
        }
        foreach ($supplies as $key => $supply) {
            $supply['to'] = DB::table('companies')
                ->leftJoin('users', 'users.company_id', '=', 'companies.id')
                ->leftJoin('suppliers', 'suppliers.email', '=', 'users.email')
                ->leftJoin('sales', 'sales.provider_id', '=', 'suppliers.id')
                ->where('sales.id', '=', $supply['sale']['id'])
                ->select('companies.*')
                ->first();
            $supply = $this->supplyExtraData($supply);
        }
        return [$supplies, $received];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getAccessPermit()
    {
        return json_decode(cache()->get('userPin')->position['access_permit']);
    }

    /**
     * @param $supply
     * @return mixed
     */
    private function supplyExtraData($supply)
    {

        $supply['sale']['shop'] = DB::table('shops')
            ->select('shops.*', 'shops.id as shop_id')
            ->where('sales.id', '=', $supply['sale']['id'])
            ->leftJoin('articles_shops', 'shops.id', '=', 'articles_shops.shop_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->first();

        $supply['sale']['articles'] = DB::table('articles')
            ->select([
                'articles.*', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                'articles_shops.stock as inventory', 'articles.id as article_id'
            ])
            ->where('sales.id', '=', $supply['sale']['id'])
            ->leftJoin('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->get();
        $supply['sale']['pays'] = DB::table('payments')
            ->where('sales.id', '=', $supply['sale']['id'])
            ->whereNull('pay_sales.deleted_at')
            ->leftJoin('pay_sales', 'pay_sales.payment_id', '=', 'payments.id')
            ->leftJoin('sales', 'sales.id', '=', 'pay_sales.sale_id')
            ->select('payments.id as payment_id', 'pay_sales.id', 'payments.name', 'payments.method',
                'pay_sales.cant', 'pay_sales.mora', 'pay_sales.cantMora', 'pay_sales.cant_pay',
                'pay_sales.currency_id', 'pay_sales.cant_back')
            ->get();
        $supply['sale']['client'] = DB::table('clients')
            ->leftJoin('sales', 'sales.client_id', '=', 'clients.id')
            ->where('sales.id', '=', $supply['sale']['id'])
            ->first();
        $totalCost = 0;
        $subTotal = 0;
        $totalTax = 0;
        $totalDisc = 0;
        $totalRefund = 0;

        foreach ($supply['sale']['pays'] as $p => $pay) {
            $pay->currency = $pay->currency_id ? ExchangeRate::findOrFail($pay->currency_id) : '';
        }
        foreach ($supply['sale']['articles'] as $k => $v) {

            $v->name = $v->parent_id !== null ? Articles::findOrFail($v->parent_id)->name.'('.$v->name.')' : $v->name;
            $supply['sale']['articles'][$k]->images = DB::table('article_images')
                ->where('article_images.article_id', '=', $v->id)
                ->get();

            $supply['sale']['articles'][$k]->taxes = DB::table('taxes')
                ->leftJoin('article_tax', 'article_tax.tax_id', '=', 'taxes.id')
                ->leftJoin('articles', 'articles.id', '=', 'article_tax.article_id')
                ->where('articles.id', '=', $v->id)
                ->addSelect(['taxes.*'])
                ->get();

            $supply['sale']['articles'][$k]->refounds = DB::table('refunds')
                ->leftJoin('users', 'users.id', '=', 'refunds.created_by')
                ->where('refunds.article_id', '=', $v->id)
                ->where('refunds.sale_id', '=', $supply['sale_id'])
                ->select('refunds.*', 'users.firstName as created_by')
                ->get();

            $supply['sale']['articles'][$k]->modifiers = DB::table('modifiers')
                ->leftJoin('sales_articles_shop_modifiers', 'sales_articles_shop_modifiers.modifier_id', '=',
                    'modifiers.id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                    'sales_articles_shop_modifiers.sales_articles_shops_id')
                ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->where('articles.id', '=', $v->id)
                ->where('shops.id', '=', $supply['sale']['shop']->shop_id)
                ->where('sales.id', '=', $supply['sale']['id'])
                ->addSelect(['modifiers.*'])
                ->get();

            $supply['sale']['articles'][$k]->discount = DB::table('discounts')
                ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', '=',
                    'discounts.id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                    'sales_articles_shop_discounts.sales_articles_shops_id')
                ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->where('articles.id', '=', $v->id)
                ->where('shops.id', '=', $supply['sale']['shop']->shop_id)
                ->where('sales.id', '=', $supply['sale']['id'])
                ->addSelect(['discounts.*'])
                ->get();
            $sum = 0;
            $discount = 0;
            $refund = 0;
            $cantRefund = 0;
            foreach ($supply['sale']['articles'][$k]->discount as $j => $i) {
                $discount += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
            }
            foreach ($supply['sale']['articles'][$k]->modifiers as $j => $i) {
                $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
            }
            foreach ($supply['sale']['articles'][$k]->taxes as $j => $i) {
                if ($i->type === 'added') {
                    $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
            }
            foreach ($supply['sale']['articles'][$k]->refounds as $s => $t) {
                $refund += $t->money;
                $cantRefund += $t->cant;
            }
            $supply['sale']['articles'][$k]->moneyRefund = $refund;
            $supply['sale']['articles'][$k]->cantRefund = $cantRefund;
            $totalCost += $v->cant * $v->cost;
            $supply['sale']['articles'][$k]->totalPrice = $v->cant * $v->price + $sum - $discount - $refund;
            $subTotal += $supply['sale']['articles'][$k]->totalPrice;
            $totalRefund += $refund;

        }
        foreach ($supply['sale']['taxes'] as $j => $i) {
            $totalTax += $i->percent ? $subTotal * $i->value / 100 : $i->value;
        }
        foreach ($supply['sale']['discounts'] as $j => $i) {
            $totalDisc += $i->percent ? $subTotal * $i->value / 100 : $i->value;
        }
        $totalPrice = $subTotal + $totalTax - $totalDisc;
        $supply['sale']['totalCost'] = round($totalCost, 2);
        $supply['sale']['totalTax'] = round($totalTax, 2);
        $supply['sale']['totalDisc'] = round($totalDisc, 2);
        $supply['sale']['subTotal'] = round($subTotal, 2);
        $supply['sale']['totalRefund'] = round($totalRefund, 2);
        $supply['sale']['totalPrice'] = round($totalPrice, 2);
        $supply['sale']['create'] = DB::table('users')
            ->where('users.id', '=', $supply['created_by'])
            ->select('firstName', 'lastName')
            ->first();
        $supply['state']['next'] = $this->nextState($supply);
        return $supply;
    }

    /**
     * @param $supply
     * @return array|null
     */
    public function nextState($supply): ?array
    {
        if (property_exists('to', $supply)) {
            if (User::findOrFail(auth()->id())->email === $supply['to']->email) {
                if ($supply['state']['name'] === 'requested') {
                    return [
                        SupplyState::latest()->where('name', '=', 'requested')->first(),
                        SupplyState::latest()->where('name', '=', 'accepted')->first(),
                        SupplyState::latest()->where('name', '=', 'process')->first(),
                        SupplyState::latest()->where('name', '=', 'ship')->first(),
                        SupplyState::latest()->where('name', '=', 'received')->first(),
                        SupplyState::latest()->where('name', '=', 'cancelled')->first(),
                    ];
                } else {
                    if ($supply['state']['name'] === 'accepted') {
                        return [
                            SupplyState::latest()->where('name', '=', 'accepted')->first(),
                            SupplyState::latest()->where('name', '=', 'process')->first(),
                            SupplyState::latest()->where('name', '=', 'ship')->first(),
                            SupplyState::latest()->where('name', '=', 'received')->first(),
                            SupplyState::latest()->where('name', '=', 'cancelled')->first(),
                        ];
                    } else {
                        if ($supply['state']['name'] === 'process') {
                            return [
                                SupplyState::latest()->where('name', '=', 'process')->first(),
                                SupplyState::latest()->where('name', '=', 'ship')->first(),
                                SupplyState::latest()->where('name', '=', 'received')->first(),
                                SupplyState::latest()->where('name', '=', 'cancelled')->first(),
                            ];
                        } else {
                            if ($supply['state']['name'] === 'ship') {
                                return [
                                    SupplyState::latest()->where('name', '=', 'ship')->first(),
                                    SupplyState::latest()->where('name', '=', 'received')->first()
                                ];
                            } else {
                                return [SupplyState::latest()->where('name', '=', $supply['state']['name'])->first()];
                            }
                        }
                    }
                }
            } else {
                if ($supply['state']['name'] !== 'received') {
                    return [
                        SupplyState::latest()->where('name', '=', $supply['state']['name'])->first(),
                        SupplyState::latest()->where('name', '=', 'cancelled')->first()
                    ];
                }
            }
        } else {

            if ($supply['state']['name'] === 'requested') {
                return [
                    SupplyState::latest()->where('name', '=', 'requested')->first(),
                    SupplyState::latest()->where('name', '=', 'cancelled')->first()
                ];

            } else {
                return [SupplyState::latest()->where('name', '=', 'cancelled')->first()];
            }
        }
    }

    /**
     * @param $data
     * @return Sale
     * @throws Exception
     */
    public function new($data): Sale
    {
        $sale = Sale::create([
            'no_facture' => $data['no_facture'],
            'company_id' => $data['company_id']
        ]);
        $sale->type = 'buy-sale';
        if (isset($data['supplier']['id'])) {
            $sale->provider_id = $data['supplier']['id'];
        }
        $sale->supply = true;
        $sale->supply_process = true;
        $state = SupplyState::latest()->where('name', '=', 'requested')->first();
        $sale->save();
        $supply = Supply::create([
            'company_id' => $data['company_id'],
            'sale_id' => $sale->id,
            'state_id' => $state->id,
            'to_company' => Supplier::findOrFail($data['supplier']['id'])->company_id
        ]);
        Notification::create([
            'company_id' => Supplier::findOrFail($data['supplier']['id'])->company_id,
            'params' => Company::findOrFail($data['company_id'])->name,
            'msg' => 'solicited_supply',
            'type' => 'info',
            'read' => false
        ]);
        $this->managerBy('new', $supply);
        $this->managerBy('new', $sale);
        $this->buyManager->updateSaleData($sale->id, $data, false);
        return $sale;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $sale = Sale::findOrFail($data['sale']['id']);
        $sale->no_facture = $data['sale']['no_facture'];
        $supply = Supply::findOrFail($id);
        $supply->state_id = $data['state']['id'];
        $supply->save();
        $sale->save();
        $this->managerBy('edit', $supply);
        $this->managerBy('edit', $sale);
        $this->buyManager->removeSaleArticle($data['sale_id'], $data['sale']['articles']);
        $this->buyManager->removePaySale($data['sale_id'], $data['sale']['pays']);
        $this->buyManager->updateSaleData($data['sale_id'], $data['sale'], true);
        return $sale;
    }

    /**
     * @param $data
     * @return int
     */
    public function findFactureNumber($data): int
    {
        $number = Sale::select('no_facture')
            ->where('company_id', '=', $data)
            ->where('type', '=', 'sale')
            ->latest()
            ->first();

        if ($number && count($number->toArray()) > 0) {
            $lastNumber = explode('-', $number['no_facture']);
            if ('F'.date('Y') === $lastNumber[0]) {
                return (int) $lastNumber[1] + 1;
            }

            return 1000000;
        }

        return 1000000;
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $sale = Supply::findOrFail($id);
        $this->managerBy('delete', $sale);
        return $sale->delete();
    }
}
