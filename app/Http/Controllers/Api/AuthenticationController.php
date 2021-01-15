<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\Position;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function user()
    {
        $company = Company::findOrFail(auth()->user()['company_id']);
        $position = Position::findOrFail(auth()->user()['position_id']);
        auth()->user()['position']->accessPin = $position['accessPin'] === 1;
        auth()->user()['position']->accessEmail = $position['accessEmail'] === 1;
        auth()->user()['position']->disabled = $position['key'] === 'super_manager';
        auth()->user()['company'] = $company;
        auth()->user()['position'] = $position;

        return ResponseHelper::sendResponse(auth()->user(), '');
    }
}
