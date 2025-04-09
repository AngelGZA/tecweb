<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';


$app = AppFactory::create();
// /myapp/api is the api folder http://domain/myapp/api/
$app->setBasePath("/tecweb/pruebaslim");

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hola mundo Slim!!!");
    return $response;
});

$app->run();
?>