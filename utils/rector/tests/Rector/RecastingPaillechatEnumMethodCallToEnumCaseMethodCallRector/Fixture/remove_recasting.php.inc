<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Fixture;

$string1 = (string) \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\CountryEnum::RU();
$string2 = (string) \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\CountryEnum::BY()->getName();
$string3 = (string) \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\NativeEnum::NATIVE;

?>
-----
<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Fixture;

$string1 = \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\CountryEnum::RU->name;
$string2 = \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\CountryEnum::BY->name;
$string3 = \Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector\Source\NativeEnum::NATIVE->name;

?>