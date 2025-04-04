<?php
    namespace TECWEB\MYAPI; 

    use TECWEB\MYAPI\DataBase as Database; 
    require_once __DIR__ .'/DataBase.php'; 

    class Products extends DataBase{
        private $data = NULL; 

        public function __construct($db, $user='root', $pass='Mitelefono12'){
            $this->data = array(); 
            parent::__construct($user, $pass, $db); 
        }

        public function add($productData) {
            $this->data = array(
                'status' => 'error',
                'message' => 'Ya existe un producto con ese nombre'
            );
            
            // Verificar que los datos requeridos estén presentes
            $requiredFields = ['nombre', 'marca', 'modelo', 'precio', 'unidades'];
            foreach ($requiredFields as $field) {
                if (!isset($productData[$field]) || empty($productData[$field])) {
                    $this->data['message'] = "El campo $field es requerido";
                    return;
                }
            }
            
            // Asignar imagen por defecto si no se proporciona
            $imagen = !empty($productData['imagen']) ? $productData['imagen'] : 'http://localhost/tecweb/practicas/p09/img/imagen.png';
            
            // Verificar si el nombre ya existe
            $nombre = $this->conexion->real_escape_string($productData['nombre']);
            $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if ($result->num_rows == 0) {
                $this->conexion->set_charset("utf8");
                $marca = $this->conexion->real_escape_string($productData['marca']);
                $modelo = $this->conexion->real_escape_string($productData['modelo']);
                $precio = floatval($productData['precio']);
                $detalles = isset($productData['detalles']) ? $this->conexion->real_escape_string($productData['detalles']) : '';
                $unidades = intval($productData['unidades']);
                
                $sql = "INSERT INTO productos VALUES (null, '{$nombre}', '{$marca}', '{$modelo}', {$precio}, '{$detalles}', {$unidades}, '{$imagen}', 0)";
                
                if($this->conexion->query($sql)) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Producto agregado";
                    $this->data['id'] = $this->conexion->insert_id;
                } else {
                    $this->data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($this->conexion);
                }
            }
            
            if (isset($result)) {
                $result->free();
            }
            $this->conexion->close();
        }

        public function delete($id) {
            $this->data = [
                'status' => 'error',
                'message' => 'No se pudo eliminar el producto'
            ];
        
            if (!$id) {
                $this->data['message'] = 'ID no proporcionado';
                return;
            }
        
            // Sanitizar el ID
            $id = $this->conexion->real_escape_string($id);
            
            // Query para marcar como eliminado (soft delete)
            $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";
            
            if ($this->conexion->query($sql)) {
                if ($this->conexion->affected_rows > 0) {
                    $this->data = [
                        'status' => 'success',
                        'message' => 'Producto eliminado correctamente'
                    ];
                } else {
                    $this->data['message'] = 'No se encontró el producto o ya fue eliminado';
                }
            } else {
                $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
            }
        
            $this->conexion->close();
        }

        public function edit($productData) {
            $this->data = [
                'status' => 'error',
                'message' => 'Error al actualizar el producto'
            ];
        
            // Campos obligatorios
            $required = ['id', 'nombre', 'marca', 'modelo', 'precio', 'unidades'];
            foreach ($required as $field) {
                if (!isset($productData[$field]) || empty($productData[$field])) {
                    $this->data['message'] = "El campo $field es requerido";
                    return;
                }
            }
        
            // Sanitizar datos
            $id = $this->conexion->real_escape_string($productData['id']);
            $nombre = $this->conexion->real_escape_string($productData['nombre']);
            $marca = $this->conexion->real_escape_string($productData['marca']);
            $modelo = $this->conexion->real_escape_string($productData['modelo']);
            $precio = floatval($productData['precio']);
            $unidades = intval($productData['unidades']);
            $detalles = isset($productData['detalles']) ? $this->conexion->real_escape_string($productData['detalles']) : '';
            
            // Imagen por defecto si no se proporciona
            $imagen = !empty($productData['imagen']) ? 
                      $this->conexion->real_escape_string($productData['imagen']) : 
                      'http://localhost/tecweb/practicas/p09/img/imagen.png';
        
            // Query de actualización
            $sql = "UPDATE productos SET 
                    nombre = '$nombre',
                    marca = '$marca',
                    modelo = '$modelo',
                    precio = $precio,
                    unidades = $unidades,
                    detalles = '$detalles',
                    imagen = '$imagen'
                    WHERE id = $id AND eliminado = 0";
        
            if ($this->conexion->query($sql)) {
                if ($this->conexion->affected_rows > 0) {
                    $this->data = [
                        'status' => 'success',
                        'message' => 'Producto actualizado correctamente'
                    ];
                } else {
                    $this->data['message'] = 'No se realizaron cambios o el producto no existe';
                }
            } else {
                $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
            }
        
            $this->conexion->close();
        }

        public function list(){
            $this->data = [
                'status' => 'error',
                'message' => 'Error al listar productos',
                'data' => []
            ];
        
            if($result = $this->conexion->query("SELECT * FROM productos WHERE eliminado = 0")){
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                
                if(!empty($rows)) {
                    $this->data = [
                        'status' => 'success',
                        'message' => 'Productos obtenidos correctamente',
                        'data' => $rows
                    ];
                } else {
                    $this->data['message'] = 'No hay productos disponibles';
                }
                $result->free();
            } else {
                $this->data['message'] = 'Query Error: '.mysqli_error($this->conexion);
            }
        }

        public function search($search) {
            $this->data = [
                'status' => 'error',
                'message' => 'No se encontraron productos',
                'data' => []
            ];
        
            if (!empty($search)) {
                $search = $this->conexion->real_escape_string($search);
                $sql = "SELECT * FROM productos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR marca LIKE '%{$search}%' OR detalles LIKE '%{$search}%') AND eliminado = 0";
                
                if ($result = $this->conexion->query($sql)) {
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    
                    if (!empty($rows)) {
                        $this->data = [
                            'status' => 'success',
                            'message' => 'Productos encontrados',
                            'data' => $rows
                        ];
                    }
                    $result->free();
                } else {
                    $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
                }
            }
        }

        public function single($id){
            $this->data = [
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ];
        
            if(!empty($id)) {
                $id = $this->conexion->real_escape_string($id);
                $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
                
                if ($result = $this->conexion->query($sql)) {
                    $row = $result->fetch_assoc();
                    
                    if(!is_null($row)) {
                        $this->data = [
                            'status' => 'success',
                            'data' => $row
                        ];
                    }
                    $result->free();
                } else {
                    $this->data['message'] = 'Error en la consulta: ' . $this->conexion->error;
                }
            }
            // No cerrar la conexión aquí, déjala abierta para que getData() funcione
        }

        public function singleByName($name){
            $this->data = []; 

            if($name){
                if($stmt = $this->conexion->prepare("SELECT * FROM productos WHERE nombre = ? AND eliminado = 0")){
                    $stmt->bind_param("s", $name); 
                    if($stmt->execute()){
                        $result = $stmt->get_result(); 
                        $this->data=$result->fetch_assoc() ?? []; 
                    }
                    $stmt->close(); 
                }
            }
            $this->conexion_close(); 

        }

        public function getData() {
            // No cerrar la conexión aquí, déjala abierta para operaciones posteriores
            return json_encode($this->data, JSON_PRETTY_PRINT);
        }
        
        // Añade este método para cerrar conexión explícitamente cuando sea necesario
        public function closeConnection() {
            if(isset($this->conexion)) {
                $this->conexion->close();
            }
        }
    }
?>