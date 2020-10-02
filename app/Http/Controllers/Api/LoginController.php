<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

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

    protected $server;
    protected $tokens;
    protected $jwt;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(AuthorizationServer $server, TokenRepository $tokens, JwtParser $jwt)
    {
        $this->middleware('guest')->except('logout');
        $this->server = $server;
        $this->jwt = $jwt;
        $this->tokens = $tokens;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws ValidationException
     */
    /*public function login(ServerRequestInterface $request)
    {
        $controller = new AccessTokenController($this->server, $this->tokens, $this->jwt);

        $request = $request->withParsedBody($request->getParsedBody() + [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret')
            ]);
        if ($controller->issueToken($request)) {
            return with($controller->issueToken($request));
        }
        return $this->sendFailedLoginResponse($request);
    }*/
    public function login(Request $request)
    {
        $this->validateLogin($request)->validate();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
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

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return response()->json([
            'token' => $request->user()->createToken(config('services.passport.client_secret'))->accessToken,
        ]);
    }
    /**
     * @param ServerRequestInterface $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function loginPincode(ServerRequestInterface $request)
    {
        $this->validateLogin($request->getParsedBody());

        $user = UserManager::loginByPincode($request->getParsedBody());

        if (isset($user[0])) {
            $this->guard()->login($user[0]);

            return response()->json([
                'token_type' => 'Bearer',
                'access_token' => $user[0]->createToken(config('services.passport.client_secret'))->accessToken,
            ]);
        } else {
            return ResponseHelper::sendError('No login.', 404);
        }

    }

    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'username' => 'email|required|string',
            'password' => 'required|integer',
        ]);
    }
}
