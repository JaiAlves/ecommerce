<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Util\Variaveis;
use \Hcode\Util\Lg;

$app->get('/admin', function() {
    User::verifyLogin();
    
    $user = new User();

    $iduser = (int) $_SESSION[User::SESSION]["iduser"];

    $lg = new Lg();
    $lg->log("[rota admin] iduser =".$iduser);

    $user->_get((int) $iduser);

	$page = new PageAdmin();

    //$page->setTpl("index");
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

    header("Location: ".Variaveis::_getPathApp()."/admin");
    exit;

});


$app->get('/admin/logout', function() {
    User::logout();
    
    header("Location: ".Variaveis::_getPathApp()."/admin/login");
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

    header("Location: ".Variaveis::_getPathApp()."/admin/forgot/sent");
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

?>