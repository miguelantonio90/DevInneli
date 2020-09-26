<?php


namespace App\Managers;


use App\Shop;
use App\User;
use Illuminate\Support\Facades\DB;

class ShopManager
{
    public function findAllByCompany()
    {
        return Shop::latest()
            ->where('company_id', '=', $this->getCompanyByAdmin())
            ->get();
    }

    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    private function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

    public function new($request)
    {
        $data = $request->all();
        $company_id = $this->getCompanyByAdmin();
        $company = DB::table('companies')
            ->select('email')
            ->where('companies.id', '=', $company_id)
            ->get()[0];
        $data['company_id'] = $company_id;
        $user = User::findOrFail(auth()->id());
        $shop = Shop::create($data);
        if (auth()->user()['isManager'] === 1 && auth()->user()['company_id'] === (integer)$company_id) {
            $user->shops()->attach($shop);
        }
        return $shop;
    }

    public function edit($id, $request)
    {
        return Shop::findOrFail($id)->update($request->all());
    }

    public function delete($id)
    {
        $shops = Shop::latest()
            ->where('company_id', '=', $this->getCompanyByAdmin())
            ->get();
        if (count($shops) > 1) {
            $delete = Shop::findOrFail($id)->delete();
            return [$delete, 'Shop has deleted successfully.'];
        }
        return [false, "Shop can't by deleted"];

    }
}
