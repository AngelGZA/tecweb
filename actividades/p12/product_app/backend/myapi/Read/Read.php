<?php
namespace TECWEB\MYAPI\READ;
use TECWEB\MYAPI\DataBase as DataBase;

class Read extends DataBase {
    public function __construct($db) {
        $this->data = array();
        parent::__construct($db);
    }

    public function list() {
        $result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0");
        
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if ($rows) {
                $this->data = array_map(function($row) {
                    return array_map('utf8_encode', $row);
                }, $rows);
            }
            $result->free();
        } else {
            $this->data = ['error' => 'Error en la consulta: ' . mysqli_error($this->conexion)];
        }
        $this->conexion->close();
    }

    public function search($search) {
        $search = $this->conexion->real_escape_string($search);
        $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%') AND eliminado = 0";
        
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            if ($rows) {
                $this->data = array_map(function($row) {
                    return array_map('utf8_encode', $row);
                }, $rows);
            }
            $result->free();
        } else {
            $this->data = ['error' => 'Error en la búsqueda'];
        }
        $this->conexion->close();
    }

    public function single($id) {
        $id = intval($id);
        $result = $this->conexion->query("SELECT * FROM productos WHERE id = {$id}");
        
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row) {
                $this->data = array_map('utf8_encode', $row);
            } else {
                $this->data = ['error' => 'Producto no encontrado'];
            }
            $result->free();
        } else {
            $this->data = ['error' => 'Error en la consulta'];
        }
        $this->conexion->close();
    }

    public function getData() {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}