<?php

namespace App\Payments;
// use App\Payments\PaymentGateway;
use Illuminate\Contracts\Cache\Repository;

class FakePaymentGateway implements PaymentGateway{
    public const SUCCEED='charge_succeed';
    public const DECLINE='charge_declined';

    public const TIME_OUT='timeout_charge';
public const TIME_OUT_AFTER_CHARGE='timeout_after_charge';

private ?string $forcedOutcome=null;

public function __construct(
    private readonly Repository $store,
    private readonly float $declineRate,
        private readonly float $timeoutRate,

){}

public function charge(string $reference,int $amount): PaymentResult{
    $this->store->increment('fake-gateway:calls');
if($existing=$this->find($reference)){
        return $existing;
    }

    return match ($this->forcedOutcome ?? $this->randomOutcome()){
        self ::SUCCEED=>$this->remember($reference,PaymentResult::succeeded()),
        self ::DECLINE=>$this->remember($reference,PaymentResult::declined()),
        self ::TIME_OUT=>throw new PaymentTimeoutException('Gateway did not respond.'),
        self ::TIME_OUT_AFTER_CHARGE=>$this->timeoutAfterCharging($reference),
    };
}

public function find(string $reference): ?  PaymentResult{
    $charge=$this->store->get("fake-gateway:charge:{$reference}");
    return $charge ? new PaymentResult($charge['successfull'],$charge['message']) : null;
}


public function alwaysRespondwith(?string $outcome):void{
    $this->forcedOutcome=$outcome;

}

public function callCount():int{
    return (int) $this->store->get('fake-gateway:calls',0);
}

public function randomOutcome():string{
    $roll=random_int(0,9999)/10000;
    return match(true){
        $roll<$this->timeoutRate=>random_int(0,1) ? self::TIME_OUT : self::TIME_OUT_AFTER_CHARGE,
        $roll<$this->timeoutRate + $this->declineRate=>self::DECLINE,
        default=>self::SUCCEED
    };
}

private function remember(string $reference, PaymentResult $result): PaymentResult{
$this->store->forever("fake-gateway:charge:{$reference}",[
    'successfull'=>$result->successfull,
    'message'=>$result->message
]);

return $result;
}

private function timeoutAfterCharging(string $reference):never{
    $this->remember($reference,PaymentResult::succeeded());
    throw new PaymentTimeoutException('Gateway did not respond after charging.');
}

}