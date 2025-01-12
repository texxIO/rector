<?php declare(strict_types=1);

namespace Rector\DoctrinePhpDocParser\Tests\PhpDocParser\OrmTagParser\Property_;

use PhpParser\Node\Stmt\Property;
use Rector\DoctrinePhpDocParser\Tests\PhpDocParser\OrmTagParser\AbstractOrmTagParserTest;

/**
 * @see \Rector\DoctrinePhpDocParser\PhpDocParser\OrmTagParser
 */
final class OrmTagParserPropertyTest extends AbstractOrmTagParserTest
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath, string $expectedPrintedPhpDoc): void
    {
        $property = $this->parseFileAndGetFirstNodeOfType($filePath, Property::class);
        $printedPhpDocInfo = $this->createPhpDocInfoFromNodeAndPrintBackToString($property);

        $this->assertStringEqualsFile($expectedPrintedPhpDoc, $printedPhpDocInfo);
    }

    public function provideData(): iterable
    {
        yield [__DIR__ . '/Fixture/SomeProperty.php', __DIR__ . '/Fixture/expected_some_property.txt'];
        yield [__DIR__ . '/Fixture/PropertyWithName.php', __DIR__ . '/Fixture/expected_property_with_name.txt'];
        yield [__DIR__ . '/Fixture/FromOfficialDocs.php', __DIR__ . '/Fixture/expected_from_official_docs.txt'];
    }
}
