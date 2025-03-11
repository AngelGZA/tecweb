<?php
include_once __DIR__.'/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'No se pudo actualizar el producto'
);

if (!empty($producto)) {
    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto);

    // VALIDACIÓN DEL PRECIO
    if ($jsonOBJ->precio < 99) {
        echo json_encode(["success" => false, "message" => "Error: El precio no puede ser menor a 99."]);
        exit;
    }

    if ($jsonOBJ->unidades < 0) {
        echo json_encode(["success" => false, "message" => "Error: La unidades no pueden ser menor a 0."]);
        exit;
    }

    if ($jsonOBJ->precio < 99) {
        echo json_encode(["success" => false, "message" => "Error: El precio no puede ser menor a 99."]);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9\-]+$/', $jsonOBJ->modelo)) {
        echo json_encode(["success" => false, "message" => "Error: El modelo debe ser alfanumérico."]);
        exit;
    }
    

    // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
    $sql = "UPDATE productos SET 
            nombre = '{$jsonOBJ->nombre}', 
            marca = '{$jsonOBJ->marca}', 
            modelo = '{$jsonOBJ->modelo}', 
            precio = {$jsonOBJ->precio}, 
            detalles = '{$jsonOBJ->detalles}', 
            unidades = {$jsonOBJ->unidades}, 
            imagen = '{$jsonOBJ->imagen}' 
            WHERE id = {$jsonOBJ->id}";

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