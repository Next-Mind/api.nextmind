<?php

namespace App\Modules\HelpDesk\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class CategoryInUseException extends Exception
{
    use RenderToJson;

    protected $message = 'Deletion not allowed: subcategories and/or tickets are linked.';

    protected $code = 409;
}
