<?php

namespace App\Managers;

use App\ArticlesShops;
use App\Box;
use App\PaySale;
use App\Sale;
use App\SalesArticlesShops;
use App\Tax;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class SaleManager extends BaseManager
{
    /**
     * @param  int  $limit
     * @return mixed
     */
    public function findSalesByLimit(int $limit)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $sales = Sale::latest()
            ->where('company_id', '=', $company->id)
            ->orderBy('created_at', 'ASC')
            ->take($limit)
            ->get();
        foreach ($sales as $key => $value) {
            $sales[$key]['created'] = DB::table('users')
                ->where('users.id', '=', $value->created_by)
                ->first();
            $sales[$key]['client'] = DB::table('clients')
                ->where('sales.id', '=', $value['id'])
                ->join('sales', 'sales.client_id', '=', 'clients.id')
                ->first();
        }
        return $sales;
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
     * @return mixed
     * @throws Exception
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $sales = Sale::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $sales = Sale::latest()
                ->where('company_id', '=', $company->id)
                ->when($this->getAccessPermit()[2]->actions->just_yours === true, function ($query) {
                    return $query->where('created_by', '=', cache()->get('userPin')['id']);
                })
                ->with('company')
                ->with('box')
                ->with('articles_shops')
                ->with('taxes')
                ->with('discounts')
                ->with('refounds')
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
        return json_decode(cache()->get('userPin')->position['access_permit']);
    }

    public function filterSale($sales)
    {
        foreach ($sales as $key => $value) {
            $sales[$key]['shop'] = DB::table('shops')
                ->select('shops.*', 'shops.id as shop_id')
                ->where('sales.id', '=', $value['id'])
                ->join('articles_shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->first();
            $sales[$key]['articles'] = DB::table('articles')
                ->select([
                    'articles.*', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                    'articles_shops.stock as inventory', 'articles.id as article_id'
                ])
                ->where('sales.id', '=', $value['id'])
                ->join('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
                ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->get();
            $sales[$key]['pays'] = DB::table('payments')
                ->where('sales.id', '=', $value['id'])
                ->where('pay_sales.deleted_at', '=',null)
                ->join('pay_sales', 'pay_sales.payment_id', '=', 'payments.id')
                ->join('sales', 'sales.id', '=', 'pay_sales.sale_id')
                ->select('payments.id as payment_id', 'pay_sales.id', 'payments.name',
                    'payments.method', 'pay_sales.cant', 'pay_sales.mora', 'pay_sales.cantMora')
                ->get();
            $sales[$key]['client'] = DB::table('clients')
                ->join('sales', 'sales.client_id', '=', 'clients.id')
                ->where('sales.id', '=', $value['id'])
                ->first();
            $totalCost = 0;
            $totalPrice = 0;
            $totalRefund = 0;
            foreach ($sales[$key]['articles'] as $k => $v) {
                $sales[$key]['articles'] [$k]->images = DB::table('article_images')
                    ->where('article_images.article_id', '=', $v->id)
                    ->get();
                $sales[$key]['articles'][$k]->taxes = DB::table('taxes')
                    ->join('article_tax', 'article_tax.tax_id', '=', 'taxes.id')
                    ->join('articles', 'articles.id', '=', 'article_tax.article_id')
                    ->where('articles.id', '=', $v->id)
                    ->addSelect(['taxes.*'])
                    ->get();
                $sales[$key]['articles'][$k]->refounds = DB::table('refunds')
                    ->join('users', 'users.id', '=', 'refunds.created_by')
                    ->where('refunds.article_id', '=', $v->id)
                    ->where('refunds.sale_id', '=', $value->id)
                    ->select('refunds.*', 'users.firstName as created_by')
                    ->get();
                $sales[$key]['articles'][$k]->discount = DB::table('discounts')
                    ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', '=',
                        'discounts.id')
                    ->join('sales_articles_shops', 'sales_articles_shops.id', '=',
                        'sales_articles_shop_discounts.sales_articles_shops_id')
                    ->join('articles_shops', 'articles_shops.id', '=', 'sales_articles_shops.articles_shops_id')
                    ->join('articles', 'articles.id', '=', 'articles_shops.article_id')
                    ->join('shops', 'shops.id', '=', 'articles_shops.shop_id')
                    ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
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
                $totalPrice += $sales[$key]['articles'][$k]->totalPrice;
                $discount = 0;
                foreach ($sales[$key]['taxes'] as $j => $i) {
                    $sum += $i->percent ? $totalPrice * $i->value / 100 : $i->value;
                }
                $sum = 0;
                foreach ($sales[$key]['discounts'] as $j => $i) {
                    $discount += $i->percent ? $totalPrice * $i->value / 100 : $i->value;
                }
                $totalPrice = $totalPrice + $sum - $discount;
                $totalRefund += $refund;

            }
            $sales[$key]['totalCost'] = round($totalCost, 2);
            $sales[$key]['totalRefund'] = round($totalRefund, 2);
            $sales[$key]['totalPrice'] = round($totalPrice, 2);
            $sales[$key]['create'] = DB::table('users')
                ->where('users.id', '=', $value['created_by'])
                ->select('firstName', 'lastName')
                ->get()[0]
            ;
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
        $this->validBoxToSale(['id' => $data['box']['id']]);
        $sale = Sale::create([
            'no_facture' => $data['no_facture'],
            'company_id' => $data['company_id']
        ]);
        if (isset($data['box']['id'])) {
            $sale->box_id = $data['box']['id'];
        }
        $sale->state = $data['state'] ?: null;
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
    public function validBoxToSale($data)
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
     * @throws Exception
     */
    public function updateSaleData($sale, $data, $edit): void
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
            }
            $articleShop->save();
        }
        foreach ($pays as $k => $pay) {
            if(!array_key_exists('id', $pay)) {
                $pSale = PaySale::create([
                    'payment_id' => $pay['payment_id'],
                    'sale_id' => $sale->id
                ]);
            }
            else{
                $pSale = PaySale::findOrFail($pay['id']);
            }
                $pSale['cant'] = $pay['cant'];
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
            var_dump($exist);
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
            ->join('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->where('articles.category_id', '=', $id)
            ->orderBy('articles.created_at')
            ->select('articles.id as article_id', 'sales.id as sales_id',
                'sales_articles_shops.id as sales_articles_shops_id',
                'sales.created_at as sales_created_at', 'articles.name', 'sales_articles_shops.cant',
                'sales.created_by',
                'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost');
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
        if ($this->getAccessPermit()[6]->actions->just_yours
            || $this->getAccessPermit()[2]->actions->just_yours || $this->getAccessPermit()[9]->actions->just_yours) {
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
                ->join('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
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
            ->leftJoin('payments', 'payments.id', '=', 'pay_sales.payment_id')
            ->where('sales.created_by', '=', cache()->get('userPin')['id']);
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
        if (($this->getAccessPermit()[6]->actions->just_yours
                && $this->getAccessPermit()[2]->actions->just_yours) || $this->getAccessPermit()[9]->actions->just_yours) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        }
        $articles = $articles->get()->unique('name');
        $result = [];
        $grossPrice = 0;
        $totalTax = 0;
        $totalDiscounts = 0;
        $totalCost = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $grossPrice += $price;
            $totalCost += $value->cost;
            $taxes = DB::table('taxes')
                ->join('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
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
                'sales_articles_shops.created_at', 'articles.cost')
            ->leftJoin('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->leftJoin('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->when($this->getAccessPermit()[9]->actions->just_yours === true, function ($query) {
                return $query->where('sales.created_by', '=', cache()->get('userPin')['id']);
            })
            ->whereDate('sales_articles_shops.created_at', '>=', $dates[0])
            ->whereDate('sales_articles_shops.created_at', '<=', $dates[1])
            ->whereIn('articles_shops.shop_id', $shops)
            ->get();
        $result = [];
        $grossPrice = 0;
        $totalTax = 0;
        $totalDiscounts = 0;
        $totalCost = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $grossPrice += $price;
            $totalCost += $value->cost;
            $taxes = DB::table('taxes')
                ->join('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
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
            ->leftJoin('sales', 'sales.id', '=', 'sales_articles_shops.sale_id');

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
        if ($this->getAccessPermit()[6]->actions->just_yours
            || $this->getAccessPermit()[2]->actions->just_yours || $this->getAccessPermit()[9]->actions->just_yours) {
            $articles = $articles->where('sales.created_by', '=', cache()->get('userPin')['id']);
        }
        $articles = $articles->get();
        $result = [];
        $grossPrice = 0;
        $totalTax = 0;
        $totalDiscounts = 0;
        $totalCost = 0;
        foreach ($articles as $key => $value) {
            $price = $value->price * $value->cant;
            $grossPrice += $price;
            $totalCost += $value->cost;
            $taxes = DB::table('taxes')
                ->join('article_tax', 'article_tax.tax_id', 'taxes.id')
                ->where('article_tax.article_id', '=', $value->article_id)
                ->get();
            foreach ($taxes as $k => $tax) {
                $totalTax = $tax->percent ? $tax->value * $price / 100 : $price + $tax->value;
            }
            $discounts = DB::table('discounts')
                ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', 'discounts.id')
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
