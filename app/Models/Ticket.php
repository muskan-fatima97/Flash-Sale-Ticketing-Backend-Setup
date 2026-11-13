<?php

namespace App\Models;

// use App\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable=[
        'order_id',
        'price',
        'event_id',
        
    ];

    public function casts():array{
        return [
            
            'price'=>'integer'
        ];
    }

    public function order():BelongsTo{
        return $this->belongsTo(Order::class);
    }
}
