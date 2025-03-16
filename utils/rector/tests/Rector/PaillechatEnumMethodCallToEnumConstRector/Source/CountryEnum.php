<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source;

use Paillechat\Enum\Enum;

final class CountryEnum extends Enum
{
    public const RU = 'ru';
    public const BY = 'by';
}