<?php
    use TECWEB\MYAPI\Products as Products; 
    require_once __DIR__.'/myapi/Products.php'; 
    
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $prodObj = new Products('marketzone'); 

    if(!empty($search)){
        $prodObj->search($search); 
    }
    else{
        $prodObj->singleByName($search); 
    }
    
    echo json_encode ($prodObj->getData()); 
?>