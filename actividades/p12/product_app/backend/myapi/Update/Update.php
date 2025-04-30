<?php
namespace TECWEB\MYAPI\UPDATE;
use TECWEB\MYAPI\DataBase as DataBase;
//require_once __DIR__ . '/DataBase.php';
//require_once __DIR__ . '/../DataBase.php';


class Update extends DataBase {
    
    public function __construct( $db) {
        $this->data = array();
        parent:: __construct($db);
    }

    public function edit($jsonOBJ) {
        
        $sql = "UPDATE productos SET 
                    nombre='{$jsonOBJ->nombre}', 
                    marca='{$jsonOBJ->marca}', 
                    modelo='{$jsonOBJ->modelo}', 
                    precio={$jsonOBJ->precio}, 
                    detalles='{$jsonOBJ->detalles}', 
                    unidades={$jsonOBJ->unidades}, 
                    imagen='{$jsonOBJ->imagen}' 
                WHERE id={$jsonOBJ->id}";
    
        $this->conexion->set_charset("utf8");

        if ($this->conexion->query($sql)) {

            $this->data['status'] = "success";
            $this->data['message'] = "Producto actualizado";
        } else {

            $this->data['status'] = "error";
            $this->data['message'] = "ERROR: No se ejecutó la consulta. " . mysqli_error($this->conexion);
        }
    
        $this->conexion->close();
    }
    
   
}
?>