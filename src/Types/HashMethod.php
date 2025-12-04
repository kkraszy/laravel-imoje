<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static HashMethod SHA224()
 * @method static HashMethod SHA256()
 * @method static HashMethod SHA384()
 * @method static HashMethod SHA512()
 */
class HashMethod extends Enum
{
    const SHA224 = 'sha224';
    const SHA256 = 'sha256';
    const SHA384 = 'sha384';
    const SHA512 = 'sha512';
}
