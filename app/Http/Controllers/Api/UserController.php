<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::latest()->where('isAdmin', '=', 0)
            ->with('positions')
            ->with('employee')
            ->get();

        return ResponseHelper::sendResponse(
            $users,
            'Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $created = User::create($request->all());

        return ResponseHelper::sendResponse(
            $created,
            'User has created successfully.'
        );
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {
        return User::latest()->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse|Response
     */
    public function update(Request $request, int $id)
    {
        $this->validator($request->all())->validate();

        $edit = User::findOrFail($id)->update($request->all());

        return ResponseHelper::sendResponse(
            $edit,
            'User has updated successfully.'
        );
    }

    public function updateAvatar(Request $request, $id)
    {
        if (!empty($request)) {
            $app = (new User())->find($id);
            $app->find($id);
            $app->avatar = $request->get('image');
            $app->save();
            return ResponseHelper::sendResponse(
                $app,
                'User avatar has updated successfully.'
            );
        } else {
            return ResponseHelper::sendError(
                401,
                'User avatar has not updated.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|void
     */
    public function destroy(int $id)
    {

        $delete = User::findOrFail($id)->delete();
        return ResponseHelper::sendResponse(
            $delete,
            'Users has deleted successfully.'
        );
    }
}
