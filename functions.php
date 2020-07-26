<?php

use \Hcode\Util\Variaveis;
use Hcode\Model\User;

function formatPrice($value) {
    if ($value==NULL) {
        $value=(float)0;
    }
     
    return number_format((float)$value, 2, ",", ".");
}

function pathLink() {
    return Variaveis::_getPathApp();
}

function checkLogin($inadmin = true) {
    return User::checkLogin($inadmin);
}

function getUserName() {
    $usu = User::usuLogado();

    if ($usu!=null) {
        return $usu->getdesperson();
    } else {
        return "";
    }
}

?>