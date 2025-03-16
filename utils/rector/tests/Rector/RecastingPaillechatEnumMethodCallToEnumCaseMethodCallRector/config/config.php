<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Utils\Rector\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector::class);

    $rectorConfig->ruleWithConfiguration(RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector::class, []);
};
