<?php

namespace App\Modules\Appointments\Enums;

enum AvailabilityStatus: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
    case Reserved = 'reserved';
    case Cancelled = 'cancelled';
}
