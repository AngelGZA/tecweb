<?php
header('Content-Type: text/html; charset=UTF-8');

$host = 'localhost';
$user = 'root';
$pass = 'Mitelefono12';
$db   = 'marketzone';

$nombre  = $_POST['nombre'] ?? '';
$marca   = $_POST['marca'] ?? '';
$modelo  = $_POST['modelo'] ?? '';
$precio  = $_POST['precio'] ?? 0;
$detalles = $_POST['detalles'] ?? '';
$unidades = $_POST['unidades'] ?? 0;
$imagen   = $_POST['imagen'] ?? 'img/default.png';

$link = new mysqli($host, $user, $pass, $db);

if ($link->connect_errno) {
    die('<p>Error de conexión: ' . $link->connect_error . '</p>');
}

$sql_check = "SELECT id FROM productos WHERE nombre = ? AND marca = ? AND modelo = ?";
$stmt_check = $link->prepare($sql_check);
$stmt_check->bind_param('sss', $nombre, $marca, $modelo);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die('<p>Error: El producto ya existe en la base de datos.</p>');
}
$stmt_check->close();

/*$sql_insert = "INSERT INTO productos 
               VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, 0)";
$stmt_insert = $link->prepare($sql_insert);
$stmt_insert->bind_param('sssdsis', $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);
*/
$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $link->prepare($sql_insert);
$stmt_insert->bind_param('sssdsis', $nombre, $marca, $modelo, $precio, $detalles, $unidades, $imagen);


if ($stmt_insert->execute()) {
    echo '<h2>Producto registrado exitosamente</h2>';
    echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>';
    echo '<p><strong>Marca:</strong> ' . htmlspecialchars($marca) . '</p>';
    echo '<p><strong>Modelo:</strong> ' . htmlspecialchars($modelo) . '</p>';
    echo '<p><strong>Precio:</strong> $' . number_format($precio, 2) . '</p>';
    echo '<p><strong>Detalles:</strong> ' . nl2br(htmlspecialchars($detalles)) . '</p>';
    echo '<p><strong>Unidades:</strong> ' . (int)$unidades . '</p>';
    echo '<p><strong>Imagen:</strong> <img src="' . htmlspecialchars($imagen) . '" width="100"></p>';
} else {
    echo '<p>Error al insertar el producto: ' . $stmt_insert->error . '</p>';
}

$stmt_insert->close();
$link->close();
?>