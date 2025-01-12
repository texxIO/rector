<?php declare(strict_types=1);

namespace Rector\Php53\Tests\Rector\FuncCall\DirNameFileConstantToDirConstantRector;

use Rector\Php53\Rector\FuncCall\DirNameFileConstantToDirConstantRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class DirNameFileConstantToDirConstantRectorTest extends AbstractRectorTestCase
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
    }

    protected function getRectorClass(): string
    {
        return DirNameFileConstantToDirConstantRector::class;
    }
}
