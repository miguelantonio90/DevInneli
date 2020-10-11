<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Position;
use App\Providers\RouteServiceProvider;
use App\Shop;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

/**
 * @group Auth endpos
 */
class RegisterController extends Controller
{
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
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * @param  Request  $request
     * @return Application|JsonResponse|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'shopName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'country' => 'required|string',
        ]);

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
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

                return $user;
            }
        }
    }

    /**
     * @param  Request  $request
     * @param $user
     * @return JsonResponse
     */
    protected function registered(Request $request, $user)
    {
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $user->createToken(config('services.passport.client_secret'))->accessToken
        ]);
    }
}
