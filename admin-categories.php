<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Util\Variaveis;

//rota para template categorias
$app->get('/admin/categories', function() {
    $categories = Category::listAll();

	$page = new PageAdmin();


    //$page->setTpl("categories",['categories'=>$categories] );        
    $page->setTpl("categories",array("categories"=>$categories) );        
});

$app->get('/admin/categories/create', function() {
    User::verifyLogin();
    
    $page = new PageAdmin([
        "header"=>true,
        "footer"=>true
    ], "/views/admin/", "header-entity", "footer-entity");

    $page->setTpl("categories-create");
});


$app->post('/admin/categories/create', function() {
    User::verifyLogin();
    
    $category =  new Category();

    $category->setData($_POST);

    $category->save();
    
    header("Location: ".Variaveis::_getPathApp()."/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory', function($idcategory) {
    User::verifyLogin();

    $category = new Category();

    $category->_get($idcategory);
    
    $page = new PageAdmin([
        "header"=>true,
        "footer"=>true
    ], "/views/admin/", "header-entity", "footer-entity");

    $page->setTpl("categories-update",array(
        "category"=>$category->getValues()
    ));
});

$app->post('/admin/categories/:idcategory', function($idcategory) {
    User::verifyLogin();
    
    $category = new Category();

    $category->_get((int) $idcategory);

    $category->setData($_POST);

    $category->update();

    header("Location: ".Variaveis::_getPathApp()."/admin/categories");
    exit;
});

$app->get('/admin/categories/:idcategory/delete', function($idcategory) {
    User::verifyLogin();
    
    $category = new Category();

    $category->delete((int) $idcategory);

    header("Location: ".Variaveis::_getPathApp()."/admin/categories");
    exit;

});

//rota qdo eh clicando numa categoria do meno no footer
$app->get('/categories/:idcategory', function($idcategory) {
    $category = new Category();

    $category->_get((int) $idcategory);

    $page = new Page();

    $page->setTpl("category", 
                 ['category'=>$category->getValues(),
                  'products'=>[]
    ]);

    /*
    $category->setData($_POST);

    $category->update();

    header("Location: /admin/categories");
    exit;
    */
});

?>