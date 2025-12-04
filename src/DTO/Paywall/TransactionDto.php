<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\DTO\Paywall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Routegroup\Imoje\Payment\DTO\BaseDto;
use Routegroup\Imoje\Payment\Factories\Paywall\TransactionDtoFactory;
use Routegroup\Imoje\Payment\Lib\Config;
use Routegroup\Imoje\Payment\Lib\Utils;
use Routegroup\Imoje\Payment\Types\Currency;
use Routegroup\Imoje\Payment\Types\HashMethod;

/**
 * @property-read int $amount
 * @property-read Currency $currency
 * @property-read string $orderId
 * @property-read string $customerId
 * @property-read string $customerFirstName
 * @property-read string $customerLastName
 * @property-read string $serviceId
 * @property-read string $merchantId
 * @property-read string $signature
 * @property-read string $customerEmail
 * @property-read string $customerPhone
 * @property-read string $urlSuccess
 * @property-read string $urlFailure
 * @property-read string $urlReturn
 * @property-read string $orderDescription
 * @property-read string $visibleMethod
 * @property-read int $validTo
 *
 * @method static TransactionDtoFactory factory($count = null, $state = [])
 */
class TransactionDto extends BaseDto
{
    use HasFactory;

    protected array $casts = [
        'amount' => 'int',
    ];

    /**
     * @param array $attributes
     * @param HashMethod $hashMethod
     */
    public function __construct(
        $attributes = [],
        HashMethod $hashMethod = null
    ) {
        if ($hashMethod === null) {
            $hashMethod = HashMethod::SHA256();
        }
        $config = app(Config::class);
        $utils = app(Utils::class);

        $attributes = array_merge_recursive([
            'serviceId' => $config->serviceId,
            'merchantId' => $config->merchantId,
        ], $attributes);

        $attributes['signature'] = $utils->createSignature($attributes, $hashMethod);

        parent::__construct($attributes);
    }

    protected static function newFactory(): TransactionDtoFactory
    {
        return TransactionDtoFactory::new();
    }
}
