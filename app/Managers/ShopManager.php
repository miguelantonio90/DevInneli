<?php

namespace App\Managers;

use App\Shop;
use App\User;
use Exception;

class ShopManager extends BaseManager
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
