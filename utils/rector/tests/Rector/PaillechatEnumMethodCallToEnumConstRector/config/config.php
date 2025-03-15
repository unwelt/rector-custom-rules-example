<?php

declare(strict_types=1);

use Paillechat\Enum\Enum;
use Rector\Config\RectorConfig;
use Utils\Rector\Rector\CustomEnumFactory;
use Utils\Rector\Rector\PaillechatEnumMethodCallToEnumConstRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(PaillechatEnumMethodCallToEnumConstRector::class);

    $rectorConfig->ruleWithConfiguration(PaillechatEnumMethodCallToEnumConstRector::class, []);
    $rectorConfig->importNames(false);
    $rectorConfig->importShortClasses(false);
};
