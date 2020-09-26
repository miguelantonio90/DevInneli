<?php


namespace App\Managers;


use App\Http\Helpers\ResponseHelper;
use App\Shop;
use Illuminate\Support\Facades\DB;

class ShopManager
{
    public function findAllByCompany()
    {
        return Shop::latest()
            ->where('company_id', '=', $this->getCompanyByAdmin())
            ->get();
    }

    public function new($request)
    {
        $data = $request->all();
        $company_id = $this->getCompanyByAdmin();
        $company = DB::table('companies')
            ->select('email')
            ->where('companies.id', '=', $company_id)
            ->get()[0];
        $data['email'] = $company->email;
        $data['company_id'] = $company_id;
        return Shop::create($data);
    }

    public function edit($id, $request)
    {
        return Shop::findOrFail($id)->update($request->all());
    }

    public function delete()
    {
        $shops = Shop::latest()
            ->where('company_id', '=', $this->getCompanyByAdmin())
            ->get();
        if (count($shops) > 1) {
            $delete = Shop::findOrFail($id)->delete();
            return [$delete,'Shop has deleted successfully.'];
        }
        return [false,"Shop can't by deleted"];

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
}
