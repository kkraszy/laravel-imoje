<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static Currency PLN()
 * @method static Currency EUR()
 * @method static Currency CZK()
 * @method static Currency GBP()
 * @method static Currency USD()
 * @method static Currency UAH()
 * @method static Currency HRK()
 * @method static Currency HUF()
 * @method static Currency SEK()
 * @method static Currency RON()
 * @method static Currency CHF()
 * @method static Currency BGN()
 */
class Currency extends Enum
{
    const PLN = 'PLN';
    const EUR = 'EUR';
    const CZK = 'CZK';
    const GBP = 'GBP';
    const USD = 'USD';
    const UAH = 'UAH';
    const HRK = 'HRK';
    const HUF = 'HUF';
    const SEK = 'SEK';
    const RON = 'RON';
    const CHF = 'CHF';
    const BGN = 'BGN';
}
