<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Reservation extends Model
{
    protected $fillable=[
        'event_id',
        'user_id',
        'quantity',
        'status',
        'expires_at'
    ];

    protected function casts():array{
        return [
            'event_id'=>'integer',
            'user_id'=>'integer',
            'quantity'=>'integer',
            'status'=>ReservationStatus::class,
            'expires_at'=>'datetime'
        ];
    }

    public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function order():HasOne{
        return $this->hasOne(Order::class);
    }

    public function isExpired():bool{
        return ! $this->expires_at->isFuture();
    }
}
