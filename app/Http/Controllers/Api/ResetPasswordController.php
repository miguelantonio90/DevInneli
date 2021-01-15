<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * @param  Request  $request
     * @return JsonResponse|RedirectResponse
     */
    public function doReset(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * @param $user
     * @param $password
     */
    protected function resetPassword($user, $password): void
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * @param  Request  $request
     * @param $response
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response(['success' => true, 'message' => "Password reset successful"]);
    }

    /**
     * @param  Request  $request
     * @param $response
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response(['success' => false, 'message' => "Token Invalid"]);
    }
}
