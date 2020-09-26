<?php


namespace App\Managers;


use App\Position;
use Illuminate\Support\Facades\DB;

class AccessManager
{
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
