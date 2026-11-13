<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    public function allowedTranstions():array{
        return match($this){
            self::PENDING => [self::PAID, self::FAILED, self::CANCELLED],
            self::PAID => [self::REFUNDED],
            self::FAILED => [],
            self::REFUNDED => [],
            self::CANCELLED => [],
            
        };
    }

public function canTransitionTo(self $next):bool{
    return in_array($next, $this->allowedTranstions(),true);
}
}

