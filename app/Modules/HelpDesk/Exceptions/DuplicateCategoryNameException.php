<?php

namespace App\Modules\HelpDesk\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class DuplicateCategoryNameException extends Exception
{
    use RenderToJson;

    protected $message = 'A category with this name already exists.';

    protected $code = 409;
}
