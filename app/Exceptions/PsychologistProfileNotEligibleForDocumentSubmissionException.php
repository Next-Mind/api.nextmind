<?php

namespace App\Exceptions;

use App\Traits\RenderToJson;
use Exception;

class PsychologistProfileNotEligibleForDocumentSubmissionException extends Exception
{
    use RenderToJson;

    protected $message = "Psychologist profile is not eligible for document submission. The profile status must be 'pending' or 'rejected'.";

    protected $code = 401;
}
