<?php

use Illuminate\Support\Facades\Log;
use Routegroup\Imoje\Payment\DTO\Casts\TransactionDto;
use Routegroup\Imoje\Payment\Types\PaymentMethodCode;
use Routegroup\Imoje\Payment\Types\TransactionStatus;

it('casts a known payment method code', function () {
    $dto = new TransactionDto(['paymentMethodCode' => 'c2p']);

    expect($dto->paymentMethodCode)->toBeInstanceOf(PaymentMethodCode::class)
        ->and($dto->paymentMethodCode->getValue())->toBe('c2p');
});

it('does not throw on a payment channel imoje has not documented yet', function () {
    Log::shouldReceive('warning')->once();

    $dto = new TransactionDto([
        'id' => 'transaction-id',
        'status' => TransactionStatus::SETTLED,
        'paymentMethodCode' => 'brand_new_channel',
    ]);

    expect($dto->paymentMethodCode)->toBeNull()
        ->and($dto->status->getValue())->toBe(TransactionStatus::SETTLED);
});

it('still throws for enums with a value set imoje cannot extend', function () {
    expect(fn () => new TransactionDto(['status' => 'no_such_status']))
        ->toThrow(UnexpectedValueException::class);
});
