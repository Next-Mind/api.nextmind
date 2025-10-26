<?php

namespace App\Modules\AdminInvites\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class InvitationAlreadyExistsException extends Exception
{
    use RenderToJson;

    protected $message = 'User Already Admin';
    protected $code = 422;
}
