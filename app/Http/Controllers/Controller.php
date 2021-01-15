<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var User
     */
    protected $userPin;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
    }


    /**
     * @return User
     */
    public function getUserPin(): User
    {
        return $this->userPin;
    }

    /**
     * @param  User|null  $userPin
     */
    public function setUserPin(User $userPin = null): void
    {
        $this->userPin = $userPin;
    }

}
