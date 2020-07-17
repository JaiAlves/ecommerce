<?php 

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;

$app->get('/', function() {
    $products = Product::listAll();

	$page = new Page();
    $page->setTpl("index", [
        'products'=>Product::checkList($products)
    ]);
});

//rota qdo eh clicando numa categoria do meno no footer
$app->get('/categories/:idcategory', function($idcategory) {

    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    $category = new Category();

    $category->_get((int) $idcategory);

    $pagination = $category->getProductsPage($page);

    $products =$pagination['data'];
    $total_pages = $pagination['pages'];
   
    $pages = [];

    for ($i=1; $i<=$total_pages ;$i++) {
        array_push($pages, [
            'link'=>'/categories/'.$category->getidcategory(). '?page='.$i,
            'page'=>$i
        ]);
    }

    $page = new Page();

    $page->setTpl("category", 
                 ['category'=>$category->getValues(),
                  'products'=>$products,
                  'pages'=>$pages
    ]);

});

?>