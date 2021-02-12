<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\ShopManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    /**
     * @var ShopManager
     */
    private $shopManager;

    public function __construct(ShopManager $shopManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->shopManager = $shopManager;
    }

    /**
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function index()
    {
        return ResponseHelper::sendResponse($this->shopManager->findAllByCompany(), 'Shops retrieved successfully.');
    }


    /**
     * Display a listing of the resource by shop.
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getShopData(Request $request): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->shopManager::getShopData($request),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Display a listing of the resource by shop.
     * @return JsonResponse
     * @throws Exception
     */
    public function getShopNoConfig(): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->shopManager::getShopNoConfig(),
            'Categories retrieved successfully.'
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
        return ResponseHelper::sendResponse(
            $this->shopManager->new($request), 'Shop has created successfully.');
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
            $this->shopManager->edit($id, $request),
            'User has updated successfully.'
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
        $dlt = $this->shopManager->delete($id);

        if ($dlt[0]) {
            return ResponseHelper::sendResponse($dlt[0], $dlt[1]);
        }

        return ResponseHelper::sendError($dlt[1], $dlt[2], 402);
    }
}
