<?php
namespace TECWEB\MYAPI;
abstract class DataBase {
    
    protected $conexion;
    protected $data=NULL;
    
    
    public function __construct($db, $user='root', $pass='Mitelefono12') {
        $this->conexion = @mysqli_connect(
            'localhost', 
            $user, 
            $pass, 
            $db
        );


        if (!$this->conexion) {   
            die('¡Base de datos no encontrada!') ;
         }
        }


        public function getData() {

            return json_encode($this->data, JSON_PRETTY_PRINT);
        } 
}
?>