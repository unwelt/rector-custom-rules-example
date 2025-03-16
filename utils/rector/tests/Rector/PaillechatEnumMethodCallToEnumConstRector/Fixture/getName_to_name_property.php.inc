<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

use Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\SimpleClass;

$ruCountryName = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::RU()->getName();
$simpleClassName = (new SimpleClass())->getName();

?>
-----
<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

use Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\SimpleClass;

$ruCountryName = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::RU->name;
$simpleClassName = (new SimpleClass())->getName();

?>