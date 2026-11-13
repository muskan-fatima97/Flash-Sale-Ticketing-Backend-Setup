<?php

namespace App\Exception;

use App\Models\Event;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class ReservationException extends RunTimeException implements ShouldntReport{
    public function __construct(string $message, public readonly int $status, public readonly array $headers = [],)
    {
        parent::__construct($message);
    }

    public static function notEnoughTickets():self{
        return new self ("Not enough tickets available", 409);
    }

    public static function saleNotStarted(Event $event):self{
        $wait=max(1,(int) ceil(now()->diffInSeconds($event->sale_starts_at)));
        return new self ("Sale has not started yet.", 403, [
            'Retry-After'=>$wait
        ]);
    }

    public static function expired():self{
        return new self ("Reservation has expired.", 410);
    } 

    public static function notActive():self{
        return new self ("Reservation is not active.", 409);
    }

    public static function idempotencyKeyReused():self{
        return new self ("Idempotency key has already been used.", 422);
    }

    public function render():JsonResponse{
        return response()->json(['message'=>$this->getMessage()], $this->status, $this->headers);
    }
}