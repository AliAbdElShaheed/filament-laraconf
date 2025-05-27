<?php

namespace App\Enums;

enum TalkStatus : string
{

    case SUBMITTED = 'Submitted';
    case APPROVED = 'Approved';
    case REJECTED = 'Rejected';


    public function label(): string
    {
        return match ($this) {
            self::SUBMITTED => 'Submitted',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    } // end label

    public function getColor(): string
    {
        return match ($this) {
            self::SUBMITTED => 'primary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    } // end getColor


} // end enum
