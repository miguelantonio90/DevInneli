<?php

namespace App\Managers;

use App\Shop;
use App\User;

class ShopManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        return Shop::latest()
            ->where('company_id', '=', (CompanyManager::getCompanyByAdmin())->id)
            ->get();
    }

    /**
     * @param $request
     * @return mixed
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
        return $shop;
    }

    /**
     * @param $id
     * @param $request
     * @return mixed
     */
    public function edit($id, $request)
    {
        return Shop::findOrFail($id)->update($request->all());
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $shops = Shop::latest()
            ->where('company_id', '=', (CompanyManager::getCompanyByAdmin())->id)
            ->get();
        if (count($shops) > 1) {
            $delete = Shop::findOrFail($id)->delete();
            return [$delete, 'Shop has deleted successfully.'];
        }
        return [false, "Shop can't by deleted"];

    }
}
