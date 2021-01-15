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
     * @throws Exception
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
     * @return JsonResponse|Response
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
     * @throws Exception
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
     * @throws Exception
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
    public function import(Request $request): void
    {
        $this->importManger->importData($request->file);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
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
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'cant' => ['required', 'double'],
            'money' => ['required', 'double'],
        ]);
    }
}
