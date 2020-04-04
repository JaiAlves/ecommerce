<?php 

session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

    $page->setTpl("index");

});

$app->get('/admin', function() {
    User::verifyLogin();
    
	$page = new PageAdmin();

    $page->setTpl("index");

});

$app->get('/admin/login', function() {
    
	$page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("login");

});

$app->post('/admin/login', function() {
    
    User::login($_POST["login"], $_POST["password"]);

    header("Location: /admin");
    exit;

});


$app->get('/admin/logout', function() {
    User::logout();
    
    header("Location: /admin/login");
    exit;

});

$app->get('/admin/users', function() {
    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl("users", array("users"=>$users));
});

$app->get('/admin/users/create', function() {
    User::verifyLogin();
    
    $page = new PageAdmin();

    $page->setTpl("users-create");
});

$app->get('/admin/users/:iduser', function($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->_get((int) $iduser);
    
    $page = new PageAdmin();

    $page->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));
});

$app->post('/admin/users/create', function() {
    User::verifyLogin();
    
    $user =  new User();

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    //encripta a senha antes de gravar no banco
    $_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, ["cost"=>12]);    

    $user->setData($_POST);

    $user->save();
    
    header("Location: /admin/users");
    exit;
});

$app->get('/admin/users/:iduser/delete', function($iduser) {
    User::verifyLogin();
    
    $user = new User();

    $user->delete((int) $iduser);

    header("Location: /admin/users");
    exit;

});

$app->post('/admin/users/:iduser', function($iduser) {
    User::verifyLogin();
    
    $user = new User();

    $user->_get((int) $iduser);

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");
    exit;

});

//chama a tela para digitar o email e resetar a senha
$app->get('/admin/forgot', function() {

    //desenha a tela do forgot
	$page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);
    $page->setTpl("forgot");    

});

//recebe o email enviado da tela de recuperacao de senha (q foi acessada por sua vez via get)
$app->post('/admin/forgot', function() {
    $user = User::getForgot($_POST["email"]);

    header("Location: /admin/forgot/sent");
    exit;

});

//recebe o redirecionamento do email enviado com sucesso (rota anterior)
$app->get('/admin/forgot/sent', function() {

    //desenha a tela do email enviado
	$page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("forgot-sent");        

});

//rota que o usu acessa qdo recebe o email
$app->get('/admin/forgot/reset', function() {
    $user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $page->setTpl("forgot-reset", array("name"=>$user["desperson"], "code"=>$_GET["code"]));        
});


//rota que recebe a nova senha q o usu digita
$app->post('/admin/forgot/reset', function() {
    $forgot = User::validForgotDecrypt($_POST["code"]);

    //da baixa nessa sol no banco de dados, para impedir q ela seja reutilizada
    User::closeRecovery($forgot["idrecovery"]);


    //encripta a senha antes de gravar no banco
    $_POST['password'] = password_hash($_POST["password"], PASSWORD_DEFAULT, ["cost"=>12]);  
    //faz a atualizacao da senha no bco
    User::updatePassword($forgot["iduser"], $_POST["password"]);

	$page = new PageAdmin([
        "header"=>false,
        "footer"=>false
    ]);

    $user = new User();
    $user->_get((int) $forgot["iduser"]);    
    
    $page->setTpl("forgot-reset-success", array("user"=>$user->getValues()));                                  

});

$app->run();

 ?>