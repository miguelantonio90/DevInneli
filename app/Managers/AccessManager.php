<?php

namespace App\Managers;

use App\Position;
use Illuminate\Support\Facades\DB;

class AccessManager
{

    /**
     * @return array
     */
    public function getByCompany(): array
    {
        if (auth()->user()['isAdmin'] === 1) {
            $positions = DB::table('positions')
                ->join('companies', 'companies.id', '=', 'company_id')
                ->where('companies.faker', '<>', 1)
                ->where('companies.deleted_at', '=', null)
                ->where('positions.deleted_at', '=', null)
                ->select('positions.*', 'companies.country', 'companies.name as company_name');
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $positions = DB::table('positions')
                ->where('company_id', '=', $company->id);
        }
        $positions = $positions->where('deleted_at', '=', null)->get();
        $pos = 0;
        $p = [];
        foreach ($positions as $k => $v) {
            if ($v->name !== 'CEO') {
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
            'name' => $data['name']
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
