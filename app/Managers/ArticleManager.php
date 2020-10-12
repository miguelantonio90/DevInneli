<?php

namespace App\Managers;

use App\Articles;
use App\Shop;
use App\User;

class ArticleManager
{

    /**
     * @var VariantManager
     */
    private $variantManager;

    /**
     * ArticleManager constructor.
     * @param  VariantManager  $variantManager
     */
    public function __construct(VariantManager $variantManager)
    {
        $this->variantManager = $variantManager;
    }

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
                ->with('composites')
                ->with('shops')
                ->with('variants_values')
                ->with('variants_shops')
                ->with('variants')
                ->get();
        }
        return $articles;
    }

    public function new($data)
    {
        $shops = $data['shops'];
        $article = Articles::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
        return $this->updateData($article, $data, $shops);
    }

    /**
     * @param $article
     * @param $data
     * @param $shops
     * @return mixed
     */
    private function updateData($article, $data, $shops)
    {
        if (isset($data['barCode'])) {
            $article->barCode = $data['barCode'];
        }
        if (isset($data['composite'])) {
            $article->composite = $data['composite'];
        }
        if (isset($data['cost'])) {
            $article->cost = $data['cost'];
        }
        if (isset($data['inventory'])) {
            $article->inventory = $data['inventory'];
        }
        if (isset($data['price'])) {
            $article->price = $data['price'];
        }
        if (isset($data['ref'])) {
            $article->ref = $data['ref'];
        }
        if (isset($data['track_inventory'])) {
            $article->track_inventory = $data['track_inventory'];
        }
        if (isset($data['unit'])) {
            $article->unit = $data['unit'] === 'unit';
        }
        if (isset($data['category']['id'])) {
            $article->category_id = $data['category']['id'];
        }
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['shop_id'];
        }
        $employShop = Shop::find($idShops);
        foreach ($data['variants'] as $key => $value) {
            $this->variantManager->new($value, $article->id);
        }
        $article->shops()->sync($employShop);
        foreach ($data['variantsValues'] as $key => $value) {
            $variantValue = $this->variantManager->newVariantValue($value, $article->id);
            $arrayShops = $this->getVariants($shops, $variantValue);
            foreach ($arrayShops as $k => $v) {
                $this->variantManager->newVariantShop($v, $variantValue);
            }
        }
        $article->save();
        return $article;
    }

    /**
     * @param $data
     * @param $variantValue
     * @return array
     */
    private function getVariants($data, $variantValue): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['variant'] === $variantValue->variant) {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $category = $data['position'];
        $shops = $data['shops'];
        $article = User::findOrFail($id);
        return $this->updateData($article, $data, $shops);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Articles::findOrFail($id)->delete();
    }

}
