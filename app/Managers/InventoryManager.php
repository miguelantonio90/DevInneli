<?php


namespace App\Managers;


use App\ArticlesShops;
use App\InventoriesArticlesShops;
use App\Inventory;
use App\Tax;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryManager extends BaseManager
{
    public function findAllByCompany()
    {

        if (auth()->user()['isAdmin'] === 1) {
            $inventories = Inventory::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $inventories = Inventory::latest()
                ->where('company_id', '=', $company->id)
                ->with('company')
                ->with('articles_shops')
                ->with('taxes')
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        foreach ($inventories as $key => $value) {
            $inventories[$key]['shop'] = DB::table('shops')
                ->join('articles_shops', 'articles_shops.shop_id', '=', 'shops.id')
                ->join('inventories_articles_shops', 'inventories_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('inventories', 'inventories.id', '=', 'inventories_articles_shops.inventory_id')
                ->where('inventories.id', '=', $value['id'])
                ->select('shops.*', 'shops.id as shop_id')
                ->get()->first();
            $inventories[$key]['articles'] = DB::table('articles')
                ->join('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
                ->join('inventories_articles_shops', 'inventories_articles_shops.articles_shops_id', '=',
                    'articles_shops.id')
                ->join('inventories', 'inventories.id', '=', 'inventories_articles_shops.inventory_id')
                ->select([
                    'articles.*', 'inventories_articles_shops.cant', 'inventories_articles_shops.cost',
                    'articles_shops.stock as inventory', 'articles.id as article_id'
                ])
                ->where('inventories.id', '=', $value['id'])
                ->get();
            $payments = DB::table('payments')
                ->join('inventories', 'inventories.payment_id', '=', 'payments.id')
                ->where('inventories.id', '=', $value['id'])
                ->get();
            $inventories[$key]['payments'] = count($payments) > 0 ? $payments[0] : null;
            $inventories[$key]['supplier'] = DB::table('suppliers')
                ->join('inventories', 'inventories.supplier_id', '=', 'suppliers.id')
                ->where('inventories.id', '=', $value['id'])
                ->get()[0];
            $totalCost = 0;
            $totalPrice = 0;
            foreach ($inventories[$key]['articles'] as $k => $v) {
                $totalCost += $v->cant * $v->cost;
                $totalPrice += $v->cant * $v->price;
            }
            $inventories[$key]['totalCost'] = round($totalCost, 2);
            $inventories[$key]['totalPrice'] = round($totalPrice, 2);
        }

        return $inventories;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $inventory = Inventory::create([
            'no_facture' => $data['no_facture'],
            'pay' => $data['pay'] ?: null,
            'company_id' => $data['company_id']
        ]);
        if (isset($data['payments']['id'])) {
            $inventory->payment_id = $data['payments']['id'];
        }
        if (isset($data['supplier']['id'])) {
            $inventory->supplier_id = $data['supplier']['id'];
        }
        $inventory->save();
        $this->managerBy('new', $inventory);
        $this->updateInventoryData($inventory, $data, false);
        return $inventory;

    }

    /**
     * @param $inventory
     * @param $data
     * @param $edit
     */
    public function updateInventoryData($inventory, $data, $edit): void
    {
        $articles = $data['articles'];
        foreach ($articles as $key => $value) {
            $articlesShops = ArticlesShops::latest()
                ->where('article_id', '=', $value['article_id'])
                ->where('shop_id', '=', $edit ? $data['shop']['shop_id'] : $data['shop']['id'])
                ->get();
            if (count($articles) > 0) {
                $articleShop = $articlesShops[0];
            } else {
                $articleShop = ArticlesShops::create([
                    'article_id' => $value->id,
                    'shop_id' => $data['shop']['shop_id'],
                    'stock' => 0,
                    'price' => $data['price'],
                    'under_inventory' => 0
                ]);
            }
            $this->createInventoryArticleShop($inventory, $articleShop->id, $value);
            $articleShop['stock'] += $value['cant'];
            $articleShop->save();
        }
        $taxes = [];
        foreach ($data['taxes'] as $k => $v) {
            $taxes[] = $v['id'];
        }
        $inventory->taxes()->sync(Tax::find($taxes));
    }

    /**
     * @param $inventory
     * @param $articleShopId
     * @param $data
     */
    private function createInventoryArticleShop($inventory, $articleShopId, $data): void
    {
        $inventoriesArtShop = InventoriesArticlesShops::latest()
            ->where('inventory_id', '=', $inventory->id)
            ->where('articles_shops_id', '=', $articleShopId)
            ->get();
        if (count($inventoriesArtShop) === 0) {
            InventoriesArticlesShops::create([
                'inventory_id' => $inventory->id,
                'articles_shops_id' => $articleShopId,
                'cant' => $data['cant'],
                'cost' => $data['cost']
            ]);
        } else {
            $invAS = InventoriesArticlesShops::findOrFail($inventoriesArtShop[0]['id']);
            $invAS['cant'] = $data['cant'];
            $invAS['cost'] = $data['cost'];
            $invAS->save();
        }
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $inventory = Inventory::findOrFail($id);
        $this->managerBy('edit', $inventory);
        $inventory->no_facture = $data['no_facture'];
        $inventory->pay = $data['pay'];
        if (isset($data['payments']['id'])) {
            $inventory->payment_id = $data['payments']['payment_id'];
        }
        if (isset($data['supplier']['id'])) {
            $inventory->supplier_id = $data['supplier']['supplier_id'];
        }
        $inventory->save();
        $this->removeInventoryArticle($inventory, $data['articles']);
        $this->updateInventoryData($inventory, $data, true);
        return $inventory;
    }

    /**
     * @param $inventory
     * @param $articles
     */
    private function removeInventoryArticle($inventory, $articles): void
    {
        $inventoryArtShopDB = InventoriesArticlesShops::latest()
            ->where('inventory_id', '=', $inventory->id)
            ->with('articles_shops')
            ->get();
        foreach ($inventoryArtShopDB as $art => $value) {
            $exist = false;
            foreach ($articles as $k => $v) {
                if (array_key_exists('id',
                    $v) ? $v['id'] : $v['article_id'] === $value['articles_shops']['article_id']) {
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
     * @throws Exception
     */
    public function delete($id)
    {
        $inventory = Inventory::findOrFail($id);
        $this->managerBy('delete', $inventory);
        return $inventory->delete();
    }

}
