<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\ExchangeRateManger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExchangeRateController extends Controller
{
    /**
     * @var ExchangeRateManger
     */
    private $exchangeManager;

    /**
     * ExchangeRateController constructor.
     *
     * @param  ExchangeRateManger  $exchangeManager
     */
    public function __construct(ExchangeRateManger $exchangeManager)
    {
        $this->middleware('auth');
        $this->exchangeManager = $exchangeManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->exchangeManager->findAllByCompany(),
            'Exchange Rates retrieved successfully.'
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
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->exchangeManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Exchange Rate has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'country' => ['required'],
            'change' => 'required|numeric|regex:/^[\d]{0,15}(\.[\d]{1,2})?$/'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->exchangeManager->edit($id, $request->all()),
            'Exchange Rate has updated successfully.'
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
            $this->exchangeManager->delete($id),
            'Exchange Rate has deleted successfully.'
        );
    }
}
