<?php
include_once __DIR__.'/database.php';

// Respuesta por defecto
$data = array(
    'status'  => 'error',
    'message' => 'El nombre ya existe en la base de datos.'
);

// Verificar si se recibió el nombre
if (isset($_GET['nombre'])) {
    $nombre = $_GET['nombre'];

    // Escapar caracteres especiales para evitar inyección SQL
    $nombre = $conexion->real_escape_string($nombre);

    // Consulta para verificar si el nombre ya existe
    $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
    $result = $conexion->query($sql);

    // Si no hay resultados, el nombre no existe
    if ($result->num_rows == 0) {
        $data['status'] = 'success';
        $data['message'] = 'El nombre está disponible.';
    }
}

// Cerrar la conexión
$conexion->close();

// Enviar la respuesta en formato JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>