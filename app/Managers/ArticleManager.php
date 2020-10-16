<?php

namespace App\Managers;

use App\Articles;
use App\ArticlesComposite;
use App\Shop;
use App\Variant;
use App\VariantsValues;

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
        if ($data['composite'] === true) {
            $this->updateComposite($article, $data);
        }
        $this->updateVariant($article, $data, $shops);
        return $article;
    }

    /**
     * @param $article
     * @param $data
     */
    public function updateComposite($article, $data): void
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
        if (isset($data['color'])) {
            $article->color = $data['color'];
        }
        if (isset($data['category']['id'])) {
            $article->category_id = $data['category']['id'];
        }
        $article->save();
        foreach ($data['composites'] as $key => $value) {
            if (!isset($value['id'])) {
                $article = ArticlesComposite::create([
                    'articles_id' => $article->id,
                    'composite_id' => $value['composite_id'],
                    'cant' => $value['cant'],
                    'price' => $value['price'],
                ]);
            } else {
                $article = ArticlesComposite::findOrFail($value['id']);
                $article['composite_id'] = $value['composite_id'];
                $article['cant'] = $value['cant'];
                $article['price'] = $value['price'];
                $article->save();
            }
        }

    }

    /**
     * @param $article
     * @param $data
     * @param $shops
     * @return mixed
     */
    private function updateVariant($article, $data, $shops)
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
        if (isset($data['color'])) {
            $article->color = $data['color'];
        }
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['shop_id'];
        }
        $employShop = Shop::find($idShops);
        foreach ($data['variants'] as $key => $value) {
            if (isset($value['id'])) {
                $this->variantManager->editVariant($value);
            } else {
                $this->variantManager->newVariant($value, $article->id);
            }
        }
        $article->shops()->sync($employShop);
        foreach ($data['variantsValues'] as $key => $value) {
            if (isset($value['id'])) {
                $variantValue = $this->variantManager->editVariantValue($value['id'], $article->id);
            } else {
                $variantValue = $this->variantManager->newVariantValue($value, $article->id);
            }
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
        $shops = $data['shops'];
        $article = Articles::findOrFail($id);
        $article->name = $data['name'];
        $article->save();
        if ($data['composite']) {
            $this->removeComposite($article, $data['composites']);
            $this->updateComposite($article, $data);
        } else {
            $this->removeVariants($article, $data['variants']);
            if (count($data['variants']) === 0) {
                $this->variantManager->removeAll('vv', $article->id);
            } else {
                $this->removeVariantsValues($article, $data['variants_values']);
            }
            $this->updateVariant($article, $data, $shops);
        }
        return $article;
    }

    /**
     * @param $article
     * @param $composites
     */
    public function removeComposite($article, $composites): void
    {
        $articleComposite = ArticlesComposite::latest()
            ->where('articles_id', '=', $article->id)
            ->get();
        foreach ($articleComposite as $key => $value) {
            $exist = false;
            foreach ($composites as $k => $v) {
                if (isset($v['id'])) {
                    if ($v['id'] === $value->id) {
                        $exist = true;
                    }
                }
            }
            if (!$exist) {
                $value->delete();
            }

        }
    }

    /**
     * @param $article
     * @param $variants
     */
    public function removeVariants($article, $variants): void
    {
        $variant = Variant::latest()
            ->where('articles_id', '=', $article->id)
            ->get();
        foreach ($variant as $key => $value) {
            $exist = false;
            foreach ($variants as $k => $v) {
                if (isset($v['id']) && $v['id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteVariant($value->id);
            }
        }
    }

    /**
     * @param $article
     * @param $variantValues
     */
    public function removeVariantsValues($article, $variantValues): void
    {
        $variantsValue = VariantsValues::latest()
            ->where('articles_id', '=', $article->id)
            ->get();
        foreach ($variantsValue as $key => $value) {
            $exist = false;
            foreach ($variantValues as $k => $v) {
                if ($v['id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteVariantValue($value['id']);

            }
        }
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
