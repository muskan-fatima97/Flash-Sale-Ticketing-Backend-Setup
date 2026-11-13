<?php

namespace App\Models;

use App\Enums\OrderStatus;  
use App\Exceptions\InvalidOrderTransitions;  
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order extends Model
{
    protected $fillable=[
        'reservation_id',
        'status',
        'user_id',
        'event_id',
        'idempotency_key',
        'quantity',
        'amount',
        'payment_reference',

    ];

    protected function casts():array{
        return [
            'reservation_id'=>'integer',
            'status'=>OrderStatus::class,
            'user_id'=>'integer',
            'event_id'=>'integer',
            // 'idempotency_key'=>'string',
            'quantity'=>'integer',
            'amount'=>'integer',
            // 'payment_reference'=>'string'
        ];
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
public function event():BelongsTo{
        return $this->belongsTo(Event::class);
    }

    public function reservation():BelongsTo{
        return $this->belongsTo(Reservation::class);
    }

    public function tickets():HasMany{
        return $this->hasMany(Ticket::class);
    }
    public function transition():HasMany{
        return $this->hasMany(OrderStatus::class);
    }

    public function transitionTo(OrderStatus $to, string $actor, string $reason):void{
        DB::transaction(function() use($to, $actor, $reason){
            $from=static::whereKey($this->getKey())->lockForUpdate()->value('status');
           if(! $from->canTransitionTo($to)){
                throw new InvalidOrderTransitions($from, $to);
            }
            $this->forceFill([
                'status'=>$to
            ])->save();
            $this->transition()->create([
                'from_status'=>$from,
                'to_status'=>$to,
                'actor'=>$actor,
                'reason'=>$reason
            ]);
            
        });
    }

}
