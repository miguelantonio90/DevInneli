<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Position;
use App\Providers\RouteServiceProvider;
use App\Shop;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @group Auth endpoints
 */
class RegisterController extends Controller
{
    protected $server;
    protected $tokens;
    protected $jwt;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * RegisterController constructor.
     * @param AuthorizationServer $server
     * @param TokenRepository $tokens
     * @param JwtParser $jwt
     */
    public function __construct(AuthorizationServer $server, TokenRepository $tokens, JwtParser $jwt)
    {
        $this->middleware('guest');
        $this->server = $server;
        $this->jwt = $jwt;
        $this->tokens = $tokens;
    }


    public function register(ServerRequestInterface $request)
    {
        $this->validator($request->getParsedBody());

        $user = $this->create($request->getParsedBody());

        $this->guard()->login($user);

        $data = $request->getParsedBody();

        $controller = new AccessTokenController($this->server, $this->tokens, $this->jwt);
        $request = $request->withParsedBody([
                'username' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation']
            ] + [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret')
            ]);
        return with($controller->issueToken($request));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'shopName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User|JsonResponse|Response
     */
    protected function create(array $data)
    {
        $company = Company::create([
            'name' => $data['shopName'],
            'email' => $data['email'],
            'country' => $data['country'],
        ]);
        if ($company) {
            $position = Position::createFirst($company);
            if ($position) {
                $user = User::createFirst($data, $company, $position);
                $shop = Shop::createFirst($data, $company);
                $user->shops()->saveMany([$shop]);
                $user->sendEmailVerificationNotification();

                return $user;
            }
        }
    }
}
