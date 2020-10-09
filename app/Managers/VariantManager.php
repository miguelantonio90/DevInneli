<?php

namespace App\Managers;

use App\Articles;
use App\Variant;
use App\Shop;
use App\User;
use App\VariantsShops;
use App\VariantsValues;

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
     * @return mixed
     */
    public static function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

    /**
     * @param $data
     * @param $idArticle
     * @return mixed
     */
    public function new($data, $articleId)
    {
        $variant = Variant::create([
            'articles_id' => $articleId,
            'name' => $data['name'],
            'value'=> json_encode($data['values'])
        ]);
        return $variant;
    }

    /**
     * @param $data
     * @param $articleId
     * @return VariantsValues
     */
    public function newVariantValue($data, $articleId):VariantsValues{
        $variantValue = VariantsValues::create([
            'articles_id' => $articleId,
            'variant' => $data['variant'],
            'cost' => $data['cost'],
            'price' => $data['price'],
            'ref' => $data['ref'],
            'baraCode' => $data['barCode']
        ]);
        return $variantValue;
    }

    /**
     * @param $data
     * @param $variantId
     * @return VariantsShops
     */
    public function newVariantShop($data, $variant):VariantsShops{
        $variantValue = VariantsShops::create([
            'articles_id'=>$variant->articles_id,
            'vv_id' => $variant->id,
            'shop_id' => $data['shop_id'],
            'stock' => $data['stock'],
            'price' => $data['price'],
            'under_inventory' => $data['under_inventory']
        ]);
        return $variantValue;
    }

    /**
     * @param $article
     * @param $data
     * @param $shops
     * @return mixed
     */
    private function updateData($article, $data, $shops)
    {
        if (isset($data['barCode'])) $article->barCode = $data['barCode'];
        if (isset($data['composite'])) $article->composite = $data['composite'];
        if (isset($data['cost'])) $article->cost = $data['cost'];
        if (isset($data['inventory'])) $article->inventory = $data['inventory'];
        if (isset($data['itbis'])) $article->itbis = $data['inventory'];
        if (isset($data['lay'])) $article->lay = $data['lay'];
        if (isset($data['price'])) $article->price = $data['price'];
        if (isset($data['ref'])) $article->ref = $data['ref'];
        if (isset($data['track_inventory'])) $article->track_inventory = $data['track_inventory'];
        if (isset($data['unit'])) $article->unit = $data['unit']==='unit';
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }
        foreach ($data['variants'] as $key=>$value){

        }
        $employShop = Shop::find($idShops);
        $article->shops()->sync($employShop);
        $article->save();
        return $article;
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
        return User::findOrFail($id)->delete();
    }

}
