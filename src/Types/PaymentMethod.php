<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static PaymentMethod CARD()
 * @method static PaymentMethod PAY_BY_LINK()
 * @method static PaymentMethod BLIK()
 * @method static PaymentMethod PAYLATER()
 * @method static PaymentMethod LEASE()
 * @method static PaymentMethod WIRE_TRANSFER()
 * @method static PaymentMethod ING()
 * @method static PaymentMethod WALLET()
 * @method static PaymentMethod VISA_MOBILE()
 * @method static PaymentMethod IMOJE_INSTALLMENTS()
 */
class PaymentMethod extends Enum
{
    const CARD = 'card'; // Visa, MasterCard, Visa Mobile etc
    const PAY_BY_LINK = 'pbl'; // Online transfer
    const BLIK = 'blik';
    const PAYLATER = 'imoje_paylater'; // Twisto, PayPo, PragmaGO etc
    const LEASE = 'lease'; // ING Lease Now
    const WIRE_TRANSFER = 'wt'; // Wire transfer
    const ING = 'ing';
    const WALLET = 'wallet'; // Electronic wallets
    const VISA_MOBILE = 'visa_mobile';
    const IMOJE_INSTALLMENTS = 'imoje_installments';
}
