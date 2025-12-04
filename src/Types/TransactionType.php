<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionType REFUND()
 * @method static TransactionType SALE()
 */
class TransactionType extends Enum
{
    const REFUND = 'refund';
    const SALE = 'sale';
}
