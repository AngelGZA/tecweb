<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone');

try {
    // Obtener los datos del POST
    $postData = $_POST;

    // Verificar si hay datos
    if (empty($postData)) {
        throw new Exception('No se recibieron datos del producto');
    }

    $prodObj->add($postData);
    echo $prodObj->getData();
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>