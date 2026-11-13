<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Order_Status_Transition extends Model
{
    public const UPDATED_AT = null;

    protected $fillable=[
        'order_id',
        'from_status',
        'to_status',
        'actor',
        'reason'
    ];

    public function casts():array{
        return[
            'from_status'=>OrderStatus::class,
            'to_status'=>OrderStatus::class,

        ];
    }

    public function order():BelongsTo{
        return $this->belongsTo(Order::class);
    }
}
