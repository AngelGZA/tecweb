<?php
namespace TECWEB\MYAPI\DELETE;
use TECWEB\MYAPI\DataBase as DataBase;
//require_once __DIR__ . '/DataBase.php';
//require_once __DIR__ . '/../DataBase.php';


class Delete extends DataBase {
    
    public function __construct( $db) {
        $this->data = array();
        parent:: __construct($db);
    }

    public function delete($id) {
        if (isset($id)) {
            
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = {$id}";
    
            if ($this->conexion->query($sql)) {
                $this->data['status'] = "success";
                $this->data['message'] = "Producto eliminado correctamente";
            } else {
                $this->data['status'] = "error";
                $this->data['message'] = "Error en la consulta: " . mysqli_error($this->conexion);
            }
        } else {
            $this->data['status'] = "error";
            $this->data['message'] = "ID no proporcionado";
        }
        $this->conexion->close();
    }
    
    
   
}
?>