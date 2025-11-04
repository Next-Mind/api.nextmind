<?php

namespace App\Modules\Contacts\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class CannotAddSelfAsContactException extends Exception
{
    use RenderToJson;

    public function __construct(string $message = 'Você não pode adicionar a si mesmo como contato.')
    {
        parent::__construct($message, 422);
    }
}

