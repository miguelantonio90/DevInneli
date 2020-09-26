<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\AccessManager;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{
    protected $accessManager;

    public function __construct(AccessManager $accessManager)
    {
        $this->middleware('auth');
        $this->accessManager = $accessManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $positions = Position::latest()->where('key', '<>', 'admin')->get();

        return ResponseHelper::sendResponse(
            $positions,
            'Positions retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->accessManager->new($request), 'Position has created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();

        $edit = Position::findOrFail($id)->update($request->all());

        return ResponseHelper::sendResponse(
            $edit,
            'Role has updated successfully.'
        );
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'key' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'accessPin' => ['boolean'],
            'accessEmail' => ['boolean'],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
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
