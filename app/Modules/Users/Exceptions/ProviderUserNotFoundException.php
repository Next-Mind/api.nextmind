<?php

namespace App\Modules\Users\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class ProviderUserNotFoundException extends Exception
{
    use RenderToJson;

    protected $message = 'Provider User Not Found.';
    protected $code = 400;
}
