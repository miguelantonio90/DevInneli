<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\AccessManager;
use App\Position;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AccessController extends Controller
{
    protected $accessManager;

    public function __construct(AccessManager $accessManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->accessManager = $accessManager;
    }

    /**
     * @return JsonResponse|Response
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->accessManager->getByCompany(),
            'Positions retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->accessManager->new($request->all()),
            'Position has created successfully.');
    }

    /**
     *  Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();

        return ResponseHelper::sendResponse(
            $this->accessManager->edit($id, $request->all()),
            'Role has updated successfully.'
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
            'accessPin' => ['boolean'],
            'accessEmail' => ['boolean'],
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     */
    public function destroy($id)
    {
        $delete = Position::findOrFail($id)->delete();
        return ResponseHelper::sendResponse(
            $delete,
            'Role has deleted successfully.'
        );
    }

}
