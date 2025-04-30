<?php
require_once __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TECWEB\MYAPI\READ\Read as Read;
use TECWEB\MYAPI\DELETE\Delete as Delete;
use TECWEB\MYAPI\CREATE\Create as Create;
use TECWEB\MYAPI\UPDATE\Update as Update;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->setBasePath('/tecweb/actividades/p12/product_app/backend');

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Content-Type', 'application/json');
});

// Listar todos los productos
$app->get('/products', function (Request $request, Response $response) {
    $prodObj = new Read('marketzone');
    $prodObj->list();
    $response->getBody()->write($prodObj->getData());
    return $response;
});

// Buscar productos por término
$app->get('/products/{search}', function (Request $request, Response $response, $args) {
    $search = $args['search'] ?? '';

    $prodObj = new Read('marketzone');
    $prodObj->search($search);
    $response->getBody()->write($prodObj->getData());
    return $response;
});


// Ruta GET /product (Obtener por ID)
$app->get('/product/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'] ?? null;

    $prodObj = new Read('marketzone');
    $prodObj->single($id);
    $response->getBody()->write($prodObj->getData());
    return $response;
});


// Ruta POST /product (Crear)
$app->post('/product', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    
    if (empty($data)) {
        $response->getBody()->write(json_encode(['error' => 'Datos no proporcionados']));
        return $response->withStatus(400);
    }

    $prodObj = new Create('marketzone');
    $prodObj->add((object)$data);
    $response->getBody()->write($prodObj->getData());
    return $response;
});

// Ruta PUT /product (Actualizar)
$app->put('/product', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    
    if (empty($data) || !isset($data['id'])) {
        $response->getBody()->write(json_encode(['error' => 'Datos incompletos']));
        return $response->withStatus(400);
    }

    $prodObj = new Update('marketzone');
    $prodObj->edit((object)$data);
    $response->getBody()->write($prodObj->getData());
    return $response;
});

// Ruta DELETE /product/{id} (Eliminar)
$app->delete('/product/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'] ?? null;
    
    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID no proporcionado']));
        return $response->withStatus(400);
    }

    $prodObj = new Delete('marketzone');
    $prodObj->delete($id);
    $response->getBody()->write($prodObj->getData());
    return $response;
});

$app->run();