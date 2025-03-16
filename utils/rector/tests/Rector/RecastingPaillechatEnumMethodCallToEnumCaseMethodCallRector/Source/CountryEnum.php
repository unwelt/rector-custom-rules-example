<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source;

use Paillechat\Enum\Enum;

/**
 * @method static static RU()
 * @method static static BY()
 */
final class CountryEnum extends Enum
{
    private const RU = 'ru';
    private const BY = 'by';
}