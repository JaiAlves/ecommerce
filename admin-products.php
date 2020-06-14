<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;
use \Hcode\Util\Variaveis;

$app->get('/admin/products', function() {
    User::verifyLogin();

    $products = Product::listAll();

    $page = new PageAdmin();

    $page->setTpl("products", array("products"=>$products));
});

$app->get('/admin/products/create', function() {
    User::verifyLogin();

    $page = new PageAdmin([
        "header"=>true,
        "footer"=>true
    ], "/views/admin/", "header", "footer");

    $page->setTpl("products-create");    
});

$app->post('/admin/products/create', function() {
    User::verifyLogin();

    $product = new Product();

    $product->setData($_POST);

    $product->save();
    
    header("Location: ".Variaveis::_getPathApp()."/admin/products");
    exit;    

});

$app->get('/admin/products/:idproduct/delete', function($idproduct) {
    User::verifyLogin();
    
    $product = new Product();

    $product->delete((int) $idproduct);

    header("Location: ".Variaveis::_getPathApp()."/admin/products");
    exit;

});

$app->get('/admin/products/:idproduct', function($idproduct) {
    User::verifyLogin();

    $product = new Product();

    $product->_get((int) $idproduct);
    
    $page = new PageAdmin([
        "header"=>true,
        "footer"=>true
    ], "/views/admin/", "header", "footer");

    $page->setTpl("products-update", array(
        "product"=>$product->getValues()
    ));
});

$app->post('/admin/products/:idproduct', function($idproduct) {
    User::verifyLogin();
    
    $product = new Product();

    $product->_get((int) $idproduct);

    $product->setData($_POST);

    //var_dump($_POST);
    //exit;

    $product->save();

    $product->setPhoto($_FILES['file']);

    header("Location: ".Variaveis::_getPathApp()."/admin/products");
    exit;

});



?>