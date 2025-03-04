<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Conectar a la base de datos
require 'database.php';  // Archivo de conexión a la BD

// Leer el JSON enviado desde la app
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Datos inválidos"]);
    exit;
}

// Extraer los datos del JSON
$nombre = trim($data['nombre']);
$marca = trim($data['marca']);
$modelo = trim($data['modelo']);
$precio = floatval($data['precio']);
$detalles = isset($data['detalles']) ? trim($data['detalles']) : "";
$unidades = intval($data['unidades']);
$imagen = !empty($data['imagen']) ? trim($data['imagen']) : "img/default.png";

// Validar si el producto ya existe (que no esté eliminado)
$query = "SELECT id FROM productos 
          WHERE eliminado = 0 
          AND ((nombre = ? AND marca = ?) OR (marca = ? AND modelo = ?))";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ssss", $nombre, $marca, $modelo, $marca);
$stmt->execute();
$stmt->store_result();

// Si el producto ya existe, devolver error
if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El producto ya existe en la base de datos."]);
    exit;
}

// Insertar el producto si no existe
$query = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
          VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
$stmt = $conexion->prepare($query);
$stmt->bind_param("sssdsis", $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Producto agregado exitosamente"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al insertar producto"]);
}

$stmt->close();
$conexion->close();
?>
