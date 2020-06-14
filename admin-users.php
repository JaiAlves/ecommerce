<?php 

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Util\Variaveis;

$app->get('/admin/users', function() {
    User::verifyLogin();

    $users = User::listAll();

    $page = new PageAdmin();

    $page->setTpl("users", array("users"=>$users));
});

$app->get('/admin/users/create', function() {
    User::verifyLogin();
    
    if (Variaveis::_getPathApp()=="") {
        $page = new PageAdmin([
            "header"=>true,
            "footer"=>true
        ], "/views/admin/", "header", "footer");
    } else {
        $page = new PageAdmin([
            "header"=>true,
            "footer"=>true
        ], "/views/admin/", "header_site", "footer_site");
    }

    $page->setTpl("users-create");
});

$app->get('/admin/users/:iduser', function($iduser) {
    User::verifyLogin();

    $user = new User();

    $user->_get((int) $iduser);
    
    if (Variaveis::_getPathApp()=="") {
        $page = new PageAdmin([
            "header"=>true,
            "footer"=>true
        ], "/views/admin/", "header", "footer");
    } else {
        $page = new PageAdmin([
            "header"=>true,
            "footer"=>true
        ], "/views/admin/", "header_site", "footer_site");
    }

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
    
    header("Location: ".Variaveis::_getPathApp()."/admin/users");
    exit;
});

$app->get('/admin/users/:iduser/delete', function($iduser) {
    User::verifyLogin();
    
    $user = new User();

    $user->delete((int) $iduser);

    header("Location: ".Variaveis::_getPathApp()."/admin/users");
    exit;

});

$app->post('/admin/users/:iduser', function($iduser) {
    User::verifyLogin();
    
    $user = new User();

    $user->_get((int) $iduser);

    $_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

    $user->setData($_POST);

    $user->update();

    header("Location: ".Variaveis::_getPathApp()."/admin/users");
    exit;

});

?>