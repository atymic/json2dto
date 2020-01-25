<?php

namespace Atymic\Json2Dto\Helpers;

abstract class NameValidator
{
    private const RESERVED_WORDS = [
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        'from',
    ];

    public static function validateVariableName(string $name): bool
    {
        return preg_match('/^[A-Za-z_][\w]*$/', $name);
    }

    public static function validateClassName(string $name): bool
    {
        return preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name)
            && !in_array(strtolower($name), self::RESERVED_WORDS, true);
    }

    public static function validateNamespace(string $namespace): bool
    {
        return preg_match('/^\\\\?[\w\\\\]+$/', $namespace);
    }
}
