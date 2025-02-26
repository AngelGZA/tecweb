<?php
// Conectar a la base de datos
$link = mysqli_connect("localhost", "root", "Mitelefono12", "marketzone");

// Verificar conexión
if ($link === false) {
    die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
}

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$precio = $_POST['precio'];
$unidades = $_POST['unidades'];
$detalles = $_POST['detalles'];

// Consulta de actualización
$sql = "UPDATE productos SET nombre='$nombre', marca='$marca', modelo='$modelo', 
        precio='$precio', unidades='$unidades', detalles='$detalles' WHERE id=$id";

if (mysqli_query($link, $sql)) {
    echo "Producto actualizado correctamente. <br>";
    echo "<a href='get_productos_xhtml_v2.php?tope=1000'>Ver Productos XHTML</a> | ";
    echo "<a href='get_productos_vigentes_v2.php?tope=1000'>Ver Productos Vigentes</a>";
} else {
    echo "ERROR: No se ejecutó $sql. " . mysqli_error($link);
}

// Cerrar conexión
mysqli_close($link);
?>
