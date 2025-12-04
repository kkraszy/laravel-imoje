<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static WidgetType ONECLICK()
 * @method static WidgetType RECURRING()
 * @method static WidgetType ECOM3DS()
 */
class WidgetType extends Enum
{
    const ONECLICK = 'oneclick';
    const RECURRING = 'recurring';
    const ECOM3DS = 'ecom3ds';
}
