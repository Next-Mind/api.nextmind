<?php

namespace App\Modules\AdminInvites\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class InvalidInviteException extends Exception
{
    use RenderToJson;

    protected $message = "Invalid Invite Token.";
    protected $code = 410;
}
