<?php declare(strict_types=1);

namespace Rector\Php55\Tests\Rector\String_\StringClassNameToClassConstantRector;

use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class StringClassNameToClassConstantRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    /**
     * @return string[]
     */
    public function provideDataForTest(): iterable
    {
        yield [__DIR__ . '/Fixture/fixture.php.inc'];
        yield [__DIR__ . '/Fixture/skip_error.php.inc'];
        yield [__DIR__ . '/Fixture/skip_sensitive.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return StringClassNameToClassConstantRector::class;
    }
}
