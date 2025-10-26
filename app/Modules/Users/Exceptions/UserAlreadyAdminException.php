<?php

namespace App\Modules\Users\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UserAlreadyAdminException extends Exception
{
    use RenderToJson;

    protected $message = 'User Already Admin';
    protected $code = 409;
}
