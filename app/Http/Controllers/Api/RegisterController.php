<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Position;
use App\Providers\RouteServiceProvider;
use App\Shop;
use App\Supplier;
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
     * @param  Request  $request
     * @return Application|JsonResponse|RedirectResponse|Response|Redirector
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'shopName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:companies',
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
     * @param  array  $data
     * @return User|JsonResponse|Response
     */
    protected function create(array $data)
    {
        $response = new User();
        $company = Company::create([
            'name' => $data['shopName'],
            'email' => $data['email'],
            'country' => $data['country']['id'],
            'phone' => $data['phone'],
            'currency' => $data['country']['currency']
        ]);
        $company->sector = $data['sector'];
        $company->save();
        if ($company) {
            $position = new Position();
            $position->company_id = $company->id;
            $position->name = 'CEO';
            $position->accessEmail = 1;
            $position->accessPin = 1;
            $position->access_permit = json_encode([
                [
                    'title' => ['name' => 'dashboard', 'value' => true],
                    'actions' => ['dashboard' => true]
                ],
                [
                    'title' => ['name' => 'manager_article', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true, 'transport' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_vending', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_order', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_boxes', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'boxes_open' => true, 'boxes_close' => true, 'import' => true,
                        'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_refunds', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true, 'edit' => true,
                        'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_category', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_mod', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_supplier', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_bank', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_buy', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_sell', 'value' => true],
                    'actions' =>
                        [
                            'just_yours' => false, 'sell_by_product' => true, 'sell_by_category' => true,
                            'sell_by_employer' => true, 'sell_by_payments' => true, 'import' => true,
                            'export' => true
                        ]
                ],
                [
                    'title' => ['name' => 'manager_employer', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_assistence', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_client', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_shop', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_key', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_access', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_payment', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_expense_category', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_exchange_rate', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_type_of_order', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_tax', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ],
                [
                    'title' => ['name' => 'manager_discount', 'value' => true],
                    'actions' => [
                        'just_yours' => false, 'list' => true, 'create' => true,
                        'edit' => true, 'delete' => true, 'import' => true, 'export' => true
                    ]
                ]
            ]);
            $position->save();
            if ($position) {
                $user = User::createFirst($data, $company, $position);
                $shop = Shop::createFirst($data, $company);
                $user->shops()->saveMany([$shop]);
                $supervisor = new Position();
                $supervisor->company_id = $company->id;
                $supervisor->name = 'Supervisor';
                $supervisor->accessEmail = 1;
                $supervisor->accessPin = 1;
                $supervisor->access_permit = json_encode([
                    [
                        'title' => ['name' => 'dashboard', 'value' => true],
                        'actions' => ['dashboard' => true]
                    ],
                    [
                        'title' => ['name' => 'manager_article', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'transport' => false, 'import' => false,
                            'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_vending', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_order', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_boxes', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'boxes_open' => false, 'boxes_close' => false,
                            'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_refunds', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false, 'edit' => false,
                            'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_bank', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_category', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_mod', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => true,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_supplier', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_buy', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_sell', 'value' => true],
                        'actions' =>
                            [
                                'just_yours' => false, 'sell_by_product' => true, 'sell_by_category' => true,
                                'sell_by_employer' => true, 'sell_by_payments' => true, 'import' => true,
                                'export' => true
                            ]
                    ],
                    [
                        'title' => ['name' => 'manager_employer', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_assistence', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_client', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_shop', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_access', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_payment', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_expense_category', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_exchange_rate', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_type_of_order', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_tax', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => true
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_discount', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ]
                ]);
                $supervisor->save();
                $atm = new Position();
                $atm->company_id = $company->id;
                $atm->name = 'ATM';
                $atm->accessEmail = 1;
                $atm->accessPin = 1;
                $atm->access_permit = json_encode([
                    [
                        'title' => ['name' => 'dashboard', 'value' => true],
                        'actions' => ['dashboard' => true]
                    ],
                    [
                        'title' => ['name' => 'manager_article', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => true,
                            'edit' => false, 'delete' => false, 'transport' => false, 'import' => false,
                            'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_vending', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => true,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_order', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_boxes', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'boxes_open' => true, 'boxes_close' => true,
                            'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_refunds', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => true, 'edit' => false,
                            'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_category', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_mod', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => true,
                            'edit' => true, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_supplier', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_buy', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_sell', 'value' => false],
                        'actions' =>
                            [
                                'just_yours' => false, 'sell_by_product' => false, 'sell_by_category' => false,
                                'sell_by_employer' => false, 'sell_by_payments' => false, 'import' => false,
                                'export' => false
                            ]
                    ],
                    [
                        'title' => ['name' => 'manager_employer', 'value' => false],
                        'actions' => [
                            'just_yours' => true, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_assistence', 'value' => true],
                        'actions' => [
                            'just_yours' => true, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_client', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_shop', 'value' => false],
                        'actions' => [
                            'just_yours' => true, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_access', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_payment', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => true,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_expense_category', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_exchange_rate', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_type_of_order', 'value' => false],
                        'actions' => [
                            'just_yours' => false, 'list' => false, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_tax', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ],
                    [
                        'title' => ['name' => 'manager_discount', 'value' => true],
                        'actions' => [
                            'just_yours' => false, 'list' => true, 'create' => false,
                            'edit' => false, 'delete' => false, 'import' => false, 'export' => false
                        ]
                    ]
                ]);
                $atm->save();
                $user->isSupplier = count(Supplier::latest()->where('email', '=', $user['email'])->get()) > 0;
                $user->save();
                $response = $user;
            }
        }
        return $response;
    }

    /**
     * @param  Request  $request
     * @param $user
     * @return JsonResponse
     */
    protected function registered(Request $request, $user): JsonResponse
    {
        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $user->createToken(config('services.passport.client_secret'))->accessToken
        ]);
    }
}
