<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @see RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRector
 */
final class RecastingPaillechatEnumMethodCallToEnumCaseMethodCallRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(string $fixtureFilePath): void
    {
        $this->doTestFile($fixtureFilePath);
    }

    private function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/config.php';
    }
}
