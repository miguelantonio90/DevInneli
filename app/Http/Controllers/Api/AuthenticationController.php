<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Managers\UserManager;
use App\User;

/**
 * @group Auth endpoints
 * @method redirectPath()
 */
class AuthenticationController extends Controller
{
    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return mixed
     */
    public function user()
    {
        if (auth()->user()['isAdmin'] === 0) {
            $company_id = UserManager::getCompanyByAdmin();
            return User::findOrFail(auth()->id())
                ->where('isAdmin', '=', '0')
                ->where('company_id', '=', $company_id)
                ->with('company')
                ->with('position')
                ->with('shops')
                ->first();
        } else {
            return User::findOrFail(auth()->id())->with('company')->first();
        }
    }
}
