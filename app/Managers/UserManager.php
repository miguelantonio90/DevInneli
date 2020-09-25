<?php


namespace App\Managers;


use App\User;
use Illuminate\Support\Facades\DB;

class UserManager
{

    public function findAllByCompany()
    {
        if (auth()->user()['isAdmin'] === 1) {
            $users = User::latest()
                ->with('employer')
                ->with('shops')
                ->with('positions')
                ->get();
        } else {
            $company_id = $this->getCompanyByAdmin();
            $users = User::findOrFail(auth()->id())
                ->where('isAdmin', '=', '0')
                ->where('company_id', '=', $company_id)
                ->with('shops')
                ->get();
        }
        return $users;
    }



    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    private function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
    }

    public function new($data)
    {

    }

}
