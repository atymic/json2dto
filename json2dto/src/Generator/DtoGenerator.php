<?php

namespace Atymic\Json2Dto\Generator;

use Atymic\Json2Dto\Helpers\NameValidator;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\FlexibleDataTransferObject;
use stdClass;

class DtoGenerator
{
    /** @var string */
    private $baseNamespace;
    /** @var boolean */
    private $nested;
    /** @var boolean */
    private $typed;
    /** @var boolean */
    private $flexible;

    /** @var PhpNamespace[] */
    private $classes = [];

    public function __construct(string $baseNamespace, bool $nested, bool $typed, bool $flexible)
    {
        $this->baseNamespace = $baseNamespace;
        $this->nested = $nested;
        $this->typed = $typed;
        $this->flexible = $flexible;
    }

    public function generate(stdClass $source, ?string $name): void
    {
        $this->createClass($this->baseNamespace, $source, $name);
    }

    public function getFiles(): array
    {
        $files = [];

        foreach ($this->classes as $classNamespace) {
            $folder = $this->namespaceToFolder($classNamespace->getName());
            $className = array_values($classNamespace->getClasses())[0]->getName();
            $class = sprintf("<?php\n\n%s", (string) $classNamespace);
            $path = sprintf('%s/%s.php', $folder, $className);

            $files[$path] = $class;
        }

        return $files;
    }

    public function createClass(
        string $namespace,
        stdClass $source,
        ?string $name
    ): PhpNamespace {
        $extends = $this->flexible ? FlexibleDataTransferObject::class : DataTransferObject::class;

        $classNamespace = new PhpNamespace($namespace);
        $classNamespace->addUse($extends);

        $class = $classNamespace->addClass(str_replace(' ', '', $name ?? 'JsonDataTransferObject'));
        $class->addExtend($extends);

        foreach ($source as $key => $value) {
            $this->addProperty($classNamespace, $class, $key, $value);
        }

        $this->classes[] = $classNamespace;

        return $classNamespace;
    }

    private function addProperty(PhpNamespace $namespace, ClassType $class, string $key, $value): void
    {
        $type = $this->getType($value);

        if (!NameValidator::validateVariableName($key)) {
            //todo log
            return;
        }

        if ($type === 'array') {
            $property = $this->addCollectionProperty($namespace, $class, $key, $value);

            if ($property !== null) {
                return;
            }

            // Fall back to mixed type if property is invalid
            $type = null;
        }

        if ($type === 'object') {
            // Fall back to mixed type if name is invalid
            $type = null;
            if ($this->nested && NameValidator::validateClassName($key)) {
                $dto = $this->addNestedDTO($namespace, $key, $value);
                $type = $this->getDtoFqcn($dto);
            }
        }

        $property = $class->addProperty($key)->setVisibility('public');

        if ($this->typed && $type === null) {
            return;
        }

        if ($this->typed) {
            $property->setType($type);
            return;
        }

        $property->addComment(sprintf('@var %s $%s', $type ?? 'mixed', $key));
    }

    public function addCollectionProperty(
        PhpNamespace $namespace,
        ClassType $class,
        string $key,
        array $values
    ): ?Property {
        $types = collect($values)->map([$this, 'getType']);

        $uniqueTypes = $types->unique()->filter();

        if ($uniqueTypes->isEmpty() || $uniqueTypes->count() > 1 || $uniqueTypes->first() === 'array') {
            // todo log here
            return null;
        }

        $type = $uniqueTypes->first();

        // Handle scalar types
        if ($type !== 'object') {
            $property = $class->addProperty($key)->setVisibility('public');

            if ($this->typed) {
                $property->setType('array');
            }

            $property->addComment(sprintf('@var %s[] $%s', $type, $key));

            return $property;
        }

        // Handle nested DTOs

        if (!$this->nested) {
            return null;
        }

        if (!$this->membersHaveConsistentStructure($values)) {
            // todo log here
            return null;
        }

        if (!NameValidator::validateClassName($key)) {
            // todo log here
            return null;
        }

        $dto = $this->addNestedDTO($namespace, $key, reset($values));

        $property = $class->addProperty($key)->setVisibility('public');

        if ($this->typed) {
            $property->setType('array');
        }

        $property->addComment(sprintf('@var %s[] $%s', $this->getDtoFqcn($dto), $key));

        return $property;
    }

    public function addNestedDTO(PhpNamespace $namespace, string $name, stdClass $object): ClassType
    {
        $generatedDtoNamespace = $this->createClass($this->baseNamespace, $object, Str::studly($name));
        $generatedDto = array_values($generatedDtoNamespace->getClasses())[0];

        if ($namespace->getName() !== $generatedDtoNamespace->getName()) {
            $namespace->addUse($generatedDtoNamespace->getName() . $generatedDto->getName());
        }

        return $generatedDto;
    }

    public function membersHaveConsistentStructure(array $values): bool
    {
        return collect($values)
                ->map(function (stdClass $object) {
                    return array_keys(get_object_vars($object));
                })
                ->unique()
                ->count() === 1;
    }

    public function getType($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return 'string';
        }

        if ($value instanceof stdClass) {
            return 'object';
        }

        if (is_array($value)) {
            return 'array';
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

    private function namespaceToFolder(string $namespace)
    {
        // todo windows?
        return str_replace('\\', '/', $namespace);
    }

    private function getDtoFqcn(ClassType $dto): string
    {
        return sprintf('\\%s\\%s', $dto->getNamespace()->getName(), $dto->getName());
    }
}
