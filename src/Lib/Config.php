<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Lib;

use Routegroup\Imoje\Payment\Types\Environment;

class Config
{
    /** @var string */
    public $merchantId;
    
    /** @var string */
    public $serviceId;
    
    /** @var string */
    public $serviceKey;
    
    /** @var string */
    public $apiKey;
    
    /** @var Environment */
    public $env;

    public function __construct(
        string $merchantId,
        string $serviceId,
        string $serviceKey,
        string $apiKey,
        Environment $env
    ) {
        $this->merchantId = $merchantId;
        $this->serviceId = $serviceId;
        $this->serviceKey = $serviceKey;
        $this->apiKey = $apiKey;
        $this->env = $env;
    }
}
