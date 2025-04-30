<?php
namespace TECWEB\MYAPI\CREATE;
use TECWEB\MYAPI\DataBase as DataBase;
//require_once __DIR__ . '/DataBase.php';
//require_once __DIR__ . '/../DataBase.php';


class Create extends DataBase {
    
    public function __construct( $db) {
        $this->data = array();
        parent:: __construct($db);
    }


    public function add($jsonOBJ) {

        $sql = "SELECT * FROM productos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
        $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {

            $this->conexion->set_charset("utf8");
            $sql = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen, eliminado) 
                    VALUES ('{$jsonOBJ->nombre}', '{$jsonOBJ->marca}', '{$jsonOBJ->modelo}', {$jsonOBJ->precio}, 
                            '{$jsonOBJ->detalles}', {$jsonOBJ->unidades}, '{$jsonOBJ->imagen}', 0)";
    
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto agregado correctamente";
            } else {
                $this->data['status'] = "error";
                $this->data['message'] = "ERROR: No se ejecutó la consulta. " . mysqli_error($this->conexion);
            }
        } else {
            $this->data['status'] = "error";
            $this->data['message'] = "El producto ya existe";
        }
        $result->free();
        $this->conexion->close();
    }
    
}
?>