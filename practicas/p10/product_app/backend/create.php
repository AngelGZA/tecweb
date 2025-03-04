<?php
header('Content-Type: application/json; charset=UTF-8');

include_once __DIR__.'/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');

if (!empty($producto)) {
    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto);

    // SE VALIDA QUE EL OBJETO JSON TENGA LOS CAMPOS REQUERIDOS
    if (isset($jsonOBJ->nombre) && isset($jsonOBJ->marca) && isset($jsonOBJ->modelo) && 
        isset($jsonOBJ->precio) && isset($jsonOBJ->unidades)) {

        // SE PREPARA LA CONSULTA PARA VERIFICAR SI EL PRODUCTO YA EXISTE
        $sql_check = "SELECT id FROM productos WHERE 
                      (nombre = ? AND marca = ?) OR 
                      (marca = ? AND modelo = ?) AND 
                      eliminado = 0";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param('ssss', $jsonOBJ->nombre, $jsonOBJ->marca, $jsonOBJ->marca, $jsonOBJ->modelo);
        $stmt_check->execute();
        $stmt_check->store_result();

        // SI EL PRODUCTO YA EXISTE, SE DEVUELVE UN MENSAJE DE ERROR
        if ($stmt_check->num_rows > 0) {
            echo json_encode(array('mensaje' => 'El producto ya existe en la base de datos.'));
            $stmt_check->close();
            $conexion->close();
            exit;
        }
        $stmt_check->close();

        // SE PREPARA LA CONSULTA PARA INSERTAR EL PRODUCTO
        $sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->bind_param('sssdsis', $jsonOBJ->nombre, $jsonOBJ->marca, $jsonOBJ->modelo, 
                                 $jsonOBJ->precio, $jsonOBJ->detalles ?? '', $jsonOBJ->unidades, $jsonOBJ->imagen ?? 'http://localhost/tecweb/practicas/p09/img/imagen.png');

        // SE EJECUTA LA INSERCIÓN
        if ($stmt_insert->execute()) {
            echo json_encode(array('mensaje' => 'Producto registrado exitosamente.'));
        } else {
            echo json_encode(array('mensaje' => 'Error al insertar el producto: ' . $stmt_insert->error));
        }

        // SE CIERRAN LAS CONEXIONES
        $stmt_insert->close();
        $conexion->close();
    } else {
        echo json_encode(array('mensaje' => 'Faltan campos en el JSON.'));
    }
} else {
    echo json_encode(array('mensaje' => 'No se recibieron datos.'));
}
?>