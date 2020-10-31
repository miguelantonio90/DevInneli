<?php

namespace App\Managers;

use App\Shops;
use App\User;

class ShopManager
{
    /**
     * @return mixed
     */
    public function findAllByCompany()
    {
        return Shops::latest()
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
        $shop = Shops::create($data);
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
        return Shops::findOrFail($id)->update($request->all());
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $shops = Shops::latest()
            ->where('company_id', '=', (CompanyManager::getCompanyByAdmin())->id)
            ->get();
        if (count($shops) > 1) {
            $delete = Shops::findOrFail($id)->delete();
            return [true, $delete, 'Shops has deleted successfully.'];
        }
        return [false, null, "Shops can't by deleted"];

    }
}
