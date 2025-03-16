<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

$name = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::getConstList();

?>
-----
<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

$name = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::cases();

?>