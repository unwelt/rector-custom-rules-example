<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\CustomEnumFactory;
use Utils\Rector\Rector\PaillechatEnumToUnitEnumRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(CustomEnumFactory::class)->autowire();

    $services->set(PaillechatEnumToUnitEnumRector::class);

    $rectorConfig->ruleWithConfiguration(PaillechatEnumToUnitEnumRector::class, []);
};
