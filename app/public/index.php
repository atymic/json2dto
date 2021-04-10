<?php

use Atymic\Json2Dto\Generator\DtoGenerator;
use Atymic\Json2Dto\Helpers\NamespaceFolderResolver;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->options('/', function (Request $request, Response $response) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', '*');
});

$app->post('/', function (Request $request, Response $response) {
    $args = json_decode((string) $request->getBody());

    if ($args === null) {
        return $response->withStatus(400);
    }

    $nested = $args->nested ?? false;
    $name = $args->name ?? 'NewDto';

    $generator = new DtoGenerator(
        $args->namespace ?? 'App\DTO',
        $nested,
        ($args->typed ?? false) || ($args->v3 ?? false),
        $args->flexible ?? false,
        $args->v3 ?? false
    );

    $generator->generate($args->source ?? new stdClass(), $name);

    if ($nested) {
        $zipName = sprintf('%s.zip', uniqid('dto', true));
        $zipPath = '/tmp/' . $zipName;
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE);

        foreach ($generator->getFiles() as $path => $class) {
            $zip->addFromString($path, $class);
        }

        $zip->close();

        $response->getBody()->write(file_get_contents($zipPath));

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', '*')
            ->withHeader('Access-Control-Allow-Methods', '*')
            ->withHeader('Content-Type', 'application/zip')
            ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $zipName))
            ->withHeader('Content-Length', filesize($zipPath));
    }

    $namespaceResolver = new NamespaceFolderResolver();

    $file = array_values($generator->getFiles($namespaceResolver))[0] ?? '';
    $response->getBody()->write($file);

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', '*')
        ->withHeader('content-type', 'text/plain');
});

$app->addErrorMiddleware(true, true, true);

$app->run();
