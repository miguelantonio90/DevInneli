<?php

namespace App\Http\Controllers\Api\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\Accounting\AccountManager;
use App\Managers\CompanyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * ClientController constructor.
     * @param  AccountManager  $accountManager
     */
    public function __construct(AccountManager $accountManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->accountManager = $accountManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->accountManager::findAllByCompany(),
            'Categories retrieved successfully.'
        );
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function show($id)
    {
        return ResponseHelper::sendResponse(
            $this->accountManager->getAccountById($id),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Display a listing of the resource by shop.
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getCategoriesShop(Request $request): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->accountManager::getCategoriesShop($request),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->accountManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Category has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param    $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->accountManager->edit($id, $request->all()),
            'Category has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    $id
     * @return JsonResponse|Response|void
     * @throws Exception
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->accountManager->delete($id),
            'Categories has deleted successfully.'
        );
    }
}
