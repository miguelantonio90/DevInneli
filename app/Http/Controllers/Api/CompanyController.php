<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * @var CompanyManager
     */
    protected $companyManager;

    /**
     * CompanyController constructor.
     * @param  CompanyManager  $compnayManager
     */
    public function __construct(CompanyManager $compnayManager)
    {
        $this->companyManager = $compnayManager;
        parent::__construct();

        $this->middleware('auth');
    }


    /**
     * @param $email
     * @return JsonResponse|Response
     */
    public function companyByEmail($email)
    {
        //$this->validEmail($request->all())->validate();

        return ResponseHelper::sendResponse(
            CompanyManager::getCompanyByEmail($email),
            'Company retrieved successfully.'
        );

    }

    /**
     *
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->companyManager->getAllCompanies(),
            'Categories retrieved successfully.'
        );

    }

    /**
     * @param  Request  $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
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
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();

        $edit = Company::findOrFail($id)->update($request->all());

        return ResponseHelper::sendResponse(
            $edit,
            'User has updated successfully.'
        );
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string'],
            'address' => ['string'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    $id
     * @return Response
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->userManager->delete($id),
            'Company has deleted successfully.'
        );
    }

    public function updateLogo(Request $request, $id)
    {
        if (!empty($request)) {
            $app = (new Company())->find($id);
            $app->find($id);
            $app->logo = $request->get('image');
            $app->save();
            return ResponseHelper::sendResponse(
                $app,
                'Company logo has updated successfully.'
            );
        } else {
            return ResponseHelper::sendError(
                401,
                'Company logo has not updated.'
            );
        }
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validEmail(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255']
        ]);
    }
}
