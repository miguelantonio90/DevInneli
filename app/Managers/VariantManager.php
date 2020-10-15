<?php

namespace App\Managers;

use App\Articles;
use App\Shop;
use App\User;
use App\Variant;
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
     * @param $articleId
     * @return mixed
     */
    public function newVariant($data, $articleId)
    {
        return Variant::create([
            'articles_id' => $articleId,
            'name' => $data['name'],
            'value' => json_encode($data['value'])
        ]);
    }

    /**
     * @param $data
     * @param $articleId
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
     * @param $articleId
     * @return VariantsValues
     */
    public function newVariantValue($data, $articleId): VariantsValues
    {
        return VariantsValues::create([
            'articles_id' => $articleId,
            'variant' => $data['variant'],
            'cost' => $data['cost'],
            'price' => $data['price'],
            'ref' => $data['ref'],
            'barCode' => $data['barCode']
        ]);
    }

    /**
     * @param $data
     * @param $variant
     * @return VariantsShops
     */
    public function newVariantShop($data, $variant): VariantsShops
    {
        return VariantsShops::create([
            'articles_id' => $variant->articles_id,
            'vv_id' => $variant->id,
            'shop_id' => $data['shop_id'],
            'stock' => $data['stock'],
            'price' => $data['price'],
            'under_inventory' => $data['under_inventory']
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function editVariantValue($id, $data)
    {
        $variant = VariantsValues::findOrFail($id);
        if (isset($data['variant']))
            $variant->variant = $data['variant'];
        if (isset($data['cost']))
            $variant->cost = $data['cost'];
        if (isset($data['price']))
            $variant->price = $data['price'];
        if (isset($data['ref']))
            $variant->ref = $data['ref'];
        if (isset($data['barCode']))
            $variant->ref = $data['barCode'];
        $variant->save();
        return $variant;

    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteVariant($id):bool
    {
        return Variant::findOrFail($id)->delete();
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteVariantValue($id):bool
    {
        return VariantsValues::findOrFail($id)->delete();
    }

    /**
     * @param $el
     * @param $articleId
     */
    public function removeAll($el, $articleId):void
    {
        $variant = $el === 'vv' ? VariantsValues::latest()
            ->where('articles_id', '=', $articleId)
            ->get() : Variant::latest()
            ->where('articles_id', '=', $articleId)
            ->get();
        foreach ($variant as $key => $value) {
            $value->delete();
        }
    }

}
