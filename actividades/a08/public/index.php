<?php
require_once '../controller/ProductController.php';

$action = $_POST['action'] ?? $_GET['action'] ?? null;
$controller = new ProductController();

switch ($action) {
    case 'list':
        $controller->listProducts(); 
        break;

    case 'addProduct':
        $data = $_POST; 
        $controller->addProduct($data); 
        break;
    case 'dataProduct':
         $search = $_GET['search']; 
         $controller->dataProduct($search); 
        break;
    
    case 'eliminar':
        $id = $_POST['id']; 
        $controller->deleteData($id); 
        break;
    
    case 'editProduct':
        $data = $_POST;
        $controller->editProduct($data);
        break;

    case 'singleProduct':
        $id = $_POST['id']; 
        $controller->singleProduct($id); 
        break; 

    case 'searchProduct':
        $search = $_GET['search']; 
        $controller->searchProduct($search);
        break;
    
    
    default:
        echo json_encode(['error' => 'Accion no valida'], JSON_PRETTY_PRINT);
        break;

}
?>