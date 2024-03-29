<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\Providers\RouteServiceProvider;
use App\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use function cache as cacheAlias;

/**
 * @group Auth endpoints
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest')->except('logout');
        /*$this->middleware('guest:users')->except('logout');
        $this->middleware('guest:clients')->except('logout');*/
    }

    /**
     * @param  Request  $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|mixed|\Symfony\Component\HttpFoundation\Response|void
     * @throws ValidationException
     * @throws Exception
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests o this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    protected function sendLoginResponse(Request $request): JsonResponse
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
        cacheAlias()->put('userPin', User::latest()->where('id', $request->user()['id'])->get()[0]);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $request->user()->createToken(config('services.passport.client_secret'))->accessToken,
            'user' => [
                'firstName' => $request->user()['firstName'],
                'lastName' => $request->user()['lastName'],
                'email_verified_at' => $request->user()['email_verified_at'],
            ]
        ]);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function loginPincode(Request $request)
    {
        $this->validatePin($request->all())->validate();
        $user = UserManager::loginByPincode($request->all());

        if (isset($user[0])) {
            $this->guard()->login($user[0]);
            $user[0]->save();
            $token = $user[0]->createToken(config('services.passport.client_secret'))->accessToken;
            $user[0]['access_token'] = $token;
            cacheAlias()->put('userPin', $user[0]);
            return ResponseHelper::sendResponse($user[0], 'Success login.');
        }

        return ResponseHelper::sendError('Unauthenticated', 'Unauthenticated', 403);

    }

    protected function validatePin(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'email' => 'email|required|string',
            'pincode' => 'required|string',
        ]);
    }
}
