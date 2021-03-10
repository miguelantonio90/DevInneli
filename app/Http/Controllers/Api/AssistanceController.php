<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Managers\AssistanceManager;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidatorAlias;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssistanceController extends Controller
{
    protected $manager;

    /**
     * AssistanceController constructor.
     * @param  AssistanceManager  $manager
     */
    public function __construct(AssistanceManager $manager)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->manager = $manager;
    }

    /**
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function index()
    {
        return ResponseHelper::sendResponse(
            $this->manager->findAll(),
            'Assistances retrieved successfully.'
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
        $this->validator($request->all())->validate();

        $assistance = $this->manager->new($request->all());

        return ResponseHelper::sendResponse(
            $assistance,
            'Assistance has created successfully.'
        );
    }

    /**
     * @param  array  $data
     * @return ValidatorAlias
     */
    protected function validator(array $data): ValidatorAlias
    {
        return Validator::make($data, [
            'datetimeEntry' => ['required'],
            'datetimeExit' => ['required'],
            'totalHours' => ['required', 'integer'],
        ]);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return JsonResponse|Response
     * @throws ValidationException
     * @throws Exception
     */
    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();

        return ResponseHelper::sendResponse(
            $this->manager->edit($id, $request->all()),
            'Assistance has updated successfully.'
        );
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     * @throws Exception
     */
    public function destroy($id)
    {
        return ResponseHelper::sendResponse(
            $this->manager->delete($id),
            'Assistance has deleted successfully.'
        );
    }
}
