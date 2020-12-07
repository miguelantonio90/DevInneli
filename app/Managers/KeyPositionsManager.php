<?php

namespace App\Managers;

use App\KeyPosition;
use App\Position;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KeyPositionsManager
{

    /**
     * @return Collection
     */
    public function getByCompany()
    {
        $company = CompanyManager::getCompanyByAdmin();
        return DB::table('key_positions')
            ->where('company_id', '=', $company->id)
            ->where('deleted_at', '=', null)
            ->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function new($data)
    {
        $company = CompanyManager::getCompanyByAdmin();
        $key= KeyPosition::create([
            'key'=>$data['key'],
            'access_permit'=>json_encode($data['access_permit'])
        ]);
        $key->company_id = $company->id;
        $key->save();
    }

    /***
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $key = KeyPosition::findOrFail($id);
        $key->key = $data['key'];
        $key->access_permit = json_encode($data['access_permit']);
        $key->save();
        return $key;
    }

    public function delete($id)
    {
        return KeyPosition::findOrFail($id)->delete();
    }

}
