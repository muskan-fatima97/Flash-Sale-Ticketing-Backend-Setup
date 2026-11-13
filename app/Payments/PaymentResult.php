<?php

namespace App\Payments;

final readonly class PaymentResult{
    public function __construct(
        public bool $successfull,
        public string $message,
    ){}

    public static function succeeded():self{
        return new self(true, 'Payment captured');
    }

    public static function declined(string $message='card declined'):self{
        return new self(false, $message);
    }
}