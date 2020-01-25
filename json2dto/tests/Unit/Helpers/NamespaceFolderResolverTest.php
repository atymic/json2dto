<?php
declare(strict_types=1);

namespace Atymic\Json2Dto\Helpers;

use PHPUnit\Framework\TestCase;

class NamespaceFolderResolverTest extends TestCase
{
    /**
     * @dataProvider providerNamespaceToFolder
     */
    public function testNamespaceToFolder(
        string $namespace,
        ?array $composer,
        ?string $expectedPath,
        ?string $expectedException = null
    ) {
        $resolver = new NamespaceFolderResolver($composer);

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $this->assertSame($expectedPath, $resolver->namespaceToFolder($namespace));
    }

    public function providerNamespaceToFolder(): array
    {
        return [
            'invalid namespace' => [
                'namespace' => 'App DTO',
                'composer' => null,
                'expected_path' => null,
                'expected_exception' => \RuntimeException::class,
            ],
            'no composer config' => [
                'namespace' => 'App\DTO',
                'composer' => null,
                'expected_path' => 'App/DTO',
            ],
            'no composer config, no sub namespace' => [
                'namespace' => 'DTO',
                'composer' => null,
                'expected_path' => 'DTO',
            ],
            'composer config for `App`' => [
                'namespace' => 'App\DTO',
                'composer' => [
                    'autoload' => [
                        'psr-4' => [
                            'Wack\\' => 'notsrc/',
                            'App\\' => 'src/',
                        ],
                    ],
                ],
                'expected_path' => 'src/DTO',
            ],
            'composer config with sub namespace' => [
                'namespace' => 'Vendor\App\Packages\DTO',
                'composer' => [
                    'autoload' => [
                        'psr-4' => [
                            'Vendor\\App\\' => 'src/app/',
                        ],
                    ],
                ],
                'expected_path' => 'src/app/Packages/DTO',
            ],
            'composer config with not matching namespace' => [
                'namespace' => 'NotVendor\App\Packages\DTO',
                'composer' => [
                    'autoload' => [
                        'psr-4' => [
                            'Vendor\\App\\' => 'src/app/',
                        ],
                    ],
                ],
                'expected_path' => 'NotVendor/App/Packages/DTO',
            ],
        ];
    }
}
