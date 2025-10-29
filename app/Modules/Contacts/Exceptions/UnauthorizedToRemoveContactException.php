<?php

namespace App\Modules\Contacts\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UnauthorizedToRemoveContactException extends Exception
{
    use RenderToJson;

    public function __construct(string $message = 'Sem autorização para remover este contato.')
    {
        parent::__construct($message, 403);
    }
}

