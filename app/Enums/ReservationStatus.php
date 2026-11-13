<?php
namespace App\Enums;

enum ReservationStatus:string{
    case ACTIVE ='active';
    case CONVERTED='converted';
    case EXPIRED= 'expired';
    case RELEASED= 'released';
}