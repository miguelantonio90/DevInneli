<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\Position;
use Exception;

/**
 * @group Auth endpos
 * @method redirectPath()
 */
class AuthenticationController extends Controller
{
    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        parent::__construct();

        $this->userManager = $userManager;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function user()
    {
        $user = auth()->user();
        $company = Company::findOrFail($user['company_id']);
        $position = Position::findOrFail($user['position_id']);
        $user['position']->accessPin = $position['accessPin'] === 1;
        $user['position']->accessEmail = $position['accessEmail'] === 1;
        $user['position']->disabled = $position['key'] === 'super_manager';
        $user['company'] = $company;
        $user['position'] = $position;
        return ResponseHelper::sendResponse($user, '');
    }
}
