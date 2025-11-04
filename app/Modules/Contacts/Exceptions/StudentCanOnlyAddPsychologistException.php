<?php

namespace App\Modules\Contacts\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class StudentCanOnlyAddPsychologistException extends Exception
{
    use RenderToJson;

    public function __construct(string $message = 'Estudantes só podem adicionar psicólogos à lista de contatos.')
    {
        parent::__construct($message, 422);
    }
}

