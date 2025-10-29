<?php

namespace App\Modules\Contacts\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class ContactAlreadyExistsException extends Exception
{
    use RenderToJson;

    public function __construct(string $message = 'Contato já foi adicionado anteriormente.')
    {
        parent::__construct($message, 422);
    }
}

