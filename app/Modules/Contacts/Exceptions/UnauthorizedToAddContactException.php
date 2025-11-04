<?php

namespace App\Modules\Contacts\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class UnauthorizedToAddContactException extends Exception
{
    use RenderToJson;

    public function __construct(string $message = 'Usuário não autorizado a adicionar contatos.')
    {
        parent::__construct($message, 403);
    }
}

