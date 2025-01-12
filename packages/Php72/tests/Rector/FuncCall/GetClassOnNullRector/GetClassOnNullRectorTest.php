<?php declare(strict_types=1);

namespace Rector\Php72\Tests\Rector\FuncCall\GetClassOnNullRector;

use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class GetClassOnNullRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/tricky_cases.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return GetClassOnNullRector::class;
    }
}
