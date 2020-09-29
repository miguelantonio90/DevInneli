<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Managers\UserManager;
use App\Position;

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
        $user = auth()->user();
        $company = Company::findOrFail($user['company_id']);
        $position = Position::findOrFail($user['position_id']);
        $user['company'] = $company;
        $user['position'] = $position;

        return $user;
    }
}
