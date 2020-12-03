<?php


namespace App\Managers;


use App\Articles;
use App\ArticlesShops;
use App\Sale;
use App\SalesArticlesShops;
use App\Tax;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SaleManager extends BaseManager
{
    public function findAllByCompany()
    {
        $sales = [];
        if (auth()->user()['isAdmin'] === 1) {
            $sales = Sale::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $sales = Sale::latest()
                ->where('company_id', '=', $company->id)
                ->with('company')
                ->with('articles_shops')
                ->with('taxes')
                ->with('discounts')
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        foreach ($sales as $key => $value) {
            $sales[$key]['shop'] = DB::table('shops')
                ->join('articles_shops', 'shops.id', '=', 'articles_shops.shop_id')
                ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->where('sales.id', '=', $value['id'])
                ->select('shops.*', 'shops.id as shop_id')
                ->get()[0];
            $sales[$key]['articles'] = DB::table('articles')
                ->join('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
                ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
                ->select([
                    'articles.*', 'sales_articles_shops.cant', 'sales_articles_shops.price',
                    'articles_shops.stock as inventory', 'articles.id as article_id'
                ])
                ->where('sales.id', '=', $value['id'])
                ->get();
            $payments = DB::table('payments')
                ->join('sales', 'sales.payment_id', '=', 'payments.id')
                ->where('sales.id', '=', $value['id'])
                ->get();
            $sales[$key]['payments'] = count($payments) > 0 ? $payments[0] : null;
            $sales[$key]['client'] = DB::table('clients')
                ->join('sales', 'sales.client_id', '=', 'clients.id')
                ->where('sales.id', '=', $value['id'])
                ->get()[0];
            $totalCost = 0;
            $totalPrice = 0;
            foreach ($sales[$key]['articles'] as $k => $v) {
                $sales[$key]['articles'][$k]->taxes = DB::table('taxes')
                    ->join('article_tax', 'article_tax.tax_id', '=', 'taxes.id')
                    ->join('articles', 'articles.id', '=', 'article_tax.article_id')
                    ->where('articles.id', '=', $v->id)
                    ->addSelect(['taxes.*'])
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
                foreach ($sales[$key]['articles'][$k]->discount as $j => $i) {
                    $discount += $i->percent ? $v->cant * $v->price * $i->value / 100 : $i->value;
                }
                $totalCost += $v->cant * $v->cost;
                $totalPrice += $v->cant * $v->price + $sum - $discount;
                $discount = 0;
                foreach ($sales[$key]['taxes'] as $j => $i) {
                    $sum += $i->percent ? $totalPrice * $i->value / 100 : $i->value;
                }
                $sum = 0;
                foreach ($sales[$key]['discounts'] as $j => $i) {
                    $discount += $i->percent ? $totalPrice * $i->value / 100 : $i->value;
                }
                $totalPrice = $totalPrice + $sum - $discount;

            }
            $sales[$key]['totalCost'] = round($totalCost, 2);
            $sales[$key]['totalPrice'] = round($totalPrice, 2);
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
        $sale = Sale::create([
            'no_facture' => $data['no_facture'],
            'pay' => $data['pay'] ?: null,
            'company_id' => $data['company_id'],
            'client_id' => $data['client']['id']
        ]);
        if (isset($data['payments']['id'])) {
            $sale->payment_id = $data['payments']['id'];
        }
        $sale->save();
        $this->updateSaleData($sale, $data, false);
        return $sale;
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
        foreach ($articles as $key => $value) {
            $articleShop = ArticlesShops::latest()
                ->where('article_id', '=', $value['article_id'])
                ->where('shop_id', '=', $edit ? $data['shop']['shop_id'] : $data['shop']['id'])
                ->get()[0];
            $oldCant = $this->createSaleArticleShop($sale, $articleShop->id, $value);
            $articleShop['stock'] = $articleShop['stock'] + $oldCant - $value['cant'];
            $articleShop->save();
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
     */
    public function edit($id, $data)
    {
        $sale = Sale::findOrFail($id);
        $sale->no_facture = $data['no_facture'];
        $sale->pay = $data['pay'];
        if (isset($data['payments']['payment_id'])) {
            $sale->payment_id = $data['payments']['payment_id'];
        }
        $sale->client_id = $data['client']['client_id'];
        $sale->save();
        $this->removeSaleArticle($sale, $data['articles']);
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
                if (key_exists('id', $v) ? $v['id'] : $v['article_id'] === $value['articles_shops']['article_id']) {
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

    public function saleCategory($filter)
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
     */
    private function getArticleInfoCategory($filter, $id): array
    {
        $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
        $shops = [];
        foreach ($filter['shops'] as $key => $value) {
            $shops[] = $value['id'];
        }
        $articles = DB::table('articles')
            ->join('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->whereDate('sales_articles_shops.created_at', '>=', $dates[0])
            ->whereDate('sales_articles_shops.created_at', '<=', $dates[1])
            ->where('articles.category_id', '=', $id)
            ->whereIn('articles_shops.shop_id', $shops)
            ->orderBy('articles.created_at')
            ->select('articles.id as article_id', 'sales.id as sales_id', 'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost')
            ->get();
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
        return ['cantArt' => count($articles), 'grossPrice' => round($grossPrice, 2), 'totalDiscount' => round($totalDiscounts, 2),
            'netPrice' => round(($grossPrice + $totalTax - $totalDiscounts), 2), 'totalCost' => round($totalCost, 2),
            'grossBenefit' => round(($grossPrice + $totalTax - $totalDiscounts) - $totalCost, 2), 'totalTax' => round($totalTax, 2),
            'margin' => ($grossPrice === 0.00 || $grossPrice === 0) ? 0.00 : round(100 * $totalCost / $grossPrice, 2)];
    }

    /**
     * @param $filter
     * @return array
     */
    public function salePayment($filter): array
    {
        $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
        $shops = [];
        foreach ($filter['shops'] as $key => $value) {
            $shops[] = $value['id'];
        }
        $pos = 0;
        $articles = DB::table('articles')
            ->join('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->whereDate('sales_articles_shops.created_at', '>=', $dates[0])
            ->whereDate('sales_articles_shops.created_at', '<=', $dates[1])
            ->whereIn('articles_shops.shop_id', $shops)
            ->select('articles.id as article_id', 'sales.id as sales_id', 'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost', 'sales.pay')
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
            if (!array_key_exists($value->pay, $result)) {
                $result[$value->pay]['cantTransactions'] = 0;
                $result[$value->pay]['grossPrice'] = 0;
                $result[$value->pay]['totalDiscount'] = 0;
                $result[$value->pay]['totalTax'] = 0;
                $result[$value->pay]['netPrice'] = 0;
            }
            $price = $value->price * $value->cant;
            $result[$value->pay]['cantTransactions'] +=1;
            $result[$value->pay]['grossPrice'] += $price;
            $result[$value->pay]['totalDiscount'] += $totalDiscounts;
            $result[$value->pay]['totalTax'] += round($totalTax, 2);
            $result[$value->pay]['netPrice'] = round($result[$value->pay]['grossPrice'] + $result[$value->pay]['totalTax']-$result[$value->pay]['totalDiscount'],2);
        }
        $data = [];
        $pos = 0;
        foreach ($result as $item=>$value)
        {
            $data[$pos]['name'] = $item;
            $data[$pos]['data']['cantTransactions'] = $value['cantTransactions'];
            $data[$pos]['data']['grossPrice'] = $value['grossPrice'];
            $data[$pos]['data']['totalDiscount'] = $value['totalDiscount'];
            $data[$pos]['data']['totalTax'] = $value['totalTax'];
            $data[$pos]['data']['netPrice'] = $value['netPrice'];
            $pos ++;
        }
        return $data;
    }

    public function saleByProduct($filter):array{
        $dates = [date($filter['dates'][0]), date($filter['dates'][1])];
        $shops = [];
        foreach ($filter['shops'] as $key => $value) {
            $shops[] = $value['id'];
        }
        $pos = 0;
        $articles = DB::table('articles')
            ->join('articles_shops', 'articles.id', '=', 'articles_shops.article_id')
            ->join('sales_articles_shops', 'sales_articles_shops.articles_shops_id', '=',
                'articles_shops.id')
            ->join('sales', 'sales.id', '=', 'sales_articles_shops.sale_id')
            ->whereDate('sales_articles_shops.created_at', '>=', $dates[0])
            ->whereDate('sales_articles_shops.created_at', '<=', $dates[1])
            ->whereIn('articles_shops.shop_id', $shops)
            ->select('articles.id as article_id', 'sales.id as sales_id', 'sales_articles_shops.id as sales_articles_shops_id',
                'articles.name', 'sales_articles_shops.cant', 'sales_articles_shops.price', 'sales_articles_shops.created_at', 'articles.cost', 'sales.pay')
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
            if (!array_key_exists($value->pay, $result)) {
                $result[$value->pay]['cantTransactions'] = 0;
                $result[$value->pay]['grossPrice'] = 0;
                $result[$value->pay]['totalDiscount'] = 0;
                $result[$value->pay]['totalTax'] = 0;
                $result[$value->pay]['netPrice'] = 0;
            }
            $price = $value->price * $value->cant;
            $result[$value->pay]['cantTransactions'] +=1;
            $result[$value->pay]['grossPrice'] += $price;
            $result[$value->pay]['totalDiscount'] += $totalDiscounts;
            $result[$value->pay]['totalTax'] += round($totalTax, 2);
            $result[$value->pay]['netPrice'] = round($result[$value->pay]['grossPrice'] + $result[$value->pay]['totalTax']-$result[$value->pay]['totalDiscount'],2);
        }
        $data = [];
        $pos = 0;
        foreach ($result as $item=>$value)
        {
            $data[$pos]['name'] = $item;
            $data[$pos]['data']['cantTransactions'] = $value['cantTransactions'];
            $data[$pos]['data']['grossPrice'] = $value['grossPrice'];
            $data[$pos]['data']['totalDiscount'] = $value['totalDiscount'];
            $data[$pos]['data']['totalTax'] = $value['totalTax'];
            $data[$pos]['data']['netPrice'] = $value['netPrice'];
            $pos ++;
        }
        return $data;
    }

}
