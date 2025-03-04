<?php
include_once __DIR__.'/database.php';

// SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
$data = array();

// SE VERIFICA HABER RECIBIDO EL TÉRMINO DE BÚSQUEDA
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    // SE REALIZA LA QUERY DE BÚSQUEDA
    $sql = "SELECT * FROM productos WHERE 
            nombre LIKE ? OR 
            marca LIKE ? OR 
            detalles LIKE ?";
    $stmt = $conexion->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // SE VERIFICA SI HUBO RESULTADOS
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $row[$key] = utf8_encode($value);
            }
            $data[] = $row;
        }
    } else {
        $data['error'] = 'No se encontraron productos.';
    }

    // SE CIERRAN LAS CONEXIONES
    $stmt->close();
    $conexion->close();
} else {
    $data['error'] = 'No se recibió el término de búsqueda.';
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>