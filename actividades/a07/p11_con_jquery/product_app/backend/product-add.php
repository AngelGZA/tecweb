<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

$prodObj = new Products('marketzone');

// Obtener los datos del POST
$postData = $_POST;

// Verificar si hay datos
if (!empty($postData)) {
    $prodObj->add($postData);
    echo json_encode($prodObj->getData());
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No se recibieron datos del producto'
    ]);
}
?>