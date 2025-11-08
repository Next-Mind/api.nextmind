<?php

namespace App\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class InvalidClientPlatformHeaderException extends Exception
{
    use RenderToJson;

    protected $message = 'Invalid client platform.';

    protected $code = 401;
}
