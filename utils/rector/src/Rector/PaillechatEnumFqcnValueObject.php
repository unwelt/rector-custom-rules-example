<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use Paillechat\Enum\Enum;
use Webmozart\Assert\Assert;

final class PaillechatEnumFqcnValueObject
{
    public function __construct(
        public readonly string $fqcn
    )
    {}

    public function __toString(): string
    {
        return $this->fqcn;
    }

    public static function fromString(string $fqcn): self
    {
        Assert::classExists($fqcn);
        Assert::isAOf($fqcn, Enum::class);

        return new self($fqcn);
    }
}
