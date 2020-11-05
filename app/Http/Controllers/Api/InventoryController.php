<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\InventoryManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    /**
     * @var InventoryManager
     */
    private $inventoryManager;

    /**
     * UserController constructor.
     * @param  InventoryManager  $inventoryManager
     */
    public function __construct(InventoryManager $inventoryManager)
    {
        $this->middleware('auth');
        $this->inventoryManager = $inventoryManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->inventoryManager->findAllByCompany(),
            'Articles retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $inventory = $this->inventoryManager->new($data);

        return ResponseHelper::sendResponse(
            $inventory,
            'Article has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'barCode' => ['required', 'string', 'max:255']
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
            $this->inventoryManager->edit($id, $request->all()),
            'Article has updated successfully.'
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
            $this->inventoryManager->delete($id),
            'Article has deleted successfully.'
        );
    }
}
