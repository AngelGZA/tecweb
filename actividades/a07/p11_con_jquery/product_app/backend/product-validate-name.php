<?php
use TECWEB\MYAPI\Products as Products;
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

$prodObj = new Products('marketzone');

$response = [
    'status' => 'error',
    'message' => 'Error en validación'
];

try {
    // Verificar que se recibió el nombre
    if (!isset($_GET['nombre'])) {
        throw new Exception('Nombre no proporcionado');
    }

    $nombre = trim($_GET['nombre']);
    $excludeId = isset($_GET['excludeId']) ? intval($_GET['excludeId']) : null;

    // Validación básica del nombre
    if (empty($nombre) || strlen($nombre) > 100) {
        $response['message'] = 'Nombre inválido (debe tener entre 1 y 100 caracteres)';
        echo json_encode($response);
        exit;
    }

    // Consulta preparada para evitar SQL injection
    if ($excludeId) {
        $sql = "SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND id != ? AND eliminado = 0";
    } else {
        $sql = "SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND eliminado = 0";
    }

    $stmt = $prodObj->conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta');
    }

    // Bind parameters
    if ($excludeId) {
        $stmt->bind_param("si", $nombre, $excludeId);
    } else {
        $stmt->bind_param("s", $nombre);
    }

    // Ejecutar consulta
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta');
    }

    // Obtener resultados
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $response['message'] = 'Ya existe un producto con este nombre';
    } else {
        $response = [
            'status' => 'success',
            'message' => 'Nombre disponible'
        ];
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar conexión si es necesario
    if (isset($prodObj->conexion)) {
        $prodObj->conexion->close();
    }
}

echo json_encode($response);
?>