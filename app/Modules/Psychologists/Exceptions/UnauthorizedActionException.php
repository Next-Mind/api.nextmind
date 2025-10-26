<?php

namespace App\Modules\Psychologists\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UnauthorizedActionException extends Exception
{
    use RenderToJson;
    protected $message = "Unauthorized action.";

    protected $code = 401;
}
