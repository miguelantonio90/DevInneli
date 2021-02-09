<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\UserManager;
use App\Notification;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param  UserManager  $userManager
     */
    public function __construct(UserManager $userManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->userManager = $userManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->userManager->findAllByCompany(),
            'Users retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function getAll(Request $request): JsonResponse
    {
        return ResponseHelper::sendResponse(
            $this->userManager->findAllByCompany(),
            'Users retrieved successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['company_id'] = (CompanyManager::getCompanyByAdmin())->id;
        $this->validator($data)->validate();
        $user = $this->userManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'User has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return User::latest()->where('isAdmin', '=', 0)->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    $id
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update($id, Request $request)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->userManager->edit($id, $request->all()),
            'User has updated successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function updateAvatar(Request $request, $id)
    {
        if ($request !== null) {
            $app = (new User())->find($id);
            $app->find($id);
            $app->avatar = $request->get('image');
            $app->save();
            return ResponseHelper::sendResponse(
                $app,
                'User avatar has updated successfully.'
            );
        }

        return ResponseHelper::sendError(
            401,
            'User avatar has not updated.'
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
            $this->userManager->delete($id),
            'Users has deleted successfully.'
        );
    }

    /**
     * @return JsonResponse|Response
     */
    public function userLogin()
    {
        try {
            return ResponseHelper::sendResponse(
                cache()->get('userPin'),
                'Users has deleted successfully.'
            );
        } catch (Exception $e) {
            return ResponseHelper::sendError($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @param $id
     */
    public  function readNotification(Request $request, $id){
        $notif = Notification::findOrFail($id);
        $notif->read = true;
        $notif->save();
        return ResponseHelper::sendResponse([],'Notification is read.');
    }
}
