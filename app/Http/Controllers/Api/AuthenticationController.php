<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth endpoints
 * @method redirectPath()
 */
class AuthenticationController extends Controller
{

    /**
     * @return mixed
     */
    public function user()
    {
        return User::findOrFail(auth()->id());

    }
}
