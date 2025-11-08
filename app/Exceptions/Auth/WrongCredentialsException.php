<?php

namespace App\Exceptions\Auth;

class WrongCredentialsException extends \Exception
{
    protected $message = 'Wrong credentials';
}
