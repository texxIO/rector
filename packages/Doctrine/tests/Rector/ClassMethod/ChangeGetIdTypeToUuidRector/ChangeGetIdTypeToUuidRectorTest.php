<?php declare(strict_types=1);

namespace Rector\Doctrine\Tests\Rector\ClassMethod\ChangeGetIdTypeToUuidRector;

use Rector\Doctrine\Rector\ClassMethod\ChangeGetIdTypeToUuidRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ChangeGetIdTypeToUuidRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/get_id.inc.php'];
    }

    protected function getRectorClass(): string
    {
        return ChangeGetIdTypeToUuidRector::class;
    }
}
