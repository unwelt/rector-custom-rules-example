<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

$name = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::RU();

?>
-----
<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Fixture;

$name = \Utils\Rector\Tests\Rector\PaillechatEnumMethodCallToEnumConstRector\Source\CountryEnum::RU;

?>