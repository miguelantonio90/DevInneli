<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\BuyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * @var BuyManager
     */
    private $inventoryManager;

    /**
     * UserController constructor.
     * @param  BuyManager  $inventoryManager
     */
    public function __construct(BuyManager $inventoryManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->inventoryManager = $inventoryManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->inventoryManager->findAllByCompany(),
            'Inventories retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $inventory = $this->inventoryManager->new($data);

        return ResponseHelper::sendResponse(
            $inventory,
            'Inventory has created successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        return ResponseHelper::sendResponse(
            $this->inventoryManager->edit($id, $request->all()),
            'Inventory has updated successfully.'
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->inventoryManager->delete($id),
            'Inventory has deleted successfully.'
        );
    }
}
