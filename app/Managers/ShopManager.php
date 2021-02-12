<?php

namespace App\Managers;

use App\ArticlesShops;
use App\Shop;
use App\User;
use Exception;

class ShopManager extends BaseManager
{
    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public static function getShopData($data)
    {
        $shop = Shop::latest()
            ->where('name', '=', str_replace('_', ' ', $data['shopName']))
            ->with('articlesShops')
            ->first();
        $ArticlesShops = ArticlesShops::latest()->where('shop_id', '=', $shop->id)
            ->with('article')
            ->get();
        return ['shop' => $shop, 'articles' => $ArticlesShops];
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
    public function new($request)
    {
        $data = $request->all();
        $company = CompanyManager::getCompanyByAdmin();
        $data['company_id'] = $company->id;
        $user = User::findOrFail(auth()->id());
        $shop = Shop::create($data);
        if (auth()->user()['isManager'] === 1 && auth()->user()['company_id'] === $company->id) {
            $user->shops()->attach($shop);
        }
        $this->managerBy('new', $shop);
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
