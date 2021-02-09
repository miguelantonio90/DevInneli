<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\SupplyManager;
use App\Managers\CompanyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplyController extends Controller
{
    /**
     * @var SupplyManager
     */
    private $supplyManager;

    /**
     * UserController constructor.
     * @param  SupplyManager  $supplyManager
     */
    public function __construct(SupplyManager $supplyManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->supplyManager = $supplyManager;
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
            $this->supplyManager->findAllByCompany(),
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
        $inventory = $this->supplyManager->new($data);

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
            $this->supplyManager->edit($id, $request->all()),
            'Inventory has updated successfully.'
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function findNumberFacture(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->supplyManager->findFactureNumber($request),
            'New number sale retrieved successfully.'
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
            $this->supplyManager->delete($id),
            'Inventory has deleted successfully.'
        );
    }
}
