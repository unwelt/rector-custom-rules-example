<?php

namespace Utils\Rector\Tests\Rector\PaillechatEnumToUnitEnumRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Utils\Rector\Rector\PaillechatEnumToUnitEnumRector;

/**
 * @see PaillechatEnumToUnitEnumRector
 */
final class PaillechatEnumToUnitEnumRectorTest extends AbstractRectorTestCase
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
