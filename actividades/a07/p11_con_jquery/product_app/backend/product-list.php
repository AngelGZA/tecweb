<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

$prodObj = new Products('marketzone'); 
$prodObj->list();

// Obtener los datos como array
$response = json_decode($prodObj->getData(), true);

// Si la respuesta no tiene la estructura esperada, formatearla
if (!isset($response['status'])) {
    $response = [
        'status' => 'success',
        'data' => $response
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>