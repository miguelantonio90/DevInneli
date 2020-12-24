<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\BoxManager;
use App\Managers\CompanyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BoxController extends Controller
{
    /**
     * @var BoxManager
     */
    private $boxManager;

    /**
     * ClientController constructor.
     * @param  BoxManager  $boxManager
     */
    public function __construct(BoxManager $boxManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->boxManager = $boxManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->boxManager->findAllByCompany(),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->boxManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Category has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function show($id)
    {
        return ResponseHelper::sendResponse(
            $this->boxManager->getOpenClose($id),
            'Categories has deleted successfully.'
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
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->boxManager->edit($id, $request->all()),
            'Category has updated successfully.'
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
            $this->boxManager->delete($id),
            'Categories has deleted successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function sendOpenClose(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->boxManager->openClose($request->all()),
            'OpenClose has deleted successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function getDetailOfBox(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->boxManager->getDetailOfBox($request->all()),
            'OpenClose Data is loaded.'
        );
    }
}
