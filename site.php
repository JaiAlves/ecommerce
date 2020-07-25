<?php 

use \Hcode\Page;
use \Hcode\Model\Product;
use \Hcode\Model\Category;
use \Hcode\Model\Cart;
use \Hcode\Model\User;
use \Hcode\Model\Address;

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

    $pagination = $category->getProductsPage($page, 8);

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

$app->get("/products/:desurl", function($desurl) {
    $product = new Product();

    //obs na procedure que faz o insert eu faco um update no desurl com o valor do idproduct (obtido no insert)
    $product->_get((int) $desurl);

    $page = new Page();

    $page->setTpl("product-detail",[
        'product'=>$product->getValues(),
        'categories'=>$product->getCategories()
    ]);

});

$app->get("/cart", function(){
    $cart = Cart::getFromSession();    

    $page = new Page();

    $data =['cart'=>$cart->getValues(),
         'products'=>$cart->getProducts()];

    $page->setTpl("cart", 
                ['cart'=>$cart->getValues(),
                'products'=>$cart->getProducts(),
                'error'=>Cart::getMsgError()
            ]);
});

$app->get("/cart/:idproduct/add", function($idproduct) {
    $product = new Product();
    $product->_get((int) $idproduct);

    $cart = Cart::getFromSession();

    $qtd =(isset ($_GET["qtd"])) ? (int)$_GET["qtd"] : 1;

    for ($i=1; $i<=$qtd; $i++) {
        $cart->addProduct($product, $i==$qtd); //so atualiza o frete no ultimo produto
    }

    header("Location: /cart");
    exit;
});

$app->get("/cart/:idproduct/minus", function($idproduct) {
    $product = new Product();
    $product->_get((int) $idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product);

    header("Location: /cart");
    exit;
});

$app->get("/cart/:idproduct/remove", function($idproduct) {
    $product = new Product();
    $product->_get((int) $idproduct);

    $cart = Cart::getFromSession();

    $cart->removeProduct($product, true);

    header("Location: /cart");
    exit;
});


$app->post("/cart/freight", function(){
    $cart = Cart::getFromSession();

    $cart->setFreight($_POST['zipcode']);

    header("Location: /cart");
    exit;
});

$app->get("/checkout", function(){
    User::verifyLogin(false);

    $cart = Cart::getFromSession();
    $address = new Address();

    $page = new Page();
    $page->setTpl("checkout", [
        "cart"=>$cart->getValues(),
        "address"=>$address->getValues()
    ]);
});


$app->get("/login", function(){

    $page = new Page();
    $page->setTpl("login");
});

?>