<?php

namespace App\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class InvalidAdminCredencialsException extends Exception
{
     use RenderToJson;

    protected $message = 'Invalid admin credentials.';

    protected $code = 401;
}
