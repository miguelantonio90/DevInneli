<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CategoryManager;
use App\Managers\CompanyManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * ClientController constructor.
     * @param  CategoryManager  $categoryManager
     */
    public function __construct(CategoryManager $categoryManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->categoryManager = $categoryManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->categoryManager::findAllByCompany(),
            'Categories retrieved successfully.'
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
        $user = $this->categoryManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Category has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param    $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->categoryManager->edit($id, $request->all()),
            'Category has updated successfully.'
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
            $this->categoryManager->delete($id),
            'Categories has deleted successfully.'
        );
    }
}
