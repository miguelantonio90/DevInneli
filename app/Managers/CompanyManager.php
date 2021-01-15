<?php

namespace App\Managers;

use App\Articles;
use App\Client;
use App\Company;
use App\Shop;
use App\Supplier;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
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
     * @return Collection
     */
    public function getAllCompanies(): Collection
    {
        $companies = DB::table('companies')
            ->where('faker', '<>', 1)
            ->get();
        foreach ($companies as $key => $company) {
            $companies[$key]->shops = Shop::latest()->where('company_id', '=', $company->id)->get();
            $companies[$key]->employers = User::latest()->where('company_id', '=', $company->id)->get();
            $companies[$key]->clients = Client::latest()->where('company_id', '=', $company->id)->get();
            $companies[$key]->suppliers = Supplier::latest()->where('company_id', '=', $company->id)->get();
            $companies[$key]->articles = Articles::latest()->where('company_id', '=', $company->id)->get();
        }
        return $companies;
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
