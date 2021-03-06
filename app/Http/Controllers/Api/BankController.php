<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\BankManager;
use App\Managers\CompanyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BankController extends Controller
{
    /**
     * @var BankManager
     */
    private $bankManager;

    /**
     * BankController constructor.
     * @param BankManager $bankManager
     */
    public function __construct(BankManager $bankManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->bankManager = $bankManager;
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
            $this->bankManager::findAllByCompany(),
            'Banks retrieved successfully.'
        );
    }

    /**
     * Display a listing of the resource by shop.
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getBanksShop(Request $request): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->bankManager::getBanksShop($request),
            'Banks retrieved successfully.'
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
        $user = $this->bankManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Bank has created successfully.'
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
            $this->bankManager->edit($id, $request->all()),
            'Bank has updated successfully.'
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
            $this->bankManager->delete($id),
            'Banks has deleted successfully.'
        );
    }
}
