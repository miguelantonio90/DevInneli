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
            ->get();
        $pos = 0;
        $p = [];
        foreach ($positions as $k => $v) {
            if (KeyPosition::findOrFail($v->key_position_id)->key !== 'CEO') {
                $p[] = $v;
                $p[$pos]->accessPin = $v->accessPin === 1;
                $p[$pos]->accessEmail = $v->accessEmail === 1;
            }
        }

        return $p;
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
            'key_position_id' => $data['key']['id']
        ]);
        $p->company_id = $company->id;
        $p->accessEmail = $data['accessEmail'];
        $p->accessPin = $data['accessPin'];
        $p->access_permit = json_encode($data['access_permit']);
        $p->save();
        return $p;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $p = Position::findOrFail($id);
        $p->name = $data['name'];
        $p->accessEmail = $data['accessEmail'];
        $p->accessPin = $data['accessPin'];
        $p->description = $data['description'];
        $p->access_permit = json_encode($data['access_permit']);
        $p->save();
        return $p;
    }

}
