<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class PingController extends Controller
{
    /**
     * PingController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'success' => true,
            'timestamp' => Carbon::now()->toTimeString()
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function sendMsg()
    {
        $data = ['link' => 'http://www.inneli.com'];
        Mail::send('emails.notification', $data, function ($message) {
            $message->from('no-reply@inneli.com', 'Inneli APP');
            $message->to('miguel.cabreja90@gmail.com');
            $message->subject('Notification');
        });

        return response()->json([
            'status' => 'ok',
            'success' => true,
            'message' => 'Send message ok',
            'timestamp' => Carbon::now()->toTimeString()
        ]);
    }
}
