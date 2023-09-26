<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\DTO\Requests;

use Routegroup\Imoje\Payment\DTO\BaseDto;
use Routegroup\Imoje\Payment\Lib\Config;
use Routegroup\Imoje\Payment\Lib\Utils;
use Routegroup\Imoje\Payment\Types\HashMethod;

/**
 * @property-read string $merchantId
 * @property-read string $serviceId
 * @property-read int $amount
 * @property-read string $currency
 * @property-read string $orderId
 * @property-read string $customerId
 * @property-read string $customerFirstName
 * @property-read string $customerLastName
 * @property-read string $customerEmail
 * @property-read string $signature
 * @property-read string $customerPhone
 * @property-read string $orderDescription
 * @property-read string $urlSuccess
 * @property-read string $urlFailure
 * @property-read string $urlReturn
 * @property-read string $urlCancel
 * @property-read string $widgetType
 * @property-read int $validTo
 */
class OneClickRequestDto extends BaseDto
{
    protected array $casts = [
        'amount' => 'int',
        'validTo' => 'int',
    ];

    public function __construct(
        #[ArrayShape([
            // Required
            'merchantId' => 'string',
            'serviceId' => 'string',
            'amount' => 'int',
            'currency' => 'string',
            'orderId' => 'string',
            'customerId' => 'string',
            'customerFirstName' => 'string',
            'customerLastName' => 'string',
            'customerEmail' => 'string',
            'signature' => 'string',
            // Optional
            'customerPhone' => 'string',
            'orderDescription' => 'string',
            'urlSuccess' => 'string',
            'urlFailure' => 'string',
            'urlReturn' => 'string',
            'urlCancel' => 'string',
            'widgetType' => 'string',
            'validTo' => 'int',
        ])] $attributes = [],
        HashMethod $hashMethod = HashMethod::SHA256
    ) {
        $config = app(Config::class);
        $utils = app(Utils::class);

        $attributes = array_merge_recursive([
            'serviceId' => $config->serviceId,
            'merchantId' => $config->merchantId,
            'widgetType' => 'oneclick',
        ], $attributes);

        $attributes['signature'] = $utils->createSignature($attributes, $hashMethod);

        parent::__construct($attributes);
    }
}