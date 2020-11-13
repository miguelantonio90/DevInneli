<?php


namespace App\Managers;


use App\Articles;
use App\ArticlesShops;
use App\Discount;
use App\SalesArticlesShops;
use App\Sale;
use App\Tax;
use Illuminate\Support\Facades\DB;

class SaleManager
{
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
                    ->join('sales_articles_shop_discounts', 'sales_articles_shop_discounts.discount_id', '=', 'discounts.id')
                    ->join('sales_articles_shops', 'sales_articles_shops.id', '=', 'sales_articles_shop_discounts.sales_articles_shops_id')
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
                $totalPrice = $totalPrice + $sum- $discount;

            }
            $sales[$key]['totalCost'] = round($totalCost, 2);
            $sales[$key]['totalPrice'] = round($totalPrice, 2);
        }
        return $sales;
    }

    public function new($data)
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
        foreach ($data['discount'] as $key => $discount){
            $discounts[] = $discount['id'];
        }
        $saleAS->discount()->sync($discounts);
        return $cant;
    }


    /**
     * @param $sale
     * @param $articles
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
                $artShop->save();
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Sale::findOrFail($id)->delete();
    }

}
