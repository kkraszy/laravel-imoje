<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Contracts;

/**
 * Marks an enum whose set of values is controlled by imoje and may be extended
 * without notice (new payment channels appear in notifications before they land
 * in the documentation).
 *
 * When casting an incoming payload, BaseDto turns an unknown value of such an
 * enum into null and logs a warning, instead of throwing and failing the whole
 * notification.
 */
interface LenientEnum
{
}
