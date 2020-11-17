<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\SaleManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    /**
     * @var SaleManager
     */
    private $saleManager;

    /**
     * SaleController constructor.
     * @param  SaleManager  $saleManager
     */
    public function __construct(SaleManager $saleManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->saleManager = $saleManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->findAllByCompany(),
            'Sale retrieved successfully.'
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
        $sale = $this->saleManager->new($data);

        return ResponseHelper::sendResponse(
            $sale,
            'Sale has created successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param    $id
     * @return JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->edit($id, $request->all()),
            'Sale has updated successfully.'
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
            $this->saleManager->delete($id),
            'Sale has deleted successfully.'
        );
    }
}
