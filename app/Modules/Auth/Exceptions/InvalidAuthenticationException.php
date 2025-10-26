<?php

namespace App\Modules\Auth\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class InvalidAuthenticationException extends Exception
{
    use RenderToJson;

    protected $message = 'Invalid admin credentials.';

    protected $code = 401;
}
