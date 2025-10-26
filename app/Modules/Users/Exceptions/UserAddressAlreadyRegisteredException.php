<?php

namespace App\Modules\Users\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UserAddressAlreadyRegisteredException extends Exception
{
    use RenderToJson;

    protected $message = 'This address already registered.';
    protected $code = 409;
}
