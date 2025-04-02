<?php

require_once __DIR__ . '/../model/ProductModel.php';

class ProductController {
    private $model;

    public function __construct() {
        $this->model = new ProductModel(); 
    }

    public function listProducts() {
        $response = $this->model->listProduct(); 
        echo $response; 
    }

    public function addProduct($data) {
        $response = $this->model->addProduct(
            $data['nombre'],
            $data['marca'],
            $data['modelo'],
            $data['precio'],
            $data['detalles'],
            $data['unidades'],
            $data['imagen']
        );
        echo $response; 
    }

    public function dataProduct($search) {
        $response = $this->model->dataProduct($search); 
        echo $response;
    }

    public function deleteData($id) {
        $response = $this->model->deleteData($id);
        echo $response; 
    }

    public function editProduct($data) {
        $response = $this->model->editProduct(
            $data['id'],
            $data['nombre'],
            $data['marca'],
            $data['modelo'],
            $data['precio'],
            $data['detalles'],
            $data['unidades'],
            $data['imagen']
        );
        echo $response; 
    }

    public function singleProduct($id) {
        $response = $this->model->singleProduct($id); 
        echo $response; 
    }

    public function searchProduct($search) {
        $response = $this->model->searchProduct($search);
        echo $response; 
    }
 }
?>