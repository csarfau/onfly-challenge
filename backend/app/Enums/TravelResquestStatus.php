<?php

namespace App\Enums;

enum TravelResquestStatus: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';
}
