<?php

namespace App\Enums;

enum TalkStatus : string
{

    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';


} // end enum
