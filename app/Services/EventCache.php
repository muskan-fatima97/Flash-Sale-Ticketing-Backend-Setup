<?php

namespace App\Services;

use App\Events\TicketsRemainingUpdated;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Cache\LockTimeoutException;
class EventCache{
    public function get (int $eventId): ?array {
        $key=$this->key($eventId);

        $cached=Cache::get($key);
        if($cached && $cached ['fresh_until']>now()->getTimestamp()){
            return $cached ['data'];
        }
        $lock=Cache::lock("{$key}:rebuilt",10);

        if($cached) {
            if(!$lock->get()){
                return $cached ['data'];
            }
            try{
                return $this->refresh($eventId);
            }
            finally{
                $lock->release();
            }

        }
        try{
            return $lock->block(3, function() use ($key,$eventId){
                $built=Cache::get($key);
                return $built ? $built ['data'] : $this->refresh($eventId);
            });
        }
        catch(lockTimeoutException ){
            abort(503,'busy, Retry.',['Retry- after '=>1]);
        }
    }
    public function refresh (int $eventId): ?array{
        $event=Event::query()->find($eventId);
        $data=$event ? [
            'id'=>$event->id,
            'name'=>$event->name,
            'price'=>$event->price,
            'tickets_remaining'=>$event->tickets_available,
            'tickets_sold'=>$event->tickets_sold,
            'sale_starts_at'=>$event->sale_starts_at->toIsO8601String(),
            'tickets_total'=>$event->total_tickets,
            'sale_ends_at'=>$event->sale_ends_at
        ] : null;
        Cache::put($this->key($eventId),[
            'data'=>$data,
            'fresh_until'=>now()->addSeconds(config('flashsale.availability_cache.fresh_seconds'))->getTimestamp()
        ],config('flashsale.availability_cache.stale_seconds'));
        return $data;
    }

    public function refreshAfterCommit(int $eventId):void{
        DB::afterCommit(function() use($eventId){
            $data=$this->refresh($eventId);
            if($data){
                TicketsRemainingUpdated::dispatch($data['tickets_remaining'],$data['tickets_sold'], $eventId);
            }
        });
    }

    private function key(int $eventId):string{
        return "event:{$eventId}:summary";
    }
}