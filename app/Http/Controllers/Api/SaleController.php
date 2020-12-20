<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\SaleManager;
use Exception;
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
    public function index(): JsonResponse
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
     * @throws Exception
     */
    public function store(Request $request): Response
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
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->edit($id, $request->all()),
            'Sale has updated successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @param $limit
     * @return JsonResponse|Response
     */
    public function findSalesByLimit(Request $request, $limit)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->findSalesByLimit($limit),
            'Latest sales retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function getTotalSalesStatic(Request $request){
        return ResponseHelper::sendResponse(
            $this->saleManager->getTotalsStatic(),
            'Sales statics retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function saleCategory(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->saleCategory($request->all()),
            'Sale by category retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function salePayment(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->salePayment($request->all()),
            'Sale by category retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function saleByProduct(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->saleByProduct($request->all()),
            'Sale by category retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function saleByEmployer(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->saleManager->saleEmployer($request->all()),
            'Sale by employer retrieved successfully.'
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
            $this->saleManager->delete($id),
            'Sale has deleted successfully.'
        );
    }
}
