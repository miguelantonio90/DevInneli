<?php

namespace App\Managers;

use App\ArticleImage;
use App\Articles;
use App\ArticlesComposite;
use App\ArticlesShops;
use App\Variant;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

/**
 * @method findMoreStars(Request $request)
 */
class ArticleManager extends BaseManager
{
    /**
     * @var AuthManager
     */
    protected $authManager;

    /**
     * @var VariantManager
     */
    private $variantManager;

    /**
     * @var ArticleImageManager
     */
    private $articleImageManager;

    /**
     * ArticleManager constructor.
     * @param  VariantManager  $variantManager
     * @param  ArticleImageManager  $articleImageManager
     */
    public function __construct(VariantManager $variantManager, ArticleImageManager $articleImageManager)
    {
        $this->variantManager = $variantManager;
        $this->articleImageManager = $articleImageManager;
    }


    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $articles = Articles::latest()
                ->with('company')
                ->with('category')
                ->with('composites')
                ->with('articlesShops')
                ->with('variants')
                ->with('tax')
                ->with('variantValues')
                ->with([
                    'images' => function ($q) {
                        $q->orderBy('article_images.default', 'desc');
                    }
                ])
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $articles = Articles::latest()
                ->where('company_id', '=', $company->id)
                ->whereNull('deleted_at')
                ->with('company')
                ->with([
                    'category' => function ($q) use ($company) {
                        $q->where('categories.company_id', '=', $company->id);
                    }
                ])
                ->with('composites')
                ->with('articlesShops')
                ->with('variants')
                ->with('tax')
                ->with('variantValues')
                ->with([
                    'images' => function ($q) {
                        $q->orderBy('article_images.default', 'desc');
                    }
                ])
                ->get();
        }
        foreach ($articles as $k => $article) {
            $article['composite'] = (boolean) $article['composite'];
            $article['personSale'] = (boolean) $article['personSale'];
            $article['onlineSale'] = (boolean) $article['onlineSale'];
            foreach ($article['images'] as $im => $image) {
                if ($image['default'] === 1) {
                    $articles[$k]['path'] = $image['path'];
                    $articles[$k]['nameImage'] = $image['name'];
                }
            }
            $shopNames = [];
            foreach ($article['articlesShops'] as $sh => $shop) {
                $shopNames[$sh] = $shop['shops']['name'];
            }
            $articles[$k]['shopsNames'] = array_unique($shopNames);

            $shopVariant = [];
            foreach ($article['variantValues'] as $sh => $shopV) {
                $shopV['images'] = ArticleImage::latest()->where('article_id', '=', $shopV['id'])->get();
                foreach ($shopV['articlesShops'] as $i => $v) {
                    $shopVariant[$sh] = $v['shops']['name'];
                }
                $articles[$k]['variantValues'][$sh]['shopsNames'] = array_unique($shopVariant);
                if (round($articles[$k]['variantValues'][$sh]['price'], 2) === 0.00) {
                    $articles[$k]['variantValues'][$sh]['percent'] = 0;
                } elseif (round($articles[$k]['variantValues'][$sh]['cost'], 2) === 0.00) {
                    $articles[$k]['variantValues'][$sh]['percent'] = 100.00;
                } else {
                    $articles[$k]['variantValues'][$sh]['percent'] = round(
                        (100 * $articles[$k]['variantValues'][$sh]['cost']) / $articles[$k]['variantValues'][$sh]['price'],
                        2
                    );
                }
            }

            if ($article['price'] === 0.00) {
                $articles[$k]['percent'] = 0;
            } elseif ($article['cost'] === 0.00) {
                $articles[$k]['percent'] = 100;
            } else {
                $difference = $article['price'] - $article['cost'];
                $articles[$k]['percent'] = !$article['cost'] || $article['cost'] === 0.00 || $article['cost'] === 0 ? 100 : round(($difference / $article['cost']) * 100,
                    2);
            }
        }
        return $articles;
    }

    /**
     * @return int
     */
    public function findArticleNumber(): int
    {
        $company = CompanyManager::getCompanyByAdmin();
        $number = Articles::select('ref')
            ->where('company_id', '=', $company->id)
            ->latest()
            ->orderBy('ref', 'DESC')
            ->first();

        return ($number && count($number->toArray()) > 0) ? (int) $number['ref'] : 1000;
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public function new($data): Articles
    {
        $shops = $data['articles_shops'];
        $taxes = $data['tax'];
        $article = $this->insertArticle($data);
        if ($data['composite'] === true) {
            $this->updateComposite($article, $data);
        } else {
            $this->updateData($article, $data);
            if (count($data['variants']) !== 0) {
                foreach ($data['variants'] as $key => $value) {
                    $this->variantManager->newVariant($value, $article->id);
                }
                foreach ($data['variant_values'] as $key => $value) {
                    $value['company_id'] = $data['company_id'];
                    $articleChildren = $this->insertArticle($value);
                    $this->updateData($articleChildren, $value);
                    $articleChildren->parent_id = $article->id;
                    $articleChildren->category_id = $article->category_id;
                    $this->updateTaxes($article, $taxes);
                    $articleChildren->save();
                    $arrayShops = $this->getShopsByVariantValue($shops, $articleChildren);
                    foreach ($arrayShops as $k => $v) {
                        $this->variantManager->newArticleShop($v, $articleChildren);
                    }
                }
            }
        }

        foreach ($shops as $key => $value) {
            if ($value['personSale'] || $value['onlineSale']) {
                $artShop = ArticlesShops::create([
                    'article_id' => $article->id,
                    'shop_id' => $value['shop_id'],
                    'stock' => $value['stock'] ?: 0,
                    'price' => $value['price'],
                    'onlinePrice' => $value['onlinePrice'],
                    'under_inventory' => $value['under_inventory'] ?: 0
                ]);
                $artShop->personSale = $value['personSale'];
                $artShop->onlineSale = $value['onlineSale'];
                $artShop->save();
                $this->managerBy('new', $artShop);
            }
        }
        $article->save();
        if (count($data['images']) > 0) {
            $this->articleImageManager->new($article->id, $data['images']);
        }
        $this->updateTaxes($article, $taxes);
        return $article;
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
        if (isset($data['onlinePrice'])) {
            $article->onlinePrice = $data['onlinePrice'];
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
        foreach ($data['articles_shops'] as $key => $value) {
            $artShop = ArticlesShops::create([
                'article_id' => $article->id,
                'shop_id' => $value['shop_id'],
                'stock' => $value['stock'] ?: 0,
                'price' => $value['price'],
                'onlinePrice' => $value['onlinePrice'],
                'under_inventory' => $value['under_inventory'] ?: 0
            ]);
            $artShop->personSale = $value['personSale'];
            $artShop->onlineSale = $value['onlineSale'];
            $artShop->save();
            $this->managerBy('new', $artShop);
        }
        foreach ($data['composites'] as $key => $value) {
            if (!isset($value['id'])) {
                ArticlesComposite::create([
                    'article_id' => $article->id,
                    'composite_id' => $value['composite_id'],
                    'cant' => $value['cant'],
                    'price' => $value['price'],
                    'onlinePrice' => $value['onlinePrice'],
                ]);
            } else {
                $article_c = ArticlesComposite::findOrFail($value['id']);
                $article_c['cant'] = $value['cant'];
                $article_c['price'] = $value['price'];
                $article_c['onlinePrice'] = $value['onlinePrice'];
                $article_c->save();
            }
        }

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
        if (isset($data['um'])) {
            $article->um = json_encode($data['um']);
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
            $this->updateArticleImage($article, $data['images']);
            $this->updateChidrensArticles($article, $data);

        }
        $this->managerBy('edit', $article);
        $this->updateTaxes($article, $taxes);
        $this->updateData($article, $data);
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
     * @param $variants
     * @throws Exception
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
     * @param $images
     * @throws Exception
     */
    public function updateArticleImage($article, $images): void
    {
        $variantDB = ArticleImage::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($variantDB as $key => $value) {
            $exist = false;
            foreach ($images as $k => $v) {
                if (isset($v['id']) && $v['id'] === $value['id']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->articleImageManager->delete($value->id);
            }
        }
        foreach ($images as $k => $v) {
            if (!array_key_exists('id', $v)) {
                $this->articleImageManager->new($article->id, [$v]);
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
        $shops = $data['articles_shops'];
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
            $arrayShops = $this->getShopsByVariantValue($shops, $articleChildren);
            foreach ($arrayShops as $l => $m) {
                if ($m['articles_shop_id'] === null || $m['articles_shop_id'] === "") {
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
     * @param $article
     * @param $shopsArticles
     * @throws Exception
     */
    public function updateArticlesShops($article, $shopsArticles): void
    {
        $variantDB = ArticlesShops::latest()
            ->where('article_id', '=', $article->id)
            ->get();
        foreach ($variantDB as $key => $value) {
            $exist = false;
            foreach ($shopsArticles as $k => $v) {
                if (isset($v['articles_shop_id']) && $v['articles_shop_id'] === $value['id'] && $v['articles_shop_id'] !== '' && ($v['personSale'] || $v['onlineSale'])) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteArticlesShops($value->id);
            }
        }
    }
}
