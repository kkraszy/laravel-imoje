<?php

declare(strict_types=1);

namespace Routegroup\Imoje\Payment\Types;

use MyCLabs\Enum\Enum;

/**
 * @method static TransactionStatus NEW()
 * @method static TransactionStatus AUTHORIZED()
 * @method static TransactionStatus PENDING()
 * @method static TransactionStatus SUBMITTED_FOR_SETTLEMENT()
 * @method static TransactionStatus REJECTED()
 * @method static TransactionStatus SETTLED()
 * @method static TransactionStatus ERROR()
 * @method static TransactionStatus CANCELLED()
 * @method static TransactionStatus REFUND()
 */
class TransactionStatus extends Enum
{
    const NEW = 'new';
    const AUTHORIZED = 'authorized';
    const PENDING = 'pending';
    const SUBMITTED_FOR_SETTLEMENT = 'submitted_for_settlement';
    const REJECTED = 'rejected';
    const SETTLED = 'settled';
    const ERROR = 'error';
    const CANCELLED = 'cancelled';
    const REFUND = 'refund';

    public function canChange(TransactionStatus $newStatus): bool
    {
        if ($newStatus->equals(self::NEW())) {
            return false;
        }

        if ($this->equals(self::SETTLED()) && $newStatus->equals(self::REFUND())) {
            return true;
        }

        if (
            $this->equals(self::ERROR()) ||
            $this->equals(self::CANCELLED()) ||
            $this->equals(self::REJECTED()) ||
            $this->equals(self::SETTLED()) ||
            $this->equals(self::REFUND())
        ) {
            return false;
        }

        if ($this->equals($newStatus)) {
            return false;
        }

        return true;
    }
}
