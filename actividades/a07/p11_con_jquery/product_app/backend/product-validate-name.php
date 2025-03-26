<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone');

$response = [
    'status' => 'error',
    'message' => 'Nombre no proporcionado'
];

if (isset($_GET['nombre'])) {
    $nombre = $_GET['nombre'];
    $prodObj->singleByName($nombre);
    $data = json_decode($prodObj->getData(), true);
    
    if (empty($data)) {
        $response = [
            'status' => 'success',
            'message' => 'Nombre disponible'
        ];
    } else {
        $response['message'] = 'Ya existe un producto con este nombre';
    }
}

echo json_encode($response);
?>