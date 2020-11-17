<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\ClientManager;
use App\Managers\CompanyManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    /**
     * @var ClientManager
     */
    private $clientManager;

    /**
     * ClientController constructor.
     * @param  ClientManager  $clientManager
     */
    public function __construct(ClientManager $clientManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->clientManager = $clientManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->clientManager->findAllByCompany(),
            'Clients retrieved successfully.'
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
        $user = $this->clientManager->new($data);

        return ResponseHelper::sendResponse(
            $user,
            'Client has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param    $id
     * @return void
     */
    public function show($id)
    {
//        return Client::latest()->where('isAdmin', '=', 0)->get($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param    $id
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->clientManager->edit($id, $request->all()),
            'Client has updated successfully.'
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
            $app = (new Client())->find($id);
            $app->find($id);
            $app->avatar = $request->get('image');
            $app->save();
            return ResponseHelper::sendResponse(
                $app,
                'Client avatar has updated successfully.'
            );
        } else {
            return ResponseHelper::sendError(
                401,
                'Client avatar has not updated.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    $id
     * @return JsonResponse|Response|void
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->clientManager->delete($id),
            'Clients has deleted successfully.'
        );
    }
}
