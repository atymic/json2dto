<?php

declare(strict_types=1);

namespace Atymic\Json2Dto\Helpers;

use PHPUnit\Framework\TestCase;

class NameValidatorTest extends TestCase
{
    /**
     * @dataProvider providerValidateNamespace
     */
    public function testValidateNamespace(string $namespace, bool $shouldBeValid): void
    {
        $this->assertSame($shouldBeValid, NameValidator::validateNamespace($namespace));
    }

    public function providerValidateNamespace(): array
    {
        return [
            'empty' => ['', false],
            'has space' => ['App Two', false],
            'has invalid char' => ['App#Two', false],
            'valid root' => ['App', true],
            'valid subroot' => ['App\DTO', true],
            'valid subsubroot' => ['App\DTO\Two', true],
        ];
    }

    /**
     * @dataProvider providerValidateVariableName
     */
    public function testValidateVariableName(string $varName, bool $shouldBeValid): void
    {
        $this->assertSame($shouldBeValid, NameValidator::validateVariableName($varName));
    }

    public function providerValidateVariableName(): array
    {
        return [
            'empty' => ['', false],
            'invalid has space' => ['var name', false],
            'invalid starts with number' => ['1', false],
            'invalid has dash' => ['abc-123', false],
            'valid starts with letter' => ['abc', true],
            'valid starts with cap letter' => ['Abc', true],
            'valid starts with underscore' => ['_a', true],
            'valid' => ['app', true],
        ];
    }

    /**
     * @dataProvider providerValidateClassName
     */
    public function testValidateClassName(string $className, bool $shouldBeValid): void
    {
        $this->assertSame($shouldBeValid, NameValidator::validateClassName($className));
    }

    public function providerValidateClassName(): array
    {
        return [
            'empty' => ['', false],
            'invalid starts with number' => ['1Class', false],
            'invalid is reserved word 1' => ['require', false],
            'invalid is reserved word 2' => ['array', false],
            'valid starts with letter' => ['abc', true],
            'valid starts with cap letter' => ['Abc', true],
            'valid starts with underscore' => ['_Class', true],
            'valid' => ['App', true],
        ];
    }
}
