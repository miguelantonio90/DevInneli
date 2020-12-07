<?php

namespace App\Managers;

use App\KeyPosition;
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
            ->where('company_id', '=', $company->id)
            ->where('company_id', '=', $company->id)
            ->get();
        foreach ($positions as $k => $v) {
            $positions[$k]->accessPin = $v->accessPin === 1;
            $positions[$k]->accessEmail = $v->accessEmail === 1;
//            $positions[$k]->disabled = $v->key === 'super_manager' ;
            $positions[$k]->disabled = KeyPosition::findOrFail($v->key_position_id)->key === 'super_manager';
        }

        return $positions;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $p = Position::create([
            'name' => $data['name'],
            'key_position_id'=>$data['key']['id']
        ]);
        $p->company_id = $company->id;
        $p->accessEmail = $data['accessEmail'];
        $p->accessPin = $data['accessPin'];
        $p->access_permit = json_encode($data['access_permit']);
        $p->save();
        return $p;
    }

}
