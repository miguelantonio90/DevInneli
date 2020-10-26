<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\TypeOfOrderManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TypeOfOrderController extends Controller
{
    /**
     * @var TypeOfOrderManager
     */
    protected $typeOrderManager;

    /**
     * TypeOfOrderController constructor.
     *
     * @param  TypeOfOrderManager  $typeOrderManager
     */
    public function __construct(TypeOfOrderManager $typeOrderManager)
    {
        $this->middleware('auth');
        $this->typeOrderManager = $typeOrderManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->typeOrderManager->findAllByCompany(),
            'Type of Orders retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->typeOrderManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Type of Order has created successfully.'
        );
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:500']
        ]);
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        return ResponseHelper::sendResponse(
            $this->typeOrderManager->edit($id, $data),
            'Type of Order has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->typeOrderManager->delete($id),
            'Users has deleted successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     */
    public function setPrincipal(Request $request, $id)
    {
        return ResponseHelper::sendResponse(
            $this->typeOrderManager->setPrincipal($id, $request->all()),
            'Field principal has updated successfully.'
        );
    }
}
