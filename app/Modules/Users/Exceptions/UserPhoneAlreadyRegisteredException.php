<?php

namespace App\Modules\Users\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class UserPhoneAlreadyRegisteredException extends Exception
{
    use RenderToJson;

    protected $message = 'This phone already registered.';
    protected $code = 409;
}
