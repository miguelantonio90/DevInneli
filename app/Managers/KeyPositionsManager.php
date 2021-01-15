<?php

namespace App\Managers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class KeyPositionsManager
{

    /**
     * @return Collection
     */
    public function getByCompany(): Collection
    {
        if (auth()->user()['isAdmin'] === 1) {
            $keys = DB::table('key_positions')
                ->join('companies', 'companies.id', '=', 'company_id')
                ->where('companies.faker', '<>', 1)
                ->whereNull('companies.deleted_at')
                ->whereNull('key_positions.deleted_at')
                ->where('key', '<>', 'CEO')
                ->select('key_positions.*', 'companies.country', 'companies.name')
                ->get();
        } else {
            $company = CompanyManager::getCompanyByAdmin();
            $keys = DB::table('key_positions')
                ->where('company_id', '=', $company->id)
                ->whereNull('deleted_at')
                ->where('key', '<>', 'CEO')
                ->get();
        }
        return $keys;
    }
}
