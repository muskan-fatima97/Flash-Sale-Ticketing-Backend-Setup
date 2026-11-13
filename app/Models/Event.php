<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Event extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'sale_starts_at',
        'price',
        'total_tickets',
        'tickets_available',
        'tickets_sold'
    ];

protected function casts():array{
    return [
        'sale_starts_at'=>'datetime',
        'price'=>'integer',
        'total_tickets'=>'integer',
        'tickets_available'=>'integer',
        'tickets_sold'=>'integer'
    ];
}

public function reservations():HasMany{
    return $this->hasMany(Reservation::class);
}

public function saleHasStarted():bool{
    return ! $this->sale_starts_at->isFuture();
}
}
