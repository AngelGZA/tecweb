<?php
use TECWEB\MYAPI\Products as Products; 
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone');

try {
    $postData = $_POST;
    
    if (!isset($postData['id'])) {
        throw new Exception('ID de producto no proporcionado');
    }
    
    // Procesar la edición y obtener respuesta
    $prodObj->edit($postData);
    
    // El método edit ahora maneja su propia salida JSON
    exit;
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
?>