<?php declare(strict_types=1);

namespace Rector\Php74\Tests\Rector\MagicConstClass\ClassConstantToSelfClassRector;

use Rector\Php74\Rector\MagicConstClass\ClassConstantToSelfClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ClassConstantToSelfClassRectorTest extends AbstractRectorTestCase
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
        return ClassConstantToSelfClassRector::class;
    }
}
