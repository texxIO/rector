<?php declare(strict_types=1);

namespace Rector\DeadCode\Tests\Rector\Class_\RemoveSetterOnlyPropertyAndMethodCallRector;

use Rector\DeadCode\Rector\Class_\RemoveSetterOnlyPropertyAndMethodCallRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveSetterOnlyPropertyAndMethodCallRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/in_constructor.php.inc'];
        yield [__DIR__ . '/Fixture/keep_many_to_one.php.inc'];
        yield [__DIR__ . '/Fixture/keep_static_property.php.inc'];
        yield [__DIR__ . '/Fixture/keep_public_property.php.inc'];
        yield [__DIR__ . '/Fixture/keep_serializable_object.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return RemoveSetterOnlyPropertyAndMethodCallRector::class;
    }
}
