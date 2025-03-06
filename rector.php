<?php

declare(strict_types=1);

use RectorExample\PaillechatEnum;
use Rector\Config\RectorConfig;
use Utils\Rector\Rector\CustomEnumFactory;
use Utils\Rector\Rector\PaillechatEnumFqcnValueObject;
use Utils\Rector\Rector\PaillechatEnumMethodCallToEnumConstRector;
use Utils\Rector\Rector\PaillechatEnumToUnitEnumRector;
use Utils\Rector\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector;



return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/**/_generated/*',
    ]);
    $services = $rectorConfig->services();
    $services->set(CustomEnumFactory::class)->autowire();

    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/test/DataMatrixService_KernelTestDebugContainer.xml');

    // Run to refactor target enum
    $enumToRefactor = PaillechatEnumFqcnValueObject::fromString(PaillechatEnum::class);

    $rectorConfig->ruleWithConfiguration(PaillechatEnumToUnitEnumRector::class, [$enumToRefactor]);
    $rectorConfig->ruleWithConfiguration(PaillechatEnumMethodCallToEnumConstRector::class, [$enumToRefactor]);
    $rectorConfig->ruleWithConfiguration(RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector::class, [$enumToRefactor]);

    $rectorConfig->parallel(seconds: 360);
};
