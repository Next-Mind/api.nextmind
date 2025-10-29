<?php

namespace App\Modules\HelpDesk\Exceptions;

use Exception;
use App\Traits\RenderToJson;

class TicketMessageNotBelongsToTicketException extends Exception
{
    use RenderToJson;

    protected $message = 'This message does not belong to the specified ticket.';

    protected $code = 422;
}
