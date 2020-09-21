<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Position;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        //return $request->getParsedBody();
        $this->validator($request->getParsedBody())->validate();

        event(new Registered($user = $this->create($request->getParsedBody())));

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
            'firstName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'firstName' => $data['firstName'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'isAdmin' => 0,
        ]);
        $user->positions()
            ->attach(Position::where('key', 'manager')->first());

        return $user;
    }


    protected function registered(Request $request, $user)
    {

        return response()->json([
            'token' => $user->createToken('inneli_app')->accessToken,
            'user' => $request->user()
        ]);
    }

    protected function createNewToken(ServerRequestInterface $request)
    {
        $controller = new AccessTokenController($this->server, $this->tokens, $this->jwt);
        $request = $request->withParsedBody($request->getParsedBody() + [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret')
            ]);
        return with($controller->issueToken($request));
    }
}
