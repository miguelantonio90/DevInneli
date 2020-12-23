<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\RefoundManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RefoundController extends Controller
{
    /**
     * @var RefoundManager
     */
    private $refundManager;

    /**
     * ArticleController constructor.
     * @param  RefoundManager  $refundManager
     */
    public function __construct(RefoundManager $refundManager)
    {
        parent::__construct();
        $this->refundManager = $refundManager;
    }


    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function index(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->refundManager->findAllByCompany(),
            'Articles retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     * @throws ValidationException
     * @throws Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $user = $this->refundManager->new($data);
        return ResponseHelper::sendResponse(
            $user,
            'Article has created successfully.'
        );
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
        return ResponseHelper::sendResponse(
            $this->refundManager->edit($id, $request->all()),
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
            $this->refundManager->delete($id),
            'Article has deleted successfully.'
        );
    }

    /**
     * @param  Request  $request
     */
    public function import(Request $request)
    {
        $this->importManger->importData($request->file);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function refound(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->refundManager->refound($request->all()),
            'Article has updated successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'cant' => ['required', 'double'],
            'money' => ['required', 'double'],
        ]);
    }
}
