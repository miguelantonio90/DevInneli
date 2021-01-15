<?php

namespace App\Managers;

use App\Articles;
use App\ArticlesComposite;
use App\ArticlesShops;
use App\Refund;
use App\Variant;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * @method refound(array $all)
 */
class RefoundManager extends BaseManager
{


    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        return DB::table('refunds')
            ->join('sales', 'sales.id', '=', 'refunds.sale_id')
            ->join('articles', 'articles.id', '=', 'refunds.article_id')
            ->join('users', 'users.id', '=', 'refunds.created_by')
            ->select('refunds.*', 'sales.no_facture as facture', 'articles.name', 'users.firstName as created_by')
            ->get();

    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $refunds = Refund::create([
            'company_id' => (CompanyManager::getCompanyByAdmin())->id,
            'cant' => $data['cant'],
            'money' => $data['money'],
            'sale_id' => $data['sale']['id'],
            'article_id' => $data['article']['article_id']
        ]);
        $refunds['box_id'] = $data['box']['id'];
        $refunds->save();
        $this->managerBy('new', $refunds);
        $article_shop = ArticlesShops::latest()
            ->where('article_id', '=', $data['article']['id'])
            ->where('shop_id', '=', $data['sale']['shop']['shop_id'])
            ->get()[0];
        $article_shop->stock += $data['cant'];
        $this->managerBy('update', $article_shop);
        return $refunds;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $data)
    {
        $article = Articles::findOrFail($id);
        $article->name = $data['name'];
        $article->save();
        $taxes = $data['tax'];
        if ($data['composite']) {
            $this->removeComposite($article, $data['composites']);
            $this->updateComposite($article, $data);
        } else {
            $this->updateVariants($article, $data['variants']);
            $this->updateChidrensArticles($article, $data);

        }
        $this->managerBy('edit', $article);
        $this->updateTaxes($article, $taxes);
        return $article;
    }

    /**
     * @param $article
     * @param $composites
     * @throws Exception
     */
    public function removeComposite($article, $composites): void
    {
        $articleComposite = ArticlesComposite::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($articleComposite as $key => $value) {
            $exist = false;
            foreach ($composites as $k => $v) {
                if (isset($v['id']) && $v['id'] === $value->id) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->managerBy('delete', $value);
                $value->delete();
            }

        }
    }

    /**
     * @param $article
     * @param $data
     * @throws Exception
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
        foreach ($data['shops'] as $key => $value) {
            $artShop = ArticlesShops::create([
                'article_id' => $article->id,
                'shop_id' => $value['shop_id'],
                'stock' => $value['stock'] ?: 0,
                'price' => $value['price'],
                'under_inventory' => $value['under_inventory'] ?: 0
            ]);
            $this->managerBy('new', $artShop);
        }
        foreach ($data['composites'] as $key => $value) {
            if (!isset($value['id'])) {
                ArticlesComposite::create([
                    'article_id' => $article->id,
                    'composite_id' => $value['composite_id'],
                    'cant' => $value['cant'],
                    'price' => $value['price'],
                ]);
            } else {
                $article_c = ArticlesComposite::findOrFail($value['id']);
                $article_c['cant'] = $value['cant'];
                $article_c['price'] = $value['price'];
                $article_c->save();
            }
        }

    }

    /**
     * @param $article
     * @param $variants
     */
    public function updateVariants($article, $variants): void
    {
        $variantDB = Variant::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($variantDB as $key => $value) {
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
        foreach ($variants as $k => $v) {
            if (array_key_exists('id', $v)) {
                $this->variantManager->editVariant($v);
            } else {
                $this->variantManager->newVariant($v, $article->id);
            }
        }
    }

    /**
     * @param $article
     * @param $data
     * @throws Exception
     */
    public function updateChidrensArticles($article, $data): void
    {
        $shops = $data['shops'];
        $taxes = $data['tax'];
        $variantValues = $data['variant_values'];
        $variantsValue = Articles::latest()
            ->where('parent_id', '=', $article->id)
            ->get();
        foreach ($variantsValue as $key => $value) {
            $exist = false;
            foreach ($variantValues as $k => $v) {
                if (isset($v['id']) && $v['id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->delete($value['id']);
            }
        }
        foreach ($variantValues as $k => $v) {
            if (!isset($v['id'])) {
                $v['company_id'] = $data['company_id'];
                $articleChildren = $this->insertArticle($v);
                $articleChildren->parent_id = $article->id;
                $articleChildren->save();
            } else {
                $articleChildren = Articles::findOrFail($v['id']);
            }
            $articleChildren->name = $v['name'];
            $articleChildren->save();
            $this->updateData($articleChildren, $v);
            $this->updateTaxes($articleChildren, $taxes);
            $this->updateArticlesShops($articleChildren, $shops);
            $this->updateArticlesShops($articleChildren, $shops);
            $arrayShops = $this->getShopsByVariantValue($shops, $articleChildren);
            foreach ($arrayShops as $l => $m) {
                if ($m['articles_shop_id'] === "") {
                    $this->variantManager->newArticleShop($m, $articleChildren);
                } else {
                    $this->variantManager->updateArticleShop($m['articles_shop_id'], $m);
                }
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
        $childrens = Articles::latest()
            ->where('parent_id', '=', $id)
            ->get();
        if (count($childrens) > 0) {
            foreach ($childrens as $key => $value) {
                $this->managerBy('delete', $value);
                $value->delete();
            }
        }
        $article = Articles::findOrFail($id);
        $this->managerBy('delete', $article);
        return $article->delete();
    }

    /**
     * @param $data
     * @return Articles
     * @throws Exception
     */
    public function insertArticle($data): Articles
    {
        $article = Articles::create([
            'company_id' => $data['company_id'],
            'name' => $data['name']
        ]);
        $this->managerBy('new', $article);
        $article->save();
        return $article;
    }

    /**
     * @param $article
     * @param $data
     * @return mixed
     * @throws Exception
     */
    private function updateData($article, $data): void
    {
        if (isset($data['barCode'])) {
            $article->barCode = $data['barCode'];
        }
        if (isset($data['composite'])) {
            $article->composite = false;
        }
        if (isset($data['cost'])) {
            $article->cost = $data['cost'];
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
        $this->managerBy('edit', $article);
        $article->save();
    }

    /**
     * @param  Articles  $article
     * @param $taxes
     */
    public function updateTaxes(Articles $article, $taxes): void
    {
        $idTaxes = [];
        foreach ($taxes as $key => $tax) {
            $idTaxes[] = $tax['id'];
        }
        $article->tax()->sync($idTaxes);
        $article->save();
    }

    /**
     * @param $article
     * @param $shopsArticles
     */
    public function updateArticlesShops($article, $shopsArticles): void
    {
        $variantDB = ArticlesShops::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($variantDB as $key => $value) {
            $exist = false;
            foreach ($shopsArticles as $k => $v) {
                if (isset($v['articles_shop_id']) && $v['articles_shop_id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteArticlesShops($value->id);
            }
        }
    }

    /**
     * @param $data
     * @param $variantValue
     * @return array
     */
    private function getShopsByVariantValue($data, $variantValue): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['name'] === $variantValue->name) {
                $result[] = $value;
            }
        }
        return $result;
    }


}
