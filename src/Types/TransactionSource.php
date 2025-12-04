<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionSource API()
 * @method static TransactionSource WEB()
 */
class TransactionSource extends Enum
{
    const API = 'api';
    const WEB = 'web';
}
