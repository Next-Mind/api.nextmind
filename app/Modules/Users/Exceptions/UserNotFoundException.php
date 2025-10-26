<?php

namespace App\Modules\Users\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UserNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'User Not Found.';
    protected $code = 404;
}
