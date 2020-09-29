<?php


namespace App\Managers;


use App\Company;
use Illuminate\Support\Facades\DB;

class CompanyManager
{
    public static function getCompanyByEmail(string $email)
    {
        return Company::where('email', '=', $email)
            ->where('companies.faker', '<>', 1)
            ->firstOrFail();
    }

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
