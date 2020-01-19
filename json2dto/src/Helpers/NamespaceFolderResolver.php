<?php
declare(strict_types=1);

namespace Atymic\Json2Dto\Helpers;

class NamespaceFolderResolver
{
    public static function namespaceToFolder(string $namespace): string
    {
        if (!file_exists('composer.json')) {
            return str_replace('\\', '/', $namespace);
        }

        $composerConfig = json_decode(file_get_contents('composer.json'), true);

        foreach ($composerConfig['autoload']['psr-4'] ?? [] as $autoloadNamespace => $autoloadFolder) {
            if (!self::namespaceSameRoot($namespace, $autoloadNamespace)) {
                continue;
            }

            $subNamespace = str_replace($autoloadNamespace, '', $namespace);

            return $autoloadFolder . str_replace('\\', '/', $subNamespace);
        }

        return str_replace('\\', '/', $namespace);
    }

    public static function namespaceSameRoot(string $a, string $b): bool
    {
        return !empty($a) && !empty($b) && explode('\\' ,$a)[0] ===  explode('\\' ,$b)[0];
    }
}
