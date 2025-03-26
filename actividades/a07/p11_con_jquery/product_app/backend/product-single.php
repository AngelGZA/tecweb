<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone'); 

// Obtener ID tanto de POST como de GET para mayor flexibilidad
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

if(empty($id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$prodObj->single($id);
echo $prodObj->getData();
?>
