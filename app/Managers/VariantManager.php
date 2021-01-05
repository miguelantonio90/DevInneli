<?php

namespace App\Managers;

use App\ArticlesShops;
use App\Variant;
use Exception;

class VariantManager extends BaseManager
{

    /**
     * @param $data
     * @param $articleId
     * @return Variant
     * @throws Exception
     */
    public function newVariant($data, $articleId): Variant
    {
        $variant = Variant::create([
            'article_id' => $articleId,
            'name' => $data['name'],
            'value' => json_encode($data['value'])
        ]);
        $this->managerBy('new', $variant);
        return $variant;
    }

    /**
     * @param $data
     * @return Variant
     * @throws Exception
     */
    public function editVariant($data): Variant
    {
        $variant = Variant::findOrFail($data['id']);
        $variant['name'] = $data['name'];
        $variant['value'] = json_encode($data['value']);
        $variant->save();
        $this->managerBy('edit', $variant);
        return $variant;
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteVariant($id): bool
    {
        $variant = Variant::findOrFail($id);
        $this->managerBy('delete', $variant);
        return $variant->delete();
    }

    /**
     * @param $data
     * @param $article
     * @return ArticlesShops
     * @throws Exception
     */
    public function newArticleShop($data, $article): ArticlesShops
    {
        $artShop = ArticlesShops::create([
            'article_id' => $article->id,
            'shop_id' => $data['shop_id'],
            'stock' => $data['stock'] ?: 0,
            'price' => $data['price'],
            'under_inventory' => $data['under_inventory'] ?: 0
        ]);
        $this->managerBy('new', $artShop);
        return $artShop;
    }

    /**
     * @param $id
     * @param $data
     * @return ArticlesShops
     * @throws Exception
     */
    public function updateArticleShop($id, $data): ArticlesShops
    {
        $article_shop = ArticlesShops::findOrFail($id);
        $article_shop['shop_id'] = $data['shop_id'];
        $article_shop['stock'] = $data['stock'] ?: 0;
        $article_shop['price'] = $data['price'];
        $article_shop['under_inventory'] = $data['under_inventory'] ?: 0;
        $article_shop->save();
        $this->managerBy('edit', $article_shop);
        return $article_shop;

    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function deleteArticlesShops($id): bool
    {
        $article_shop = ArticlesShops::findOrFail($id);
        $this->managerBy('delete', $article_shop);
        return $article_shop->delete();

    }


}
