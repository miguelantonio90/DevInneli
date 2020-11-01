<?php

namespace App\Managers;

use App\Articles;
use App\ArticlesComposite;
use App\ArticlesShops;
use App\Variant;

class ArticleManager
{

    /**
     * @var VariantManager
     */
    private $variantManager;

    /**
     * ArticleManager constructor.
     * @param VariantManager $variantManager
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
                ->with('articlesShops')
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
                ->with('articlesShops')
                ->with('variants')
                ->with('variantValues')
                ->with([
                    'images' => function ($q) {
                        $q->orderBy('article_images.default', 'desc');
                    }
                ])
                ->get();
        }
        foreach ($articles as $k => $article) {
            foreach ($article['images'] as $im => $image) {
                if ($image['default'] === 1) {
                    $articles[$k]['path'] = $image['path'];
                    $articles[$k]['nameImage'] = $image['name'];
                }
            }
            $shopNames = [];
            foreach ($article['articlesShops'] as $sh => $shop) {
                $shopNames[$sh] = $shop['shopName'];
            }
            $articles[$k]['shopsNames'] = array_unique($shopNames);
            if (isset($article['price'])) {
                $articles[$k]['percent'] = 0;
            } else {
                $articles[$k]['percent'] = $article['price'] !== '0.00' && $article['price'] !== '0' ? round((100 * $article['cost']) / $article['price'], 2) : 0;
            }
        }
        return $articles;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $shops = $data['shops'];
        $article = $this->insertArticle($data);
        if ($data['composite'] === true) {
            $this->updateComposite($article, $data);
        } else {
            $this->updateData($article, $data);
            if (count($data['variants']) !== 0) {
                foreach ($data['variants'] as $key => $value) {
                    $this->variantManager->newVariant($value, $article->id);
                }
                foreach ($data['variantsValues'] as $key => $value) {
                    $value['company_id'] = $data['company_id'];
                    $articleChildren = $this->insertArticle($value);
                    $this->updateData($articleChildren, $value);
                    $articleChildren->parent_id = $article->id;
                    $articleChildren->save();
                    $arrayShops = $this->getShopsByVariant($shops, $articleChildren);
                    foreach ($arrayShops as $k => $v) {
                        $this->variantManager->newArticleShop($v, $articleChildren);
                    }
                }
            } else {
                foreach ($shops as $key => $value) {
                    $this->variantManager->newArticleShop($value, $article);
                }
            }
            $article->save();
            return $article;
        }

        if (count($data['images']) > 0) {
            ArticleImageManager::new($article->id, $data['images']);
        }

        return $article;
    }

    /**
     * @param $data
     * @return Articles
     */
    public function insertArticle($data): Articles
    {
        return Articles::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);
    }

    /**
     * @param $article
     * @param $data
     * @return mixed
     */
    private function updateData($article, $data)
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
        $article->save();

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
                $newArt= ArticlesComposite::create([
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
     * @param $data
     * @param $variantValue
     * @return array
     */
    private function getShopsByVariant($data, $variantValue): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($value['name'] === $variantValue->name) {
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
            $this->updateVariants($article, $data['variants']);
            $this->updateChidrensArticles($article, $data);

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
            ->where('article_id', '=', $article->id)
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
    public function updateVariants($article, $variants): void
    {
        $variantDB = Variant::latest()
            ->where('parent_id', '=', $article->id)
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
            if ($value['id'] != "") {
                $this->variantManager->editVariant($v);
            } else {
                $this->variantManager->newVariant($v, $article->id);
            }
        }
    }

    /**
     * @param $article
     * @param $variantValues
     */
    public function updateChidrensArticles($article, $data): void
    {
        $shops = $data['shops'];
        $variantValues = $data['variant_values'];
        $variantsValue = Articles::latest()
            ->where('parent_id', '=', $article->id)
            ->get();
        foreach ($variantsValue as $key => $value) {
            $exist = false;
            foreach ($variantValues as $k => $v) {
                if (isset($v['id'])) {
                    if ($v['id'] === $value['id']) {
                        $exist = true;
                    }
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
            $this->updateShops($articleChildren, $data);
            $arrayShops = $this->getShopsByVariant($shops, $articleChildren);
            foreach ($arrayShops as $l => $m) {
                if ($m['articles_shop_id'] == "") {
                    $this->variantManager->newArticleShop($m, $articleChildren);
                } else {
                    $this->variantManager->updateArticleShop($m['articles_shop_id'], $m);
                }
            }
        }

    }

    /**
     * @param $article
     * @param $shopsArticles
     */
    public function updateShops($article, $shopsArticles): void
    {
        $variantDB = ArticlesShops::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($variantDB as $key => $value) {
            $exist = false;
            foreach ($shopsArticles as $k => $v) {
                if (isset($v['articles_shop_id']) && $v['id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteShop($value->id);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $childrens = Articles::latest()
            ->where('parent_id', '=', $id)
            ->get();
        if (count($childrens) > 0)
            foreach ($childrens as $key => $value)
                $value->delete();
        return Articles::findOrFail($id)->delete();
    }

}
