<?php

namespace App\Exceptions;

use App\Enums\OrderStatus;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class InvalidOrderTransitions extends RuntimeException
{
    public function __construct(public readonly OrderStatus $from, public readonly OrderStatus $to)
    {
        parent::__construct("Invalid order transition from [{$from->value} to {$to->value}].");
    }

    public function render ():JsonResponse{
        return response()->json([
            'message' => $this->getMessage()
        ], 409);
    }
}