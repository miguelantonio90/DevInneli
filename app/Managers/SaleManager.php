<?php


namespace App\Managers;


use App\ArticlesShops;
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
                    $totalCost += $v->cant * $v->cost;
                    $totalPrice += $v->cant * $v->price;
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
        $sale->payment_id = $data['payments']['payment_id'];
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
            $this->createSaleArticleShop($sale, $articleShop->id, $value);
            $articleShop['stock'] -= $value['cant'];
            $articleShop->save();
        }
        $taxes = [];
        foreach ($data['taxes'] as $k => $v) {
            $taxes[] = $v['id'];
        }
        $sale->taxes()->sync(Tax::find($taxes));
    }

    /**
     * @param $sale
     * @param $articleShopId
     * @param $data
     */
    private function createSaleArticleShop($sale, $articleShopId, $data): void
    {
        $salesArtShop = SalesArticlesShops::latest()
            ->where('sale_id', '=', $sale->id)
            ->where('articles_shops_id', '=', $articleShopId)
            ->get();
        if (count($salesArtShop) === 0) {
            SalesArticlesShops::create([
                'sale_id' => $sale->id,
                'articles_shops_id' => $articleShopId,
                'cant' => $data['cant'],
                'cost' => $data['cost']
            ]);
        } else {
            $invAS = SalesArticlesShops::findOrFail($salesArtShop[0]['id']);
            $invAS['cant'] = $data['cant'];
            $invAS['cost'] = $data['cost'];
            $invAS->save();
        }
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
