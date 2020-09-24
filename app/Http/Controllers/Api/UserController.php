<?php

namespace App\Http\Controllers\Api;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Position;
use App\Shop;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
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
        if (auth()->user()['isAdmin'] === 1) {
            $users = User::latest()->get();
        } else {
            $users = User::findOrFail(auth()->id())->where('isAdmin', '=', '0')
                ->with('employer')->with('shops')
                ->with('shops')
                ->with('positions')
                ->get();

        }

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
        $employ = $request->get('employer');
        $positions = $request->get('positions');
        $shops = $request->get('shops');

        $user = User::create([
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'avatar' => $request->get('avatar'),
            'email' => $request->get('email'),
            'password' => Hash::make($employ['pinCode']),
            'created_by' => auth()->id(),
            'isAdmin' => 0,
        ]);

        $user->positions()
            ->attach(Position::where('key', $positions['key'])->first());

        $employer = new Employee();
        $employer->pinCode = $employ['pinCode'];
        $employer->phone = $employ['phone'];
        $user->employer()->save($employer);

        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }

        $employShop = Shop::find($idShops);
        $employer->shops()->attach($employShop);

        return ResponseHelper::sendResponse(
            $user,
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
        return User::latest()->where('isAdmin', '=', 0)->get($id);
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
