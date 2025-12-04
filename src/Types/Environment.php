<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static Environment PRODUCTION()
 * @method static Environment SANDBOX()
 */
class Environment extends Enum
{
    const PRODUCTION = 'production';
    const SANDBOX = 'sandbox';

    public function apiUrl(): string
    {
        if ($this->equals(self::PRODUCTION())) {
            return 'https://api.imoje.pl/v1';
        }
        return 'https://sandbox.api.imoje.pl/v1';
    }

    public function paywallUrl(?Lang $lang = null): string
    {
        if ($lang) {
            $langValue = $lang->getValue();
            if ($this->equals(self::PRODUCTION())) {
                return "https://paywall.imoje.pl/{$langValue}/payment";
            }
            return "https://sandbox.paywall.imoje.pl/{$langValue}/payment";
        }

        if ($this->equals(self::PRODUCTION())) {
            return 'https://paywall.imoje.pl/payment';
        }
        return 'https://sandbox.paywall.imoje.pl/payment';
    }

    public function widgetUrl(): string
    {
        if ($this->equals(self::PRODUCTION())) {
            return 'https://paywall.imoje.pl/js/widget.min.js';
        }
        return 'https://sandbox.paywall.imoje.pl/js/widget.min.js';
    }

    public function cdnUrl(): string
    {
        return 'https://data.imoje.pl';
    }
}
