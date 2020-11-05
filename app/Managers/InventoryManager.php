<?php


namespace App\Managers;


use App\ArticlesShops;
use App\InventoriesArticlesShops;
use App\Inventory;

class InventoryManager
{


    public function new($data)
    {
        $inventory = Inventory::create([
            'no_facture' => $data['noFacture'],
            'pay' => $data['pay'] ? $data['pay'] : null,
            'company_id' => $data['company_id'],
            'payment_id' => $data['payments']['id'] ? $data['payments']['id'] : null
        ]);
        $this->updateInventoryData($inventory, $data);

    }

    public function updateInventoryData($inventory, $data)
    {
        $articles = $data['articles'];
        foreach ($articles as $key => $value) {
            $articlesShops = ArticlesShops::latest()
                ->where('article_id', '=', $value['article_id'])
                ->where('shop_id', '=', $data['shop']['id'])
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
            $articleShop['stock'] = $articleShop['stock'] + $value['cant'];
            $articleShop->save();
        }
        $payments = [];
        foreach ($data['payments'] as $k => $v) {
            $payments[] = $v['id'];
        }
        $inventory->payments()->sync($payments);
    }

    /**
     * @param $inventory
     * @param $articleShop
     * @param $data
     */
    private function createInventoryArticleShop($inventory, $articleShopId, $data): void
    {
        InventoriesArticlesShops::created([
            'inventory_id' => $inventory->id,
            'articles_shops_id' => $articleShopId,
            'cant' => $data['cant'],
            'cost' => $data['cost'],
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Inventory::findOrFail($id)->delete();
    }

}
