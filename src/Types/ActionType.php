<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static ActionType REDIRECT()
 * @method static ActionType TRANSFER()
 */
class ActionType extends Enum
{
    const REDIRECT = 'redirect';
    const TRANSFER = 'transfer';
}
