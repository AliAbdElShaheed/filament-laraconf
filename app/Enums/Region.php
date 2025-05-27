<?php

namespace App\Enums;

enum Region : string
{
    case NorthAmerica = 'North America';
    case Europe = 'Europe';
    case Australia = 'Australia';
    case Antarctica = 'Antarctica';
    case MiddleEast = 'Middle East';
    case Online = 'Online';


    public function label(): string
    {
        return match ($this) {
            self::NorthAmerica => 'North America',
            self::Europe => 'Europe',
            self::Australia => 'Australia',
            self::Antarctica => 'Antarctica',
            self::MiddleEast => 'Middle East',
            self::Online => 'Online',
        };
    } // end label


    public function getColor(): string
    {
        return match ($this) {
            self::NorthAmerica => 'blue',
            self::Europe => 'green',
            self::Australia => 'yellow',
            self::Antarctica => 'white',
            self::MiddleEast => 'orange',
            self::Online => 'purple',
        };
    } // end getColor


} // end enum
