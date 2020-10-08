<?php

namespace App\Managers;

use App\Position;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccessManager
{

    /**
     * @return Collection
     */
    public function getByCompany()
    {
        $company = CompanyManager::getCompanyByAdmin();
        return DB::table('positions')
            ->where('key', '<>', 'admin')
            ->where('company_id', '=', $company->id)
            ->orderBy('key', 'ASC')
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

        return Position::create($data);
    }

}
