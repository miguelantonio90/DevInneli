<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\ShopManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;

class ShopController extends Controller
{
    /**
     * @var ShopManager
     */
    private $shopManager;

    public function __construct(ShopManager $shopManager)
    {
        $this->middleware('auth');
        $this->shopManager = $shopManager;
    }

    /**
     * @return JsonResponse|Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse($this->shopManager->findAllByCompany(), 'Shops retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->shopManager->new($request), 'Shop has created successfully.');
    }

    /**
     * Display the specified resource.
     *
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
     * @param    $id
     * @return Response
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
     * @param    $id
     * @return Response
     */
    public function destroy($id)
    {
        $dlt = $this->shopManager->delete($id);
        return ResponseHelper::sendResponse($dlt[0], $dlt[1]);
    }
}
