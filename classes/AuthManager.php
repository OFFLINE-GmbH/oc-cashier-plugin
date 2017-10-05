<?php namespace OFFLINE\Cashier\Classes;

use OFFLINE\Cashier\Models\User;
use RainLab\User\Classes\AuthManager as RainAuthManager;

class AuthManager extends RainAuthManager
{
    protected $userModel = User::class;
}
