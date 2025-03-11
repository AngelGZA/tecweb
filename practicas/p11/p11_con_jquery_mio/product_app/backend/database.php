<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'Mitelefono12',
        'marketzone'
    );

    /**
     * NOTA: si la conexión falló $conexion contendrá false
     **/
    if(!$conexion) {
        die('¡Base de datos NO conextada!');
    }

    // Sobrescribir mysqli_query() para filtrar productos eliminados
    function filtrarProductosNoEliminados($conexion, $query) {
    if (strpos($query, "FROM productos") !== false && strpos($query, "eliminado") === false) {
        // Agrega condición para excluir productos eliminados si no está presente
        if (strpos($query, "WHERE") !== false) {
            $query = preg_replace('/WHERE /', 'WHERE eliminado = 0 AND ', $query, 1);
        } else {
            $query .= " WHERE eliminado = 0";
        }
    }
    return mysqli_query($conexion, $query);
}
?>