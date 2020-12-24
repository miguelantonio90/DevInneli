<?php

namespace App\Http\Helpers;

use App\Position;
use App\User;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @method static jsonResponse($null, int $HTTP_BAD_REQUEST, Repository|Application $config)
 */
class ResponseHelper
{

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse|Response
     */
    public static function sendResponse($result, $message)
    {

        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            'access'=>cache()->get('userPin')?[
                cache()->get('userPin')->position['name']==='admin',
                base64_encode(cache()->get('userPin')->position['access_permit']),
                ['name'=>cache()->get('userPin')->firstName . ' '. cache()->get('userPin')->lastName, 'email'=>cache()->get('userPin')->email]
                ]:''
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse|Response
     */
    public static function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
}
