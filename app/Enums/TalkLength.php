<?php

namespace App\Enums;

enum TalkLength : string
{
    case LIGHTNING = 'lightning - 15 minutes';
    case NORMAL = 'normal - 30 minutes';
    case KEYNOTE = 'keynote - 45 minutes';


    public function getLabel(): string
    {
        return match ($this) {
            self::LIGHTNING => 'Lightning Talk (15 minutes)',
            self::NORMAL => 'Normal Talk (30 minutes)',
            self::KEYNOTE => 'Keynote Talk (45 minutes)',
        };
    } // end label


    public function getColor(): string
    {
        return match ($this) {
            self::LIGHTNING => 'warning',
            self::NORMAL => 'primary',
            self::KEYNOTE => 'success',
        };
    } // end getColor


    public function getIcon(): string
    {
        return match ($this) {
            self::LIGHTNING => 'heroicon-o-bolt',
            self::NORMAL => 'heroicon-o-megaphone',
            self::KEYNOTE => 'heroicon-o-star',
        };
    } // end getIcon



} // end enum
