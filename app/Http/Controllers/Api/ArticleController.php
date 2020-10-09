<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\ArticleManager;
use App\Managers\CompanyManager;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * UserController constructor.
     * @param  ArticleManager  $articleManager
     */
    public function __construct(ArticleManager $articleManager)
    {
        $this->middleware('auth');
        $this->articleManager = $articleManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->articleManager->findAllByCompany(),
            'Articles retrieved successfully.'
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
        $user = $this->articleManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'User has created successfully.'
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
            'barCode' => ['required', 'string', 'max:255']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show(int $id)
    {
        return User::latest()->where('isAdmin', '=', 0)->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->articleManager->edit($id, $request->all()),
            'User has updated successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     */
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
     * @param  int  $id
     * @return JsonResponse|Response|void
     */
    public function destroy(int $id)
    {
        return ResponseHelper::sendResponse(
            $this->articleManager->delete($id),
            'Users has deleted successfully.'
        );
    }
}
