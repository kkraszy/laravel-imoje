<?php

use Routegroup\Imoje\Payment\Lib\Api;
use Routegroup\Imoje\Payment\Lib\Paywall;
use Routegroup\Imoje\Payment\Lib\Utils;

beforeEach(function () {
    if (! env('PLAYGROUND', 0)) {
        $this->markTestSkipped('playground disabled');
    }
    
    config()->set('services.imoje', [
        'merchant_id' => env('IMOJE_MERCHANT_ID'),
        'service_id' => env('IMOJE_SERVICE_ID'),
        'service_key' => env('IMOJE_SERVICE_KEY'),
        'api_key' => env('IMOJE_API_KEY'),
        'env' => env('IMOJE_ENV'),
    ]);
});

it('is for playground purposes', function () {
    /** @var Api $api */
    $api = app(Api::class);
    /** @var Paywall $paywall */
    $paywall = app(Paywall::class);
    /** @var Utils $utils */
    $utils = app(Utils::class);
});
