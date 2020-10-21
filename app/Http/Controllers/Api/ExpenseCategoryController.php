<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\ExpenseCategoryManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ExpenseCategoryController extends Controller
{
    /**
     * @var ExpenseCategoryManager
     */
    private $categoryManager;

    /**
     * ExpenseCategoryController constructor.
     * @param $categoryManager
     */
    public function __construct(ExpenseCategoryManager $categoryManager)
    {
        $this->middleware('auth');
        $this->categoryManager = $categoryManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->categoryManager->findAllByCompany(),
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
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
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
            $this->categoryManager->edit($id, $request->all()),
            'Category has updated successfully.'
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
            $this->categoryManager->delete($id),
            'Category has deleted successfully.'
        );
    }
}
