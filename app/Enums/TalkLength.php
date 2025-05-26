<?php

namespace App\Enums;

enum TalkLength : string
{
    case LIGHTNING = 'lightning - 15 minutes';
    case NORMAL = 'normal - 30 minutes';
    case KEYNOTE = 'keynote - 45 minutes';

} // end enum
