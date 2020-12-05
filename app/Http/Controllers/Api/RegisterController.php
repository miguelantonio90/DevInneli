<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\KeyPosition;
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
 * @group Auth endpoints
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
        parent::__construct();

        $this->middleware('guest');
    }


    /**
     * @param Request $request
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
            'country' => 'required',
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
     * @param array $data
     * @return User|JsonResponse|Response
     */
    protected function create(array $data)
    {
        $company = Company::create([
            'name' => $data['shopName'],
            'email' => $data['email'],
            'country' => $data['country']['id'],
            'currency' => $data['country']['currency']
        ]);
        if ($company) {
            $key = KeyPosition::create([
                'key' => 'super_manager',
                'company_id' => $company->id,
                'access_permit' => json_encode(['false' => true,
                    'article_list' => true, 'article_add' => true, 'article_edit' => true, 'article_delete' => true, 'article_transport' => true,
                    'vending_list' => true, 'vending_add' => true, 'vending_edit' => true, 'vending_delete' => true,
                    'category_list' => true, 'category_add' => true, 'category_edit' => true, 'category_delete' => true,
                    'mod_list' => true, 'mod_add' => true, 'mod_edit' => true, 'mod_delete' => true,
                    'supplier_list' => true, 'supplier_add' => true, 'supplier_edit' => true, 'supplier_delete' => true,
                    'buy_list' => true, 'buy_add' => true, 'buy_edit' => true, 'buy_delete' => true,
                    'sell_by_product' => true, 'sell_by_category' => true, 'sell_by_employer' => true, 'sell_by_payments' => true,
                    'employer_list' => true, 'employer_add' => true, 'employer_edit' => true, 'employer_delete' => true,
                    'assistance_list' => true, 'assistance_add' => true, 'assistance_edit' => true, 'assistance_delete' => true,
                    'client_list' => true, 'client_add' => true, 'client_edit' => true, 'client_delete' => true,
                    'config' => true,
                    'shop_list' => true, 'shop_add' => true, 'shop_edit' => true, 'shop_delete' => true,
                    'access_list' => true, 'access_add' => true, 'access_edit' => true, 'access_delete' => true,
                    'payment_list' => true, 'payment_add' => true, 'payment_edit' => true, 'payment_delete' => true,
                    'expense_category_list' => true, 'expense_category_add' => true, 'expense_category_edit' => true, 'expense_category_delete' => true,
                    'exchange_rate_list' => true, 'exchange_rate_add' => true, 'exchange_rate_edit' => true, 'exchange_rate_delete' => true,
                    'type_of_order_list' => true, 'type_of_order_add' => true, 'type_of_order_edit' => true, 'type_of_order_delete' => true,
                    'tax_list' => true, 'tax_add' => true, 'tax_edit' => true, 'tax_delete' => true,
                    'discount_list' => true, 'discount_add' => true, 'discount_edit' => true, 'discount_delete' => true,
                ])
            ]);
            $position = new Position();
            $position->company_id = $company->id;
            $position->key_position_id = $key->id;
            $position->name = 'CEO MANAGER';
            $position->accessEmail = 1;
            $position->accessPin = 1;
            $position->access_permit = $key->access_permit;
            $position->save();

            if ($position) {
                $user = User::createFirst($data, $company, $position);
                $shop = Shop::createFirst($data, $company);
                $user->shops()->saveMany([$shop]);
                $keyPositions = [
                    KeyPosition::create([
                        'company_id' => $company->id,
                        'key' => 'supervisor',
                        'access_permit' => json_encode(['all' => false,
                            'article_list' => true, 'article_add' => false, 'article_edit' => false, 'article_delete' => false, 'article_transport' => false,
                            'vending_list' => true, 'vending_add' => false, 'vending_edit' => false, 'vending_delete' => false,
                            'category_list' => true, 'category_add' => false, 'category_edit' => false, 'category_delete' => false,
                            'mod_list' => true, 'mod_add' => false, 'mod_edit' => false, 'mod_delete' => false,
                            'supplier_list' => true, 'supplier_add' => false, 'supplier_edit' => false, 'supplier_delete' => false,
                            'buy_list' => true, 'buy_add' => false, 'buy_edit' => false, 'buy_delete' => false,
                            'sell_by_product' => true, 'sell_by_category' => true, 'sell_by_employer' => true, 'sell_by_payments' => true,
                            'employer_list' => true, 'employer_add' => false, 'employer_edit' => false, 'employer_delete' => false,
                            'assistance_list' => true, 'assistance_add' => false, 'assistance_edit' => false, 'assistance_delete' => false,
                            'client_list' => false, 'client_add' => true, 'client_edit' => true, 'client_delete' => false,
                            'config' => false,
                            'shop_list' => false, 'shop_add' => false, 'shop_edit' => false, 'shop_delete' => false,
                            'access_list' => false, 'access_add' => false, 'access_edit' => false, 'access_delete' => false,
                            'payment_list' => false, 'payment_add' => false, 'payment_edit' => false, 'payment_delete' => false,
                            'expense_category_list' => false, 'expense_category_add' => false, 'expense_category_edit' => false, 'expense_category_delete' => false,
                            'exchange_rate_list' => false, 'exchange_rate_add' => false, 'exchange_rate_edit' => false, 'exchange_rate_delete' => false,
                            'type_of_order_list' => false, 'type_of_order_add' => false, 'type_of_order_edit' => false, 'type_of_order_delete' => false,
                            'tax_list' => false, 'tax_add' => false, 'tax_edit' => false, 'tax_delete' => false,
                            'discount_list' => false, 'discount_add' => false, 'discount_edit' => false, 'discount_delete' => false,
                        ])
                    ]),
                    KeyPosition::create([
                        'company_id' => $company->id,
                        'key' => 'atm',
                        'access_permit' => json_encode(['all' => false,
                            'article_list' => false, 'article_add' => false, 'article_edit' => false, 'article_delete' => false, 'article_transport' => false,
                            'vending_list' => true, 'vending_add' => false, 'vending_edit' => false, 'vending_delete' => false,
                            'category_list' => false, 'category_add' => false, 'category_edit' => false, 'category_delete' => false,
                            'mod_list' => false, 'mod_add' => false, 'mod_edit' => false, 'mod_delete' => false,
                            'supplier_list' => false, 'supplier_add' => false, 'supplier_edit' => false, 'supplier_delete' => false,
                            'buy_list' => false, 'buy_add' => false, 'buy_edit' => false, 'buy_delete' => false,
                            'sell_by_product' => false, 'sell_by_category' => false, 'sell_by_employer' => false, 'sell_by_payments' => false,
                            'employer_list' => false, 'employer_add' => false, 'employer_edit' => false, 'employer_delete' => false,
                            'assistance_list' => false, 'assistance_add' => false, 'assistance_edit' => false, 'assistance_delete' => false,
                            'client_list' => false, 'client_add' => false, 'client_edit' => false, 'client_delete' => false,
                            'config' => false,
                            'shop_list' => false, 'shop_add' => false, 'shop_edit' => false, 'shop_delete' => false,
                            'access_list' => false, 'access_add' => false, 'access_edit' => false, 'access_delete' => false,
                            'payment_list' => false, 'payment_add' => false, 'payment_edit' => false, 'payment_delete' => false,
                            'expense_category_list' => false, 'expense_category_add' => false, 'expense_category_edit' => false, 'expense_category_delete' => false,
                            'exchange_rate_list' => false, 'exchange_rate_add' => false, 'exchange_rate_edit' => false, 'exchange_rate_delete' => false,
                            'type_of_order_list' => false, 'type_of_order_add' => false, 'type_of_order_edit' => false, 'type_of_order_delete' => false,
                            'tax_list' => false, 'tax_add' => false, 'tax_edit' => false, 'tax_delete' => false,
                            'discount_list' => false, 'discount_add' => false, 'discount_edit' => false, 'discount_delete' => false,
                        ])])
                ];
                foreach ($keyPositions as $k => $value) {
                    $p = new Position();
                    $p->company_id = $company->id;
                    $p->key_position_id = $value->id;
                    $p->name = $value->key;
                    $p->accessEmail = 1;
                    $p->accessPin = 1;
                    $p->access_permit = $value->access_permit;
                    $p->save();
                }
                return $user;
            }

        }
    }

    /**
     * @param Request $request
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
