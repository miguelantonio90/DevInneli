<?php

namespace App\Http\Controllers\Api;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\UserManager;
use App\Position;
use App\Shop;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $positions = $request->get('positions');
        $shops = $request->get('shops');
        $user = User::create([
            'company_id' => $data['company_id'],
            'position_id'=>Position::where('key', $positions['key'])->first()->id,
            'firstName' => $request->get('firstName'),
            'lastName' => $request->get('lastName'),
            'avatar' => $request->get('avatar'),
            'email' => $request->get('email'),
            'created_by' => auth()->id()
        ]);
        $user->pinCode = $request->get('pinCode');
        $user->lastName = $request->get('lastName');
        $user->avatar = $request->get('avatar');
        $user->phone = $request->get('phone');
        $user->isAdmin = 0;
        $user->isManager = 0;
        $idShops = [];
        foreach ($shops as $key => $value) {
            $idShops[$key] = $value['id'];
        }
        $employShop = Shop::find($idShops);
        $user->shops()->attach($employShop);
        $user->save();

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
}
