<?php

namespace App\Managers;

use App\Articles;
use App\ArticlesShops;
use App\BankPayment;
use App\Box;
use App\Company;
use App\ExchangeRate;
use App\PaySale;
use App\Sale;
use App\SalesArticlesShops;
use App\Shop;
use App\Tax;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleManager extends BaseManager
{

    /**
     * @param int $limit
     * @return mixed
     */
    public function findSalesByLimit(int $limit)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $sales = Sale::latest()
            ->where('company_id', '=', $company->id)
            ->where('type', '=', 'sale')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'ASC')
            ->take($limit)
            ->get();
        foreach ($sales as $key => $value) {
            $sales[$key]['created'] = DB::table('users')
                ->where('users.id', '=', $value->created_by)
                ->first();
            $sales[$key]['client'] = DB::table('clients')
                ->where('sales.id', '=', $value['id'])
                ->leftJoin('sales', 'sales.client_id', '=', 'clients.id')
                ->first();
        }
        return $sales;
    }

    /**
     * @return int
     */
    public function findFactureNumber(): int
    {
        $company = CompanyManager::getCompanyByAdmin();
        $number = Sale::select('no_facture')
            ->where('company_id', '=', $company->id)
            ->where('type', '=', 'sale')
            ->latest()
            ->first();

        if ($number && count($number->toArray()) > 0) {
            $lastNumber = explode('-', $number['no_facture']);
            if ('F' . date('Y') === $lastNumber[0]) {
                return (int)$lastNumber[1] + 1;
            }

            return 1000000;
        }

        return 1000000;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getTotalsStatic(): array
    {
        $sales = $this->findAllByCompany();
        $count = 0;
        $expenses = 0;
        $salesCount = 0;
        $response = [];
        foreach ($sales as $key => $value) {
            if ($value['state'] === 'accepted') {
                $expenses += $value['totalCost'];
                $salesCount += $value['totalPrice'];
                $response['totalSales'] = round($salesCount, 2);
                $response['totalExpenses'] = round($expenses, 2);
                $response['totalRevenue'] = round(($salesCount - $expenses), 2);
                $response['totalOrders'] = ++$count;
            }
        }

        return $response;
    }

    /**
     * @param $data
     * @return Sale|null
     * @throws Exception
     */
    public function changeState($data): ?Sale
    {
        $saleState = Sale::findOrFail($data['id']);
        $saleState->state = $data['state'];
        $saleState->save();
        if ($saleState->state === 'accepted') {
            $sale = Sale::latest()
                ->where('id', '=', $data['id'])
                ->with('company')
                ->with('box')
                ->with('articles_shops')
                ->with('taxes')
                ->with('discounts')
                ->with('refounds')
                ->with('pay_sales')
                ->orderBy('created_at', 'ASC')
                ->first();
            $sale['shop'] = DB::table('shops')
                ->select('shops.*', 'shops.id as shop_id')
                ->where('sales.id', '=', $data['id'])
                ->leftJoin('articles_shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->first();
            $sale['articles'] = DB::table('articles')
                ->select([
                    'articles.*', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                    'articles_shops.stock as inventory', 'articles.id as article_id'
                ])
                ->where('sales.id', '=', $data['id'])
                ->leftJoin('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->get();
            $sale['pays'] = DB::table('payments')
                ->where('sales.id', '=', $data['id'])
                ->whereNull('pay_sales.deleted_at')
                ->leftJoin('pay_sales', 'pay_sales.bank_payment_id', '=', 'payments.id')
                ->leftJoin('sales', 'sales.id', '=', 'pay_sales.sale_id')
                ->select('payments.id as payment_id', 'pay_sales.id', 'payments.name', 'payments.method',
                    'pay_sales.cant', 'pay_sales.mora', 'pay_sales.cantMora', 'pay_sales.cant_pay',
                    'pay_sales.currency_id', 'pay_sales.cant_back')
                ->get();
            $sale['client'] = DB::table('clients')
                ->leftJoin('sales', 'sales.client_id', '=', 'clients.id')
                ->where('sales.id', '=', $data['id'])
                ->first();
            $totalCost = 0;
            $subTotal = 0;
            $totalTax = 0;
            $totalDisc = 0;
            $totalRefund = 0;
            $cashPays = [];
            foreach ($sale['pays'] as $p => $pay) {
                $pay->currency = $pay->currency_id ? ExchangeRate::findOrFail($pay->currency_id) : '';
                if($pay->method === 'cash'){
                    $cashPays[] = $pay;
                }
            }
            $sale['cashPays'] = $cashPays;
            foreach ($sale['articles'] as $k => $v) {
                $sale['articles'][$k]->images = DB::table('article_images')
                    ->where('article_images.article_id', '=', $v->id)
                    ->get();
                $sale['articles'][$k]->taxes = DB::table('taxes')
                    ->leftJoin('article_tax', 'article_tax.tax_id', '=', 'taxes.id')
                    ->leftJoin('articles', 'articles.id', '=', 'article_tax.article_id')
                    ->where('articles.id', '=', $v->id)
                    ->addSelect(['taxes.*'])
                    ->get();
                $sale['articles'][$k]->refounds = DB::table('refunds')
                    ->join('users', 'users.id', '=', 'refunds.created_by')
                    ->where('refunds.article_id', '=', $v->id)
                    ->where('refunds.sale_id', '=', $data['id'])
                    ->select('refunds.*', 'users.firstName as created_by')
                    ->get();
                $sale['articles'][$k]->modifiers = DB::table('modifiers')
                    ->leftJoin('sales_articles_shop_modifiers', 'sales_articles_shop_modifiers.modifier_id', '=',
                        'modifiers.id')
                    ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                        'sales_articles_shop_modifiers.sales_articles_shops_id')
                    ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                    ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                    ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                    ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                    ->where('articles.id', '=', $v->id)
                    ->where('shops.id', '=', $sale['shop']->shop_id)
                    ->where('sales.id', '=', $sale['id'])
                    ->addSelect(['modifiers.*'])
                    ->get();
                $sale['articles'][$k]->discount = DB::table('discounts')
                    ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', '=',
                        'discounts.id')
                    ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                        'sales_articles_shop_discounts.sales_articles_shops_id')
                    ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                    ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                    ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                    ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                    ->where('articles.id', '=', $v->id)
                    ->where('shops.id', '=', $sale['shop']->shop_id)
                    ->where('sales.id', '=', $sale['id'])
                    ->addSelect(['discounts.*'])
                    ->get();
                $sum = 0;
                $discount = 0;
                $refund = 0;
                $cantRefund = 0;
                foreach ($sale['articles'][$k]->discount as $j => $i) {
                    $discount += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
                foreach ($sale['articles'][$k]->modifiers as $j => $i) {
                    $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
                foreach ($sale['articles'][$k]->taxes as $j => $i) {
                    if ($i->type === 'added') {
                        $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                    }
                }
                foreach ($sale['articles'][$k]->refounds as $s => $t) {
                    $refund += $t->money;
                    $cantRefund += $t->cant;
                }
                $sale['articles'][$k]->moneyRefund = $refund;
                $sale['articles'][$k]->cantRefund = $cantRefund;
                $totalCost += $v->cant * $v->cost;
                $sale['articles'][$k]->totalPrice = $v->cant * $v->price + $sum - $discount - $refund;
                $subTotal += $sale['articles'][$k]->totalPrice;
                $totalRefund += $refund;

            }
            foreach ($sale['taxes'] as $j => $i) {
                $totalTax += $i->percent ? $subTotal * $i->value / 100 : $i->value;
            }
            foreach ($sale['discounts'] as $j => $i) {
                $totalDisc += $i->percent ? $subTotal * $i->value / 100 : $i->value;
            }
            $totalPrice = $subTotal + $totalTax - $totalDisc;
            $sale['totalCost'] = round($totalCost, 2);
            $sale['totalTax'] = round($totalTax, 2);
            $sale['totalDisc'] = round($totalDisc, 2);
            $sale['subTotal'] = round($subTotal, 2);
            $sale['totalRefund'] = round($totalRefund, 2);
            $sale['totalPrice'] = round($totalPrice, 2);
            $sale['create'] = DB::table('users')
                ->where('users.id', '=', $sale['created_by'])
                ->select('firstName', 'lastName')
                ->first();
            $sale->state = $data['state'];
//            $this->sendEmail('emails.facture', ['editSale' => $sale], 'jlbarrero19990@gmail.com', 'Factura');
        }
        $this->managerBy('edit', $saleState);
        return $saleState;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function findAllByCompany()
    {
        $company = CompanyManager::getCompanyByAdmin();
        if (auth()->user()['isAdmin'] === 1) {
            $sales = Sale::latest()
                ->with('company')
                ->where('type', '=', 'sale')
                ->get();
        } else {
            $sales = Sale::latest()
                ->where('company_id', '=', $company->id)
                ->where('type', '=', 'sale')
                ->when($this->getAccessPermit()[2]['actions']['just_yours'] === true, function ($query) {
                    return $query->where('created_by', '=', cache()->get('userPin')['id']);
                })
                ->with('company')
                ->with('box')
                ->with('articles_shops')
                ->with('taxes')
                ->with('discounts')
                ->with('refounds')
                ->with('pay_sales')
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        return $this->filterSale($sales);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getAccessPermit()
    {
        return json_decode(cache()->get('userPin')->position['access_permit'], true);
    }

    public function filterSale($sales)
    {
        foreach ($sales as $key => $value) {
            $sales[$key]['shop'] = DB::table('shops')
                ->select('shops.*', 'shops.id as shop_id')
                ->where('sales.id', '=', $value['id'])
                ->leftJoin('articles_shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->first();
            $sales[$key]['articles'] = DB::table('articles')
                ->select([
                    'articles.*', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                    'articles_shops.stock as inventory', 'articles.id as article_id'
                ])
                ->where('sales.id', '=', $value['id'])
                ->leftJoin('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
                ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->get();
            $sales[$key]['pays'] = DB::table('payments')
                ->where('sales.id', '=', $value['id'])
                ->whereNull('pay_sales.deleted_at')
                ->join('bank_payments', 'bank_payments.payment_id', '=', 'payments.id')
                ->join('pay_sales', 'pay_sales.bank_payment_id', '=', 'bank_payments.id')
                ->join('sales', 'sales.id', '=', 'pay_sales.sale_id')
                ->select('payments.id as payment_id', 'pay_sales.id', 'payments.name', 'payments.method',
                    'pay_sales.cant', 'pay_sales.mora', 'pay_sales.cantMora', 'pay_sales.cant_pay',
                    'pay_sales.currency_id', 'pay_sales.cant_back')
                ->get();
            $sales[$key]['client'] = DB::table('clients')
                ->leftJoin('sales', 'sales.client_id', '=', 'clients.id')
                ->where('sales.id', '=', $value['id'])
                ->first();
            $totalCost = 0;
            $subTotal = 0;
            $totalTax = 0;
            $totalDisc = 0;
            $totalRefund = 0;
            foreach ($sales[$key]['pays'] as $p => $pay) {
                $pay->currency = $pay->currency_id ? ExchangeRate::findOrFail($pay->currency_id) : '';
            }
            foreach ($sales[$key]['articles'] as $k => $v) {
                $sales[$key]['articles'][$k]->images = DB::table('article_images')
                    ->where('article_images.article_id', '=', $v->id)
                    ->get();
                $sales[$key]['articles'][$k]->taxes = DB::table('taxes')
                    ->leftJoin('article_tax', 'article_tax.tax_id', '=', 'taxes.id')
                    ->leftJoin('articles', 'articles.id', '=', 'article_tax.article_id')
                    ->where('articles.id', '=', $v->id)
                    ->addSelect(['taxes.*'])
                    ->get();
                $sales[$key]['articles'][$k]->refounds = DB::table('refunds')
                    ->join('users', 'users.id', '=', 'refunds.created_by')
                    ->where('refunds.article_id', '=', $v->id)
                    ->where('refunds.sale_id', '=', $value->id)
                    ->select('refunds.*', 'users.firstName as created_by')
                    ->get();
                $sales[$key]['articles'][$k]->modifiers = DB::table('modifiers')
                    ->leftJoin('sales_articles_shop_modifiers', 'sales_articles_shop_modifiers.modifier_id', '=',
                        'modifiers.id')
                    ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                        'sales_articles_shop_modifiers.sales_articles_shops_id')
                    ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                    ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                    ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                    ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                    ->where('articles.id', '=', $v->id)
                    ->where('shops.id', '=', $sales[$key]['shop']->shop_id)
                    ->where('sales.id', '=', $sales[$key]['id'])
                    ->addSelect(['modifiers.*'])
                    ->get();
                $sales[$key]['articles'][$k]->discount = DB::table('discounts')
                    ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', '=',
                        'discounts.id')
                    ->leftJoin('sales_articles_shops', 'sales_articles_shops.id', '=',
                        'sales_articles_shop_discounts.sales_articles_shops_id')
                    ->leftJoin('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                    ->leftJoin('articles', 'articles.id', '=', 'articles_shops.article_id')
                    ->leftJoin('shops', 'shops.id', '=', 'articles_shops.shop_id')
                    ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                    ->where('articles.id', '=', $v->id)
                    ->where('shops.id', '=', $sales[$key]['shop']->shop_id)
                    ->where('sales.id', '=', $sales[$key]['id'])
                    ->addSelect(['discounts.*'])
                    ->get();
                $sum = 0;
                $discount = 0;
                $refund = 0;
                $cantRefund = 0;
                foreach ($sales[$key]['articles'][$k]->discount as $j => $i) {
                    $discount += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
                foreach ($sales[$key]['articles'][$k]->modifiers as $j => $i) {
                    $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
                foreach ($sales[$key]['articles'][$k]->taxes as $j => $i) {
                    if ($i->type === 'added') {
                        $sum += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                    }
                }
                foreach ($sales[$key]['articles'][$k]->refounds as $s => $t) {
                    $refund += $t->money;
                    $cantRefund += $t->cant;
                }
                $sales[$key]['articles'][$k]->moneyRefund = $refund;
                $sales[$key]['articles'][$k]->cantRefund = $cantRefund;
                $totalCost += $v->cant * $v->cost;
                $sales[$key]['articles'][$k]->totalPrice = $v->cant * $v->price + $sum - $discount - $refund;
                $subTotal += $sales[$key]['articles'][$k]->totalPrice;
                $totalRefund += $refund;

            }
            foreach ($sales[$key]['taxes'] as $j => $i) {
                $totalTax += $i->percent ? $subTotal * $i->value / 100 : $i->value;
            }
            foreach ($sales[$key]['discounts'] as $j => $i) {
                $totalDisc += $i->percent ? $subTotal * $i->value / 100 : $i->value;
            }
            $totalPrice = $subTotal + $totalTax - $totalDisc;
            $sales[$key]['totalCost'] = round($totalCost, 2);
            $sales[$key]['totalTax'] = round($totalTax, 2);
            $sales[$key]['totalDisc'] = round($totalDisc, 2);
            $sales[$key]['subTotal'] = round($subTotal, 2);
            $sales[$key]['totalRefund'] = round($totalRefund, 2);
            $sales[$key]['totalPrice'] = round($totalPrice, 2);
            $sales[$key]['create'] = DB::table('users')
                ->where('users.id', '=', $value['created_by'])
                ->select('firstName', 'lastName')
                ->first();
        }
        return $sales;
    }

    /**
     * @param $data
     * @return Sale
     * @throws Exception
     */
    public function new($data): Sale
    {
        if ($data['online'] !== true) {
            $this->validBoxToSale(['id' => $data['box']['id']]);
        }
        $sale = Sale::create([
            'no_facture' => $data['no_facture'],
            'company_id' => $data['company_id']
        ]);

        if (isset($data['box']['id'])) {
            $sale->box_id = $data['box']['id'];
        }
        if ($data['online']) {
            $sale->online = $data['online'] ?: false;
            $sale->box_id = Box::latest()->where('shop_id', '=', $data['shop']['id'])->where('online', '=',
                true)->get();
        }
        $sale->state = $data['state'] ?: null;
        $sale->type = 'sale';
        if (isset($data['client']['id'])) {
            $sale->client_id = $data['client']['id'];
        }
        $sale->save();
        $this->updateSaleData($sale, $data, false);
        return $sale;
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function validBoxToSale($data): void
    {
        $box = Box::findOrFail($data['id']);
        if ($box->state === 'close') {
            $bManager = new BoxManager();
            $bManager->createOpenClose([
                'box' => ['id' => $data['id']],
                'open_to' => ['id' => cache()->get('userPin')['id']],
                'open_money' => 0
            ]);
        }
    }

    /**
     * @param $sale
     * @param $data
     * @param $edit
     * @return Sale
     * @throws Exception
     */
    public function updateSaleData($sale, $data, $edit): Sale
    {
        $articles = $data['articles'];
        $pays = $data['pays'];
        foreach ($articles as $key => $value) {
            $articleShop = ArticlesShops::latest()
                ->where('article_id', '=', $value['article_id'])
                ->where('shop_id', '=', $edit ? $data['shop']['shop_id'] : $data['shop']['id'])
                ->first();
            $oldCant = $this->createSaleArticleShop($sale, $articleShop->id, $value);
            if ($data['state'] !== 'preform') {
                $articleShop['stock'] = $articleShop['stock'] + $oldCant - $value['cant'];
                $this->notificate([
                    'company_id' => Articles::latest()->where('id', '=', $value['article_id'])->with('company')->first()->company->id,
                    'params' => $articleShop['stock'],
                    'msg' => 'under_inventory',
                    'type' => 'info',
                    'read' => false
                ]);
                if ($articleShop['stock'] < $articleShop['under_inventory']) {
                    $company = Company::findOrFail(CompanyManager::getCompanyByAdmin()->id);
//                    $this->sendEmail('emails.under-stock', [
//                        'client' => $company->name,
//                        'product' => Articles::findOrFail($articleShop['article_id'])->name,
//                        'cant' => $articleShop['stock'],
//                        'shop' => Shop::findOrFail($articleShop['shop_id'])->name,
//                    ], $company->email, 'INNELI Informa sobre Bajo Inventario');
                }
            }
            $articleShop->save();
        }
        foreach ($pays as $k => $pay) {
            if (!array_key_exists('id', $pay)) {
                $bPayment = BankPayment::latest()
                    ->where('bank_id', '=',$pay['bank']['id'])
                    ->where('payment_id', '=',$pay['payment_id'])
                    ->first();
                $pSale = PaySale::create([
                    'bank_payment_id' => $bPayment->id,
                    'sale_id' => $sale->id
                ]);
            } else {
                $pSale = PaySale::findOrFail($pay['id']);
            }
            $pSale['cant'] = $pay['cant'];
            if ($pay['method'] === 'cash') {
                $pSale['currency_id'] = count($pay['currency']) > 0 ? $pay['currency']['id'] : '';
                $pSale['cant_pay'] = $pay['cant_pay'];
                $pSale['cant_back'] = $pay['cant_back'];
            }
            if ($pay['name'] === 'credit') {
                $pSale['mora'] = $pay['mora'];
                $pSale['cantMora'] = $pay['cantMora'];

            }
            $pSale->save();
            $this->managerBy('new', $pSale);
        }
        $taxes = [];
        foreach ($data['taxes'] as $k => $v) {
            $taxes[] = $v['id'];
        }
        $sale->taxes()->sync(Tax::find($taxes));
        $discounts = [];
        foreach ($data['discounts'] as $k => $v) {
            $discounts[] = $v['id'];
        }
        $edit ? $this->managerBy('edit', $sale) : $this->managerBy('new', $sale);
        $sale->discounts()->sync($discounts);
        return $sale;
    }

    /**
     * @param $sale
     * @param $articleShopId
     * @param $data
     * @return float
     */
    private function createSaleArticleShop($sale, $articleShopId, $data): float
    {
        $cant = 0;
        $salesArtShop = SalesArticlesShops::latest()
            ->where('sale_id', '=', $sale->id)
            ->where('articles_shops_id', '=', $articleShopId)
            ->get();
        if (count($salesArtShop) === 0) {
            $saleAS = SalesArticlesShops::create([
                'sale_id' => $sale->id,
                'articles_shops_id' => $articleShopId,
                'cant' => $data['cant'],
                'price' => $data['price']
            ]);
        } else {
            $saleAS = SalesArticlesShops::findOrFail($salesArtShop[0]['id']);
            $cant = $saleAS['cant'];
            $saleAS['cant'] = $data['cant'];
            $saleAS['price'] = $data['price'];
            $saleAS->save();
        }
        $discounts = [];
        foreach ($data['discount'] as $key => $discount) {
            $discounts[] = $discount['id'];
        }
        $saleAS->discount()->sync($discounts);
        $modifiers = [];
        if (array_key_exists('modifiers', $data)) {
            foreach ($data['modifiers'] as $key => $modifier) {
                $modifiers[] = $modifier['id'];
            }
            $saleAS->modifier()->sync($modifiers);
        }
        return $cant;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $sale = Sale::findOrFail($id);
        $sale->no_facture = $data['no_facture'];
        $sale->state = $data['state'];
        if (isset($data['client']['id'])) {
            $sale->client_id = $data['client']['id'];
        } else {
            $sale->client_id = null;
        }
        $sale->save();
        $this->removeSaleArticle($sale, $data['articles']);
        $this->removePaySale($sale, $data['pays']);
        $this->updateSaleData($sale, $data, true);

        return $sale;
    }

    /**
     * @param $sale
     * @param $articles
     * @throws Exception
     */
    private function removeSaleArticle($sale, $articles): void
    {
        $saleArtShopDB = SalesArticlesShops::latest()
            ->where('sale_id', '=', $sale->id)
            ->with('articles_shops')
            ->get();
        foreach ($saleArtShopDB as $art => $value) {
            $exist = false;
            foreach ($articles as $k => $v) {
                if (array_key_exists('id', $v) ? $v['id'] : $v['article_id'] === $value['articles_shops']['article_id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $artShop = ArticlesShops::findOrFail($value['articles_shops']['article_id']);
                $artShop['stock'] -= $value['cant'];
//                if ($artShop['stock'] < $artShop['under_inventory']) {
//                    $company = Company::findOrFail(CompanyManager::getCompanyByAdmin()->id);
//                    $this->notificate([
//                        'company_id' => $company->id,
//                        'params' => $artShop['stock'],
//                        'msg' => 'under_inventory',
//                        'type' => 'info',
//                        'read' => false
//                    ]);
//                    $this->sendEmail('emails.under-stock', [
//                        'client' => $company->name,
//                        'product' => Articles::findOrFail($value['articles_shops']['article_id'])->name,
//                        'cant' => $artShop['stock'],
//                        'shop' => Shop::findOrFail($value['articles_shops']['shop_id'])->name,
//                    ], $company->email, 'INNELI Informa sobre Bajo Inventario');
//                }
                $this->managerBy('edit', $artShop);
                $artShop->save();
            }
        }
    }

    /**
     * @param $sale
     * @param $pays
     * @throws Exception
     */
    private function removePaySale($sale, $pays): void
    {
        $paySale = PaySale::latest()
            ->where('sale_id', '=', $sale->id)
            ->with('method')
            ->get();
        foreach ($paySale as $art => $value) {
            $exist = false;
            foreach ($pays as $k => $v) {
                if (array_key_exists('id', $v) && $v['id'] === $value->id) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $payS = PaySale::findOrFail($value['id']);
                $payS->delete();
                $this->managerBy('delete', $payS);
                $payS->save();
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        $sale = Sale::findOrFail($id);
        $this->managerBy('delete', $sale);
        return $sale->delete();
    }

    /**
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function saleCategory($filter): array
    {
        $categoriesData = CategoryManager::findAllByCompany();
        $categories = [];
        $pos = 0;
        foreach ($categoriesData as $key => $value) {
            $categories[$pos]['name'] = $value->name;
            $categories[$pos]['color'] = $value->color;
            $categories[$pos]['data'] = $this->getArticleInfoCategory($filter, $value->id);
            $pos++;
        }
        return $categories;
    }

    /**
     * @param $filter
     * @param $id
     * @return array
     * @throws Exception
     */
    private function getArticleInfoCategory($filter, $id): array
    {
        $shops = [];
        $articles = DB::table('articles')
            ->select('articles.id as article_id', 'sales.id as sales_id',
                'sales_articles_shops.id as sales_articles_shops_id',
                'sales.created_at as sales_created_at', 'articles.name', 'sales_articles_shops.cant',
                'sales.created_by',
                'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost')
            ->leftJoin('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->where('articles.category_id', '=', $id)
            ->where('sales.type', '=', 'sale')
            ->where('sales.state', '=', 'accepted')
            ->whereNull('sales.deleted_at')
            ->orderBy('articles.created_at');
        if (array_key_exists('dates', $filter)) {
            $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
            $articles->whereDate('sales_articles_shops.created_at', '>=',
                $dates[0])->whereDate('sales_articles_shops.created_at', '<=', $dates[1]);
        }
        if (array_key_exists('shops', $filter)) {
            foreach ($filter['shops'] as $key => $value) {
                $shops[] = $value['id'];
            }
            $articles = $articles->whereIn('articles_shops.shop_id', $shops);
        }
        if ($this->getAccessPermit()[6]['actions']['just_yours']
            || $this->getAccessPermit()[2]['actions']['just_yours'] || $this->getAccessPermit()[9]['actions']['just_yours']) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        }
        $articles = $articles->get();
        $grossPrice = 0;
        $totalTax = 0;
        $totalDiscounts = 0;
        $totalCost = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $grossPrice += $price;
            $totalCost += $value->cost;
            $taxes = DB::table('taxes')
                ->leftJoin('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
                ->where('sales_articles_shop_discounts.sales_articles_shops_id', '=', $value->sales_articles_shops_id)
                ->get();
            foreach ($discounts as $k => $discount) {
                $totalDiscounts = $discount->percent ? $discount->value * $price / 100 : $price + $discount->value;
            }
        }
        return [
            'cantArt' => count($articles), 'grossPrice' => round($grossPrice, 2),
            'totalDiscount' => round($totalDiscounts, 2),
            'netPrice' => round(($grossPrice + $totalTax - $totalDiscounts), 2), 'totalCost' => round($totalCost, 2),
            'grossBenefit' => round(($grossPrice + $totalTax - $totalDiscounts) - $totalCost, 2),
            'totalTax' => round($totalTax, 2),
            'margin' => ($grossPrice === 0.00 || $grossPrice === 0) ? 0.00 : round(100 * $totalCost / $grossPrice, 2)
        ];
    }

    /**
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function salePayment($filter): array
    {
        $articles = DB::table('articles')
            ->select('articles.id as article_id', 'sales.id as sales_id',
                'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales.created_at as sales_created_at',
                'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost',
                'pay_sales.cant as cantPay',
                'payments.name as pay')
            ->leftJoin('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->leftJoin('pay_sales', 'pay_sales.sale_id', '=', 'sales.id')
            ->leftJoin('payments', 'payments.id', '=', 'pay_sales.bank_payment_id')
            ->where('sales.created_by', '=', cache()->get('userPin')['id'])
            ->where('sales.type', '=', 'sale')
            ->whereNull('sales.deleted_at')
            ->where('sales.state', '=', 'accepted');
        if (array_key_exists('dates', $filter)) {
            $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
            $articles->whereDate('sales_articles_shops.created_at', '>=',
                $dates[0])->whereDate('sales_articles_shops.created_at', '<=', $dates[1]);
        }
        if (array_key_exists('shops', $filter)) {
            $shops = [];
            foreach ($filter['shops'] as $key => $value) {
                $shops[] = $value['id'];
            }
            $articles = $articles->whereIn('articles_shops.shop_id', $shops);
        }
        if ($this->getAccessPermit()[9]['actions']['just_yours']) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        } elseif ($this->getAccessPermit()[6]['actions']['just_yours']
            && $this->getAccessPermit()[2]['actions']['just_yours']) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        }
        $articles = $articles->get()->unique('name');
        $result = [];
        $totalTax = 0;
        $totalDiscounts = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $taxes = DB::table('taxes')
                ->leftJoin('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
                ->where('sales_articles_shop_discounts.sales_articles_shops_id', '=', $value->sales_articles_shops_id)
                ->get();
            foreach ($discounts as $k => $discount) {
                $totalDiscounts = $discount->percent ? $discount->value * $price / 100 : $price + $discount->value;
            }
            if (!array_key_exists($value->pay, $result)) {
                $result[$value->pay]['cantTransactions'] = 0;
                $result[$value->pay]['grossPrice'] = 0;
                $result[$value->pay]['totalDiscount'] = 0;
                $result[$value->pay]['totalTax'] = 0;
                $result[$value->pay]['netPrice'] = 0;
            }
            $price = $value->price * $value->cant;
            $result[$value->pay]['cantTransactions'] += 1;
            $result[$value->pay]['grossPrice'] += $price;
            $result[$value->pay]['totalDiscount'] += $totalDiscounts;
            $result[$value->pay]['totalTax'] += round($totalTax, 2);
            $result[$value->pay]['netPrice'] = round($result[$value->pay]['grossPrice'] + $result[$value->pay]['totalTax'] - $result[$value->pay]['totalDiscount'],
                2);
        }
        $data = [];
        $pos = 0;
        foreach ($result as $item => $value) {
            $data[$pos]['name'] = $item;
            $data[$pos]['data']['cantTransactions'] = $value['cantTransactions'];
            $data[$pos]['data']['grossPrice'] = $value['grossPrice'];
            $data[$pos]['data']['totalDiscount'] = $value['totalDiscount'];
            $data[$pos]['data']['totalTax'] = $value['totalTax'];
            $data[$pos]['data']['netPrice'] = $value['netPrice'];
            $pos++;
        }
        return $data;
    }

    /**
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function saleByProduct($filter): array
    {
        $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
        $shops = [];
        foreach ($filter['shops'] as $key => $value) {
            $shops[] = $value['id'];
        }
        $articles = DB::table('articles')
            ->select('articles.id as article_id', 'sales.id as sales_id',
                'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                'sales_articles_shops.created_at', 'articles.cost', 'sales.created_at')
            ->leftJoin('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->when($this->getAccessPermit()[9]['actions']['just_yours'] === true, function ($query) {
                return $query->where('sales.created_by', '=', cache()->get('userPin')['id']);
            })
            ->whereDate('sales_articles_shops.created_at', '>=', $dates[0])
            ->whereDate('sales_articles_shops.updated_at', '<=', $dates[1])
            ->whereIn('articles_shops.shop_id', $shops)
            ->where('sales.type', '=', 'sale')
            ->whereNull('sales.deleted_at')
            ->where('sales.state', '=', 'accepted')
            ->get();
        $result = [];
        $totalTax = 0;
        $totalDiscounts = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $taxes = DB::table('taxes')
                ->leftJoin('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
                ->where('sales_articles_shop_discounts.sales_articles_shops_id', '=', $value->sales_articles_shops_id)
                ->get();
            foreach ($discounts as $k => $discount) {
                $totalDiscounts = $discount->percent ? $discount->value * $price / 100 : $price + $discount->value;
            }
            if (!array_key_exists($value->name, $result)) {
                $result[$value->name]['cantTransactions'] = 0;
                $result[$value->name]['grossPrice'] = 0;
                $result[$value->name]['totalDiscount'] = 0;
                $result[$value->name]['totalTax'] = 0;
                $result[$value->name]['netPrice'] = 0;
            }
            $price = $value->price * $value->cant;
            $result[$value->name]['cantTransactions'] += 1;
            $result[$value->name]['grossPrice'] += $price;
            $result[$value->name]['totalDiscount'] += $totalDiscounts;
            $result[$value->name]['totalTax'] += round($totalTax, 2);
            $result[$value->name]['netPrice'] = round($result[$value->name]['grossPrice'] + $result[$value->name]['totalTax']
                - $result[$value->name]['totalDiscount'], 2);
        }
        $data = [];
        $pos = 0;
        foreach ($result as $item => $value) {
            $data[$pos]['name'] = $item;
            $data[$pos]['data']['cantTransactions'] = $value['cantTransactions'];
            $data[$pos]['data']['grossPrice'] = $value['grossPrice'];
            $data[$pos]['data']['totalDiscount'] = $value['totalDiscount'];
            $data[$pos]['data']['totalTax'] = $value['totalTax'];
            $data[$pos]['data']['netPrice'] = $value['netPrice'];
            $pos++;
        }
        return $data;
    }

    /**
     * @param $filter
     * @return array
     * @throws Exception
     */
    public function saleEmployer($filter): array
    {
        $articles = DB::table('articles')
            ->select('articles.id as article_id', 'sales.id as sales_id',
                'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales_articles_shops.price', 'sales.created_by',
                'sales_articles_shops.created_at', 'articles.cost')
            ->leftJoin('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->where('sales.type', '=', 'sale')
            ->whereNull('sales.deleted_at')
            ->where('sales.state', '=', 'accepted');
        if (array_key_exists('dates', $filter)) {
            $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
            $articles->whereDate('sales_articles_shops.created_at', '>=',
                $dates[0])->whereDate('sales_articles_shops.created_at', '<=', $dates[1]);
        }
        if (array_key_exists('shops', $filter)) {
            $shops = [];
            foreach ($filter['shops'] as $key => $value) {
                $shops[] = $value['id'];
            }
            $articles = $articles->whereIn('articles_shops.shop_id', $shops);
        }
        if ($this->getAccessPermit()[6]['actions']['just_yours']
            || $this->getAccessPermit()[2]['actions']['just_yours'] || $this->getAccessPermit()[9]['actions']['just_yours']) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        }
        $articles = $articles->get();
        $result = [];
        $totalTax = 0;
        $totalDiscounts = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $taxes = DB::table('taxes')
                ->leftJoin('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->leftJoin('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
                ->where('sales_articles_shop_discounts.sales_articles_shops_id', '=', $value->sales_articles_shops_id)
                ->get();
            foreach ($discounts as $k => $discount) {
                $totalDiscounts = $discount->percent ? $discount->value * $price / 100 : $price + $discount->value;
            }
            if (!array_key_exists($value->created_by, $result)) {
                $result[$value->created_by]['cantTransactions'] = 0;
                $result[$value->created_by]['grossPrice'] = 0;
                $result[$value->created_by]['totalDiscount'] = 0;
                $result[$value->created_by]['totalTax'] = 0;
                $result[$value->created_by]['netPrice'] = 0;
            }
            $price = $value->price * $value->cant;
            $result[$value->created_by]['cantTransactions'] += 1;
            $result[$value->created_by]['grossPrice'] += $price;
            $result[$value->created_by]['totalDiscount'] += $totalDiscounts;
            $result[$value->created_by]['totalTax'] += round($totalTax, 2);
            $result[$value->created_by]['netPrice'] = round($result[$value->created_by]['grossPrice'] + $result[$value->created_by]['totalTax'] - $result[$value->created_by]['totalDiscount'],
                2);
        }
        $data = [];
        $pos = 0;
        foreach ($result as $item => $value) {
            $data[$pos]['name'] = User::latest()->where('id', '=', $item)->get()[0];
            $data[$pos]['data']['cantTransactions'] = $value['cantTransactions'];
            $data[$pos]['data']['grossPrice'] = $value['grossPrice'];
            $data[$pos]['data']['totalDiscount'] = $value['totalDiscount'];
            $data[$pos]['data']['totalTax'] = $value['totalTax'];
            $data[$pos]['data']['netPrice'] = $value['netPrice'];
            $pos++;
        }
        return $data;
    }

}
