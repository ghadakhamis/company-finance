<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OUTSTANDING()
 * @method static static PAID()
 * @method static static OVERDUE()
 */
final class TransactionStatus extends Enum implements LocalizedEnum
{
    const OUTSTANDING = 1;
    const PAID        = 2;
    const OVERDUE     = 3;
}
