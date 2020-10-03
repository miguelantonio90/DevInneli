<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * UserController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->middleware('auth');
        $this->userManager = $userManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->userManager->findAllByCompany(),
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
        $data = $request->all();
        $data['company_id'] = (int)$this->getCompanyByAdmin();
        $this->validator($data)->validate();
        $user = $this->userManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'User has created successfully.'
        );
    }

    /**
     * Find Company Id using admin authenticate
     * @return string
     */
    private function getCompanyByAdmin(): string
    {
        return DB::table('users')
            ->select('company_id')
            ->where('users.id', '=', auth()->id())
            ->get()[0]->company_id;
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
     * @param int $id
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function update(int $id, Request $request)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->userManager->edit($id, $request->all()),
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
        return ResponseHelper::sendResponse(
            $this->userManager->delete($id),
            'Users has deleted successfully.'
        );
    }
}
