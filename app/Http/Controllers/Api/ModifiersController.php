<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\ModifiersManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ModifiersController extends Controller
{
    /**
     * @var ModifiersManager
     */
    protected $manager;

    /**
     * ModifiersController constructor.
     *
     * @param  ModifiersManager  $manager
     */
    public function __construct(ModifiersManager $manager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->manager = $manager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->manager->findAllByCompany(),
            'Modifiers retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->manager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Modifiers has created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->manager->edit($id, $request->all()),
            'Modifiers has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->manager->delete($id),
            'Modifiers has deleted successfully.'
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
}
