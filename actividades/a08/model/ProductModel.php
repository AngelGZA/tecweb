<?php

require_once __DIR__ .  '/./DataBase.php';

class ProductModel extends DataBase{
    private $response = [];

    public function __construct($database = 'marketzone', $user = 'root', $password = 'Mitelefono12') {

        parent::__construct($database, $user, $password);
        $this->response = [];
    }

    public function getData(){
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

    public function addProduct($name, $marca, $modelo, $precio, $detalles, $unidades, $imagen) {
        $this->response = [
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre',
            'icon'    => 'error'
        ];
    
        $sql = "SELECT * FROM productos WHERE nombre = '{$this->conexion->real_escape_string($name)}' AND eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            if ($result->num_rows == 0) {
                $this->conexion->set_charset("utf8");
                $sql = "INSERT INTO productos VALUES (
                    null,
                    '{$this->conexion->real_escape_string($name)}',
                    '{$this->conexion->real_escape_string($marca)}',
                    '{$this->conexion->real_escape_string($modelo)}',
                    {$precio},
                    '{$this->conexion->real_escape_string($detalles)}',
                    {$unidades},
                    '{$this->conexion->real_escape_string($imagen)}',
                    0
                )";
                if ($this->conexion->query($sql)) {
                    $this->response = [
                        'status'  => 'success',
                        'message' => 'Producto agregado correctamente',
                        'icon'    => 'success'
                    ];
                } else {
                    $this->response['message'] = "ERROR: No se ejecutó la consulta. " . $this->conexion->error;
                }
            }
            $result->free();
        } else {
            $this->response['message'] = "ERROR en la consulta: " . $this->conexion->error;
        }
        return $this->getData();
    }

    public function dataProduct($search) {
        $this->response = [];
        $search = $this->conexion->real_escape_string($search);

        $sql = "SELECT * FROM productos WHERE nombre = '$search' AND eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
    
            if (!is_null($rows)) {
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->response[$num][$key] = utf8_encode($value);
                    }
                }
            }
    
            $result->free();
        } else {
            $this->response['error'] = "Query Error: " . $this->conexion->error;
        }
        return $this->getData();
    }

    public function deleteData($id) {
        // Inicializar la respuesta con valores predeterminados
        $this->response = [
            'status'  => 'error',
            'message' => 'La consulta falló'
        ];
    
        if (!empty($id)) {
            $id = $this->conexion->real_escape_string($id);
            $sql = "UPDATE productos SET eliminado=1 WHERE id = {$id}";
            if ($this->conexion->query($sql)) {
                $this->response = [
                    'status'  => 'success',
                    'message' => 'Producto eliminado'
                ];
            } else {
                $this->response['message'] = "ERROR: No se ejecutó la consulta. " . $this->conexion->error;
            }
        } else {
            $this->response['message'] = 'ID inválido o no proporcionado.';
        }
        return $this->getData();
    }

    public function editProduct($id, $name, $brand, $modelo, $precio, $detalles, $unidades, $imagen) {
        $this->response = [
            'status'  => 'error',
            'message' => 'Error al actualizar el producto',
            'icon'    => 'error'
        ];
    
        if (!empty($id)) {
            $id = $this->conexion->real_escape_string($id);
            $name = $this->conexion->real_escape_string($name);
            $brand = $this->conexion->real_escape_string($brand);
            $modelo = $this->conexion->real_escape_string($modelo);
            $precio = $this->conexion->real_escape_string($precio);
            $detalles = $this->conexion->real_escape_string($detalles);
            $unidades = $this->conexion->real_escape_string($unidades);
            $imagen = $this->conexion->real_escape_string($imagen);

            $sql = "UPDATE productos SET 
                        nombre='$name', 
                        marca='$brand', 
                        modelo='$modelo', 
                        precio=$precio, 
                        detalles='$detalles', 
                        unidades=$unidades, 
                        imagen='$imagen' 
                    WHERE id=$id";
    
            $this->conexion->set_charset("utf8");
    
            if ($this->conexion->query($sql)) {
                $this->response = [
                    'status'  => 'success',
                    'message' => 'Producto actualizado correctamente',
                    'icon'    => 'success'
                ];
            } else {
                $this->response['message'] = "Error al actualizar el producto: " . $this->conexion->error;
            }
        } else {
            $this->response['message'] = 'ID inválido o no proporcionado.';
        }

        return $this->getData();
    }

    public function listProduct() {
        $this->response = [];
    
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        if ($result = $this->conexion->query($sql)) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $num => $row) {
                foreach ($row as $key => $value) {
                    $this->response[$num][$key] = utf8_encode($value);
                }
            }
    
            $result->free(); 
        } else {
            $this->response['error'] = "Query Error: " . $this->conexion->error;
        }
    
        return $this->getData();
    }

    public function searchProduct($search) {
        $this->response = [];
    
        if (!empty($search)) {
            $search = $this->conexion->real_escape_string($search);
            $sql = "SELECT * FROM productos 
                    WHERE (id = '{$search}' 
                        OR nombre LIKE '%{$search}%' 
                        OR marca LIKE '%{$search}%' 
                        OR detalles LIKE '%{$search}%') 
                    AND eliminado = 0";

            if ($result = $this->conexion->query($sql)) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
    
                foreach ($rows as $num => $row) {
                    foreach ($row as $key => $value) {
                        $this->response[$num][$key] = utf8_encode($value);
                    }
                }
    
                $result->free();
            } else {
                $this->response['error'] = "Query Error: " . $this->conexion->error;
            }
        } else {
            $this->response['error'] = "El parámetro de búsqueda no puede estar vacío.";
        }
    
        return $this->getData();
    }

    public function singleProduct($id) {
        $this->response = [];

        if (!empty($id)) {
            $id = $this->conexion->real_escape_string($id);
    
            $sql = "SELECT * FROM productos WHERE id = {$id}";
            if ($result = $this->conexion->query($sql)) {
                if ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
                        $this->response[$key] = utf8_encode($value);
                    }
                } else {
                    $this->response['error'] = "No se encontró un producto con el ID proporcionado.";
                }
    
                $result->free();
            } else {
                $this->response['error'] = "Query Error: " . $this->conexion->error;
            }
        } else {
            $this->response['error'] = "El parámetro de ID no puede estar vacío.";
        }
    
        return $this->getData();
    }
 }
?>