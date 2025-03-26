<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone');

// Obtener ID de POST (consistente con el frontend)
$id = isset($_POST['id']) ? $_POST['id'] : null;

if (!$id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID no proporcionado'
    ]);
    exit;
}

$prodObj->delete($id);
echo $prodObj->getData();
?>