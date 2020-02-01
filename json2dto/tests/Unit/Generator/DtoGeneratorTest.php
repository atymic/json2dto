<?php

declare(strict_types=1);

namespace Atymic\Json2Dto\Generator;

use Atymic\Json2Dto\Helpers\NamespaceFolderResolver;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use stdClass;

class DtoGeneratorTest extends TestCase
{
    use MatchesSnapshots;

    public function testGeneratesNonNestedDocblockDto()
    {
        $generator = new DtoGenerator('App\DTO', false, false, false);

        $generator->generate($this->arrayToStdClass([
            'string' => 'string',
            'int' => 1,
            'float' => 1.1,
            'string_array' => [
                'a',
                'b',
            ],
            'int_array' => [
                1,
                2,
            ],
            'empty_array' => [],
            'nested_array' => [
                'a' => ['b' => 'c'],
            ],
        ]), 'TestDTO');

        $files = $generator->getFiles(new NamespaceFolderResolver());

        $this->assertCount(1, $files);
        $this->assertMatchesJsonSnapshot($files);
    }

    public function testGeneratesNestedDocblockDto()
    {
        $generator = new DtoGenerator('App\DTO', true, false, false);

        $generator->generate($this->arrayToStdClass([
            'string' => 'string',
            'int' => 1,
            'float' => 1.1,
            'string_array' => [
                'a',
                'b',
            ],
            'int_array' => [
                1,
                2,
            ],
            'empty_array' => [],
            'nested_dto' => [
                'nested_int_array' => [
                    1,
                    2,
                ],
                'nested_nested_dto' => ['b' => 'c'],
            ],
        ]), 'TestDTO');

        $files = $generator->getFiles(new NamespaceFolderResolver());

        $this->assertCount(3, $files);

        $this->assertSame([
            'App/DTO/NestedNestedDto.php',
            'App/DTO/NestedDto.php',
            'App/DTO/TestDTO.php',
        ], array_keys($files));

        $this->assertMatchesJsonSnapshot($files);
    }

    public function testGeneratesNestedTypedDto()
    {
        $generator = new DtoGenerator('App\DTO', true, true, false);

        $generator->generate($this->arrayToStdClass([
            'string' => 'string',
            'int' => 1,
            'float' => 1.1,
            'string_array' => [
                'a',
                'b',
            ],
            'int_array' => [
                1,
                2,
            ],
            'empty_array' => [],
            'nested_dto' => [
                'nested_nested_dto' => ['b' => 'c'],
            ],
        ]), 'TestDTO');

        $files = $generator->getFiles(new NamespaceFolderResolver());

        $this->assertCount(3, $files);

        $this->assertSame([
            'App/DTO/NestedNestedDto.php',
            'App/DTO/NestedDto.php',
            'App/DTO/TestDTO.php',
        ], array_keys($files));

        $this->assertMatchesJsonSnapshot($files);
    }

    private function arrayToStdClass(array $data): ?stdClass
    {
        return json_decode(json_encode($data));
    }
}
