<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\PaillechatEnumMethodCallToEnumConstRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(PaillechatEnumMethodCallToEnumConstRector::class)->autowire();

    $rectorConfig->ruleWithConfiguration(PaillechatEnumMethodCallToEnumConstRector::class, []);
};
