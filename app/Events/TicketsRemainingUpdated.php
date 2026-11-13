<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contacts\Broadcasting\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

 class TicketsRemainingUpdated implements ShouldBroadcast, ShouldDispatchAfterCommit{

 use Dispatchable,  SerializesModels;
    public function __construct(
        public int $eventId,
        public int $ticketsSold,
        public int $ticketsRemaining,
    ){}

    public function broadcastOn():array{
        return [new Channel('events.{$this->eventId}')];
    }

    public function broadcastAs():string{
        return 'TicketsRemainingUpdated';
    }
}