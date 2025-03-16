<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source;

final class SimpleClass
{
    public function getName(): string
    {
        return __CLASS__;
    }
}