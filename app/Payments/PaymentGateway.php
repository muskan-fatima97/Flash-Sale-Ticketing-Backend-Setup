<?php

namespace App\Payments;

interface PaymentGateway{
    public function charge(int $amount,string $token):array;

    public function find (string $reference): ?PaymentResult ;
}