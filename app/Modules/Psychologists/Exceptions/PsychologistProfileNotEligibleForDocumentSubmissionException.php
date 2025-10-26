<?php

namespace App\Modules\Psychologists\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class PsychologistProfileNotEligibleForDocumentSubmissionException extends Exception
{
    use RenderToJson;

    protected $message = "Psychologist profile is not eligible for document submission. The profile status must be 'pending' or 'rejected'.";

    protected $code = 401;
}
