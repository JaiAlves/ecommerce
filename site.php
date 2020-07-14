<?php 

use \Hcode\Page;
use \Hcode\Model\Product;

$app->get('/', function() {
    $products = Product::listAll();

	$page = new Page();
    $page->setTpl("index", [
        'products'=>Product::checkList($products)
    ]);
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