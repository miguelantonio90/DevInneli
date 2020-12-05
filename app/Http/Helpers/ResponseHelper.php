<?php

namespace App\Http\Helpers;

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
//        var_dump(cache()->get('userPin')['position']);
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
            'access'=>cache()->get('userPin')?base64_encode(cache()->get('userPin')->position['access_permit']):''
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param  array  $errorMessages
     * @param  int  $code
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
