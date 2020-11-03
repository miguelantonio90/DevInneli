<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\DiscountManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    /**
     * @var DiscountManager
     */
    private $discountManager;

    /**
     * ClientController constructor.
     * @param  DiscountManager  $discountManager
     */
    public function __construct(DiscountManager $discountManager)
    {
        $this->middleware('auth');
        $this->discountManager = $discountManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->discountManager->findAllByCompany(),
            'Taxes retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->discountManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Tax has created successfully.'
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
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->discountManager->edit($id, $request->all()),
            'Tax has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    $id
     * @return JsonResponse|Response|void
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->discountManager->delete($id),
            'Taxes has deleted successfully.'
        );
    }
}
