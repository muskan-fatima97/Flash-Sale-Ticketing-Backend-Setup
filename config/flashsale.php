<?php

return [
    'hold_minutes'=>(int)env('FLASHSALE_HOLD_MINUTES', 10),
    'max_tickets_per_hold'=> 6,
    'reservation_attempts_per_minutes'=>(int)env('FLASHSALE_RESERVATION_ATTEMPTS_PER_MINUTES', 5),
    'max_concuratent_reservations'=>(int)env('FLASHSALE_MAX_CONCURRENT_RESERVATIONS', 50),
    'availability_cache'=>[
        'fresh_seconds'=>5,
        'stale_seconds'=>60,

    ],
    'payment_grace_seconds'=>120,
    'fake_gateway'=>[
        'decline_rate'=>(float)env('FAKE_GATEWAY_DECLINE_RATE', 0.15),
        'time_out_rate'=>(float)env('FAKE_GATEWAY_TIMEOUT_RATE', 0.15),
    ],
];
