<?php

namespace App\Managers;

use App\ArticlesShops;
use Exception;
use Illuminate\Auth\AuthManager;

class ArticleShopManager extends BaseManager
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
     * @var ShopManager
     */
    private $shopManager;

    /**
     * ArticleManager constructor.
     * @param  VariantManager  $variantManager
     */
    public function __construct(VariantManager $variantManager)
    {
        $this->variantManager = $variantManager;
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
                if (isset($v['articles_shop_id']) && $v['articles_shop_id'] === $value['id'] && $v['checked']) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->variantManager->deleteArticlesShops($value->id);
            }
        }
        $this->createArticlesShops($article, $shopsArticles);
    }

    /**
     * @param $article
     * @param $shops
     * @throws Exception
     */
    public function createArticlesShops($article, $shops): void
    {
        foreach ($shops as $key => $value) {
            if ($value['checked']) {
                $artShops = ArticlesShops::latest()
                    ->where('article_id', '=', $article->id)
                    ->where('shop_id', '=', $value['shop_id'])
                    ->get();
                $artShop = count($artShops) > 0 ? $artShops[0] : ArticlesShops::create([
                    'article_id' => $article->id,
                    'shop_id' => $value['shop_id'],
                ]);
                $artShop['stock'] = $artShop['stock'] + $value['stock'] ?: 0;
                $artShop['price'] = $value['price'] ?: 0;
                $artShop['under_inventory'] = $value['under_inventory'] ?: 0;
                $this->managerBy(count($artShops) > 0 ? 'edit' : 'new', $artShop);
            }
        }
    }


}
