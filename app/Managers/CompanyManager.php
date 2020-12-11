<?php

namespace App\Managers;

use App\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CompanyManager
{
    /**
     * @param  string  $email
     * @return mixed
     */
    public static function getCompanyByEmail(string $email)
    {
        return Company::where('email', '=', $email)
            ->firstOrFail();
    }

    /**
     * @return Model|Builder|int|object
     */
    public static function getCompanyByAdmin()
    {
        return DB::table('users')
            ->select('company_id as id')
            ->where('users.id', '=', auth()->id())
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Company::findOrFail($id)->delete();
    }
}
