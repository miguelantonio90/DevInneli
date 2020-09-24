<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return JsonResponse|Response
     */
    public function index()
    {
        $shops = $this->getAllShopByUserId();
        return ResponseHelper::sendResponse($shops, 'Shops retrieved successfully.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $managerEmail = DB::table('users')
            ->select('email')
            ->where('users.id', '=', auth()->id())
            ->get();
        $data['email'] = $managerEmail[0]->email;
        $data['user_id'] = auth()->id();
        $created = Shop::create($data);
        return ResponseHelper::sendResponse(
            $created,
            'Shop has created successfully.'
        );
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
        $edit = Shop::findOrFail($id)->update($request->all());
        return ResponseHelper::sendResponse(
            $edit,
            'User has updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (count($this->getAllShopByUserId()) > 1) {
            $delete = Shop::findOrFail($id)->delete();
            return ResponseHelper::sendResponse(
                $delete,
                'Shop has deleted successfully.'
            );
        }
        return ResponseHelper::sendResponse([], "Shop can't by deleted");
    }

    private function getAllShopByUserId()
    {
        return Shop::latest()
            ->where('user_id', '=', auth()->id())
            ->get();
    }
}
