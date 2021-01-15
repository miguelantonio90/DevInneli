<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\KeyPosition;
use App\Managers\KeyPositionsManager;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class KeyPositionsController extends Controller
{


    /**
     * @var KeyPositionsManager
     */
    protected $keyPositionsManager;

    public function __construct(KeyPositionsManager $keyPositionsManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->keyPositionsManager = $keyPositionsManager;
    }

    /**
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->keyPositionsManager->getByCompany(),
            'Key Positions retrieved successfully.'
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
        $this->validator($request->all())->validate();

        return ResponseHelper::sendResponse(
            $this->keyPositionsManager->new($request->all()), 'Key Positions has created successfully.');
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'key' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $edit = $this->keyPositionsManager->edit($id, $request->all());

        return ResponseHelper::sendResponse(
            $edit,
            'Role has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->keyPositionsManager->delete($id),
            'Role has deleted successfully.'
        );
    }

}
