<?php

use Atymic\Json2Dto\DtoGenerator;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->options('/', function (Request $request, Response $response) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', '*');
});

$app->post('/', function (Request $request, Response $response) {
    $generator = new DtoGenerator();

    $args = json_decode((string) $request->getBody(), true);

    if ($args === null) {
        return $response->withStatus(400);
    }

    $output = $generator->createClass(
        $args['namespace'] ?? 'App\DTO',
        $args['source'] ?? [],
        $args['name'] ?? null,
        $args['typed'] ?? false,
        $args['flexible'] ?? false
    );

    $response->getBody()->write(sprintf("<?php\n\n%s", $output));

    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*')
        ->withHeader('Access-Control-Allow-Methods', '*')
        ->withHeader('content-type', 'text/plain');
});

$app->addErrorMiddleware(false, true, true);

$app->run();
