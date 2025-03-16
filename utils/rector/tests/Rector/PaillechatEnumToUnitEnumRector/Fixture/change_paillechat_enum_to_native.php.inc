<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumToUnitEnumRector\Fixture;

use Paillechat\Enum\Enum;

/**
 * @method static static DRAFT()
 * @method static static PUBLISHED()
 * @method static static ARCHIVED()
 */
class StatusEnum extends Enum
{
    private const DRAFT = 'draft';
    private const PUBLISHED = 'published';
    private const ARCHIVED = 'archived';
}

?>
    -----
    <?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumToUnitEnumRector\Fixture;

use Paillechat\Enum\Enum;

enum StatusEnum
{
    case DRAFT;
    case PUBLISHED;
    case ARCHIVED;
}

?>