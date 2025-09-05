<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class InvalidInviteException extends Exception
{
    use RenderToJson;

    protected $message = "Invalid Invite Token.";
    protected $code = 410;
}
