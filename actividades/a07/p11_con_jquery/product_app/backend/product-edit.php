<?php
include_once __DIR__.'/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$data = array(
    'status'  => 'error',
    'message' => 'No se pudo actualizar el producto'
);

if (isset($_POST['id'])) {
    // SE TRANSFORMA EL POST A UN OBJETO
    $jsonOBJ = (object)$_POST;

    // VALIDACIÓN DEL PRECIO
    if ($jsonOBJ->precio < 99) {
        $data['message'] = "Error: El precio no puede ser menor a 99.";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // VALIDACIÓN DE LAS UNIDADES
    if ($jsonOBJ->unidades < 0) {
        $data['message'] = "Error: Las unidades no pueden ser menor a 0.";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // VALIDACIÓN DEL MODELO
    if (!preg_match('/^[a-zA-Z0-9\-]+$/', $jsonOBJ->modelo)) {
        $data['message'] = "Error: El modelo debe ser alfanumérico.";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // ESCAPAR LOS VALORES PARA EVITAR INYECCIÓN SQL
    $nombre = $conexion->real_escape_string($jsonOBJ->nombre);
    $marca = $conexion->real_escape_string($jsonOBJ->marca);
    $modelo = $conexion->real_escape_string($jsonOBJ->modelo);
    $precio = floatval($jsonOBJ->precio);
    $detalles = $conexion->real_escape_string($jsonOBJ->detalles);
    $unidades = intval($jsonOBJ->unidades);
    $imagen = $conexion->real_escape_string($jsonOBJ->imagen);
    $id = intval($jsonOBJ->id);

    // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
    $sql = "UPDATE productos SET 
            nombre = '$nombre', 
            marca = '$marca', 
            modelo = '$modelo', 
            precio = $precio, 
            detalles = '$detalles', 
            unidades = $unidades, 
            imagen = '$imagen' 
            WHERE id = $id";

    if ($conexion->query($sql)) {
        $data['status'] = "success";
        $data['message'] = "Producto actualizado";
    } else {
        $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
    }

    $conexion->close();
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>