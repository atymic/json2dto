<?php

namespace Atymic\Json2Dto;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\FlexibleDataTransferObject;

class DtoGenerator
{
    public function createClass(
        string $namespace,
        array $source,
        ?string $name,
        bool $typed = false,
        bool $flexible = false
    ): string {
        $extends = $flexible ? FlexibleDataTransferObject::class : DataTransferObject::class;

        $classNamespace = new PhpNamespace($namespace);
        $classNamespace->addUse($extends);

        $class = $classNamespace->addClass(str_replace(' ', '', $name ?? 'JsonDataTransferObject'));
        $class->addExtend($extends);

        foreach ($source as $key => $value) {
            $this->addProperty($class, $key, $this->getType($value), $typed);
        }

        return (string) $classNamespace;
    }

    private function addProperty(ClassType $class, string $name, ?string $type, bool $typed): void
    {
        $property = $class->addProperty($name)->setVisibility('public');

        if ($typed && $type === null) {
            return;
        }

        if ($typed) {
            $property->setType($type);
            return;
        }

        $property->addComment(sprintf('@var %s $%s', $type ?? 'mixed', $name));
    }

    private function getType($value): ?string
    {
        if ($value === null) {
            return null;
        }
        if (is_string($value)) {
            return 'string';
        }
        if (($value === true || $value === false) === true) {
            return 'bool';
        }
        if (is_int($value)) {
            return 'int';
        }
        if (is_float($value)) {
            return 'float';
        }

        return null;
    }
}
