<?php

namespace App\Managers;

use App\ArticleImage;
use App\Box;
use App\Category;
use App\Company;
use App\OnlineConfig;
use App\Shop;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ShopManager extends BaseManager
{

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public static function getShopData($data)
    {
        $company = Company::latest()
            ->where('name', '=', str_replace('_', ' ', $data['compName']))
            ->first();
        $shop = Shop::latest()
            ->where('name', '=', str_replace('_', ' ', $data['shopName']))
            ->where('company_id', '=', $company->id)
            ->with('articlesShops')
            ->with('company')
            ->first();
        $articlesShops = DB::table('articles')
            ->join('articles_shops', 'articles_shops.article_id', '=', 'articles.id')
            ->join('shops', 'shops.id', '=', 'articles_shops.shop_id')
            ->where('shops.id', '=', $shop->id)
            ->where('articles_shops.onlineSale', '=', true)
            ->select('articles.*', 'articles_shops.start as start')
            ->get();
        $categories = [];
        foreach ($articlesShops as $key => $articlesShop) {
            $articlesShop->images = ArticleImage::latest()->where('article_id', '=', $articlesShop->id)->get();
            if ($articlesShop->category_id && !array_key_exists($articlesShop->category_id, $categories)) {
                $categories[$articlesShop->category_id] = Category::findOrFail($articlesShop->category_id);
            }
        }
        return [
            'shop' => $shop, 'categories' => $categories, 'articles' => $articlesShops,
            'images' => ArticleImage::latest()->where('shop_id', '=', $shop->id)->get()
        ];
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public static function getShopNoConfig()
    {
        $company = CompanyManager::getCompanyByAdmin();
        $created_by = cache()->get('userPin')['id'];
        $shops = Shop::where('company_id', '=', $company->id)
            ->with([
                'users' => function ($q) use ($created_by) {
                    $q->where('users.id', '=', $created_by);
                }
            ])
            ->get();
        $result = [];
        foreach ($shops as $key => $shop) {
            if (count(OnlineConfig::latest()->where('shop_id', '=', $shop->id)->get()) === 0) {
                $result[] = $shop;
            }
        }
        return $result;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $shops = Shop::latest()
                ->with('company')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $created_by = cache()->get('userPin')['id'];

            $shops = Shop::where('company_id', '=', $company->id)
                ->with([
                    'users' => function ($q) use ($created_by) {
                        $q->where('users.id', '=', $created_by);
                    }
                ])
                ->get();
        }
        return $shops;
    }

    /**
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $data['company_id'] = $company->id;
        $user = User::findOrFail(auth()->id());
        $shop = Shop::create($data);
        if (auth()->user()['isManager'] === 1 && auth()->user()['company_id'] === $company->id) {
            $user->shops()->attach($shop);
        }
        $box = Box::createFirst($shop, $company);
        $this->managerBy('new', $shop);
        $this->managerBy('new', $box);
        return $shop;
    }

    /**
     * @param $id
     * @param $request
     * @return mixed
     * @throws Exception
     */
    public function edit($id, $request)
    {
        $shop = Shop::findOrFail($id);
        $this->managerBy('edit', $shop);
        return $shop->update($request->all());
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    public function delete($id): array
    {
        $shops = Shop::latest()
            ->where('company_id', '=', (CompanyManager::getCompanyByAdmin())->id)
            ->get();
        if (count($shops) > 1) {
            $shop = Shop::findOrFail($id);
            $this->managerBy('delete', $shop);
            $delete = $shop->delete();
            return [true, $delete, 'Shop has deleted successfully.'];
        }
        return [false, null, "Shop can't by deleted"];
    }
}
