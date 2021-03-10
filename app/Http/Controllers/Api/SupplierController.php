<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\CompanyManager;
use App\Managers\SupplierManager;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorAlias;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

//use Illuminate\Notifications\Notification;

class SupplierController extends Controller
{
    /**
     * @var SupplierManager
     */
    private $supplierManager;

    /**
     * SupplierController constructor.
     * @param  SupplierManager  $supplierManager
     */
    public function __construct(SupplierManager $supplierManager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->supplierManager = $supplierManager;
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
            $this->supplierManager->findAllByCompany(),
            'Supplier retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
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
        $supplier = $this->supplierManager->new($data);
        $info = [
            'client' => Company::findOrFail(CompanyManager::getCompanyByAdmin()->id)->name,
            'registerUrl' => config('frontend.email_register_url').base64_encode($supplier),
        ];
        if (array_key_exists('sendEmail', $data) && $data['sendEmail']) {
            Mail::send('emails.invitation-supplier', $info,
                function ($message) use ($supplier) {
                    $message->subject('Invitation Email Address');
                    $message->to($supplier->email);
                });
        }
        return ResponseHelper::sendResponse(
            $supplier,
            'Supplier has created successfully.'
        );
    }

    protected function validator(array $data): ValidatorAlias
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param    $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        return ResponseHelper::sendResponse(
            $this->supplierManager->edit($id, $request->all()),
            'Supplier has updated successfully.'
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
            $this->supplierManager->delete($id),
            'Supplier has deleted successfully.'
        );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function getSupplierClients(Request $request)
    {
        return ResponseHelper::sendResponse(
            $this->supplierManager->getSupplierClients(),
            'Supplier has deleted successfully.'
        );
    }
}
