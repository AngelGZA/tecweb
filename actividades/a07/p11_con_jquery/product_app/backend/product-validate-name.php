<?php
use TECWEB\MYAPI\Products as Products;
require_once __DIR__.'/myapi/Products.php';

header('Content-Type: application/json');

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
        $response['message'] = 'Nombre inválido (1-100 caracteres)';
        echo json_encode($response);
        exit;
    }

    // Crear nueva instancia para cada validación
    $prodObj = new Products('marketzone');
    
    // Consulta preparada para verificar nombre
    if ($excludeId) {
        $sql = "SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND id != ? AND eliminado = 0";
        $stmt = $prodObj->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error al preparar la consulta con exclusión');
        }
        $stmt->bind_param("si", $nombre, $excludeId);
    } else {
        $sql = "SELECT COUNT(*) as count FROM productos WHERE nombre = ? AND eliminado = 0";
        $stmt = $prodObj->conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception('Error al preparar la consulta');
        }
        $stmt->bind_param("s", $nombre);
    }

    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta');
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['count'] > 0) {
        $response['message'] = 'Ya existe un producto con este nombre';
    } else {
        $response = [
            'status' => 'success',
            'message' => 'Nombre disponible'
        ];
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    // Cerrar conexión solo si existe
    if (isset($prodObj) && isset($prodObj->conexion)) {
        $prodObj->conexion->close();
    }
}

echo json_encode($response);
?>