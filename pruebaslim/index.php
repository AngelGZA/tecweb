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

//Ejemplo 2
$app->get("/hola[/{nombre}]", function( $request, $response, $args ){
    $nombre = $args["nombre"] ?? "invitado";
    $response->getBody()->write("Hola, " . $nombre);
    return $response;
});

//Ejemplo 3
$app->post("/pruebapost", function( $request, $response, $args ){
    $reqPost = $request->getParsedBody();
    $val1 = $reqPost["val1"] ?? '';
    $val2 = $reqPost["val2"] ?? '';

    $response->getBody()->write("Valores: " . $val1 . " " . $val2);
    return $response;
});


$app->run();
?>