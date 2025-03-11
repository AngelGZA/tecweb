<?php
    include_once __DIR__.'/database.php';

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $conexion->set_charset("utf8");

        $sql = "SELECT * FROM productos WHERE id = $id";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $producto = $result->fetch_assoc();
            // Estructurar el JSON correctamente
            $json_producto = array(
                "id"       => $producto['id'],
                "nombre"   => $producto['nombre'],
                "precio"   => (float) $producto['precio'],
                "unidades" => (int) $producto['unidades'],
                "modelo"   => $producto['modelo'],
                "marca"    => $producto['marca'],
                "detalles" => $producto['detalles'],
                "imagen"   => $producto['imagen']
            );
            echo json_encode($json_producto, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["error" => "Producto no encontrado"]);
        }
    }
?>
