<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\ArticleManager;
use App\Managers\CompanyManager;
use App\Managers\ImportManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RefoundController extends Controller
{
    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * @var ImportManager
     */
    private $importManger;

    /**
     * ArticleController constructor.
     * @param ArticleManager $articleManager
     * @param ImportManager $importManger
     */
    public function __construct(ArticleManager $articleManager, ImportManager $importManger)
    {
        parent::__construct();
        $this->articleManager = $articleManager;
        $this->importManger = $importManger;
    }


    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function index(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->articleManager->findAllByCompany(),
            'Articles retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->articleManager->new($data);
        return ResponseHelper::sendResponse(
            $user,
            'Article has created successfully.'
        );
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param    $id
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->articleManager->edit($id, $request->all()),
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
            $this->articleManager->delete($id),
            'Article has deleted successfully.'
        );
    }

    /**
     * @param Request $request
     */
    public function import(Request $request)
    {
        $this->importManger->importData($request->file);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function refound(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->articleManager->refound($request->all()),
            'Article has updated successfully.'
        );
    }
}
