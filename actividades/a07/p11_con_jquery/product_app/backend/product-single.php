<?php
    
    use TECWEB\MYAPI\Products as Products; 
    require_once __DIR__.'/myapi/Products.php'; 
    
    $prodObj = new Products('marketzone'); 
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $prodObj->single($id);
    
    echo json_encode ($prodObj->getData());
?>
