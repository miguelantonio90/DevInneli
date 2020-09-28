<?php


namespace App\Managers;


use App\Position;
use Illuminate\Support\Facades\DB;

class AccessManager
{

    public function getByCompany()
    {
        $company_id = $this->getCompanyByAdmin();
        return DB::table('positions')
            ->where('key', '<>', 'admin')
            ->where('company_id', '=', (integer)$company_id)
            ->latest()
            ->get();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function new($request)
    {
        $data = $request->all();
        $company_id = $this->getCompanyByAdmin();
        $data['company_id'] = (integer)$company_id;
        return Position::create($data);
    }

    private function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

}
