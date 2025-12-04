<?php

use Illuminate\Support\Facades\Http;
use Routegroup\Imoje\Payment\DTO\Api\CancelPaymentDto;
use Routegroup\Imoje\Payment\DTO\Api\ChargeProfileDto;
use Routegroup\Imoje\Payment\DTO\Api\PaymentDto;
use Routegroup\Imoje\Payment\DTO\Api\RefundDto;
use Routegroup\Imoje\Payment\DTO\Api\TransactionDto;
use Routegroup\Imoje\Payment\DTO\Responses\CancelPaymentResponseDto;
use Routegroup\Imoje\Payment\DTO\Responses\ChargeProfileResponseDto;
use Routegroup\Imoje\Payment\DTO\Responses\PaymentResponseDto;
use Routegroup\Imoje\Payment\DTO\Responses\ProfileResponseDto;
use Routegroup\Imoje\Payment\DTO\Responses\RefundResponseDto;
use Routegroup\Imoje\Payment\DTO\Responses\TransactionResponseDto;
use Routegroup\Imoje\Payment\Lib\Api;

beforeEach(function () {
    $this->api = app(Api::class);
});

it('successfully calls create transaction', function () {
    Http::fake([
        $this->api->url->createTransactionUrl() => Http::response(TransactionResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->createTransaction(TransactionDto::factory()->make());
    expect($response)->toBeInstanceOf(TransactionResponseDto::class);
});

it('successfully calls create payment', function () {
    Http::fake([
        $this->api->url->createPaymentUrl() => Http::response(PaymentResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->createPayment(PaymentDto::factory()->make());
    expect($response)->toBeInstanceOf(PaymentResponseDto::class);
});

it('successfully calls cancel payment', function () {
    Http::fake([
        $this->api->url->createCancelPaymentUrl() => Http::response(CancelPaymentResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->cancelPayment(CancelPaymentDto::factory()->make());
    expect($response)->toBeInstanceOf(CancelPaymentResponseDto::class);
});

it('successfully calls refund', function () {
    Http::fake([
        $this->api->url->createRefundUrl('$transaction_id$') => Http::response(RefundResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->createRefund(RefundDto::factory()->make(), '$transaction_id$');
    expect($response)->toBeInstanceOf(RefundResponseDto::class);
});

it('successfully calls get profile', function () {
    Http::fake([
        $this->api->url->createProfileIdUrl('$profile_id$') => Http::response(ProfileResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->getProfile('$profile_id$');
    expect($response)->toBeInstanceOf(ProfileResponseDto::class);
});

it('successfully calls charge profile', function () {
    Http::fake([
        $this->api->url->createChargeProfileUrl() => Http::response(ChargeProfileResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->chargeProfile(ChargeProfileDto::factory()->make());
    expect($response)->toBeInstanceOf(ChargeProfileResponseDto::class);
});

it('successfully calls deactivate profile', function () {
    Http::fake([
        $this->api->url->createProfileIdUrl('$profile_id$') => Http::response(ProfileResponseDto::factory()->make()->toArray()),
    ]);
    $response = $this->api->deactivateProfile('$profile_id$');
    expect($response)->toBeInstanceOf(ProfileResponseDto::class);
});
