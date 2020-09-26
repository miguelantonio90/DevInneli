<?php


namespace App\Managers;


use Illuminate\Support\Facades\DB;

class CompanyManager
{

    /**
     * @return string
     */
    public static function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->first();
    }
}
