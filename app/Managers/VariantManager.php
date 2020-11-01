<?php

namespace App\Managers;

use App\Articles;
use App\ArticlesShop;
use App\Variant;

class VariantManager
{

    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $articles = Articles::latest()
                ->with('shops')
                ->with('categories')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $articles = Articles::latest()
                ->where('company_id', '=', $company->id)
                ->with('company')
                ->with([
                    'category' => function ($q) use ($company) {
                        $q->where('categories.company_id', '=', $company->id);
                    }
                ])
                ->with('variants')
                ->with('composites')
                ->with('shops')
                ->get();
        }
        return $articles;
    }

    /**
     * @param $data
     * @param $articleId
     * @return Variant
     */
    public function newVariant($data, $articleId):Variant
    {
        return Variant::create([
            'articles_id' => $articleId,
            'name' => $data['name'],
            'value' => json_encode($data['value'])
        ]);
    }

    /**
     * @param $data
     * @return Variant
     */
    public function editVariant($data): Variant
    {
        $variant = Variant::findOrFail($data['id']);
        $variant['name'] = $data['name'];
        $variant['value'] = json_encode($data['value']);
        $variant->save();
        return $variant;
    }

    /**
     * @param $data
     * @param $article
     * @return ArticlesShop
     */
    public function newArticleShop($data, $article): ArticlesShop
    {
        return ArticlesShop::create([
            'articles_id' => $article->id,
            'shops_id' => $data['shop_id'],
            'stock' => $data['stock'],
            'price' => $data['price'],
            'under_inventory' => $data['under_inventory']
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @return ArticlesShop
     */
    public function updateArticleShop($id, $data): ArticlesShop
    {
        $article_shop = ArticlesShop::findOrFail($id);
        $article_shop['shops_id'] = $data['shop_id'];
        $article_shop['stock'] = $data['stock'];
        $article_shop['price'] = $data['price'];
        $article_shop['under_inventory'] = $data['under_inventory'];
        $article_shop->save();
        return $article_shop;

    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteShop($id):bool
    {
        return ArticlesShop::findOrFail($id)->delete();

    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteVariant($id): bool
    {
        return Variant::findOrFail($id)->delete();
    }

}
