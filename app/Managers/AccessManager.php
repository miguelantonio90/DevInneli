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
        $positions = DB::table('positions')
            ->where('key', '<>', 'admin')
            ->where('company_id', '=', $company->id)
            ->get();
        foreach ($positions as $k => $v) {
            $positions[$k]->accessPin = $v->accessPin === 1;
            $positions[$k]->accessEmail = $v->accessEmail === 1;
            $positions[$k]->disabled = $v->key === 'super_manager';
        }

        return $positions;
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
