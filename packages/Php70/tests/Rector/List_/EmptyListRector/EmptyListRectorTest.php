<?php declare(strict_types=1);

namespace Rector\Php70\Tests\Rector\List_\EmptyListRector;

use Rector\Php70\Rector\List_\EmptyListRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class EmptyListRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFileWithoutAutoload($file);
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
        return EmptyListRector::class;
    }
}
