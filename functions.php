<?php

use \Hcode\Util\Variaveis;
use Hcode\Model\User;

function formatPrice(float $value) {
    return number_format($value, 2, ",", ".");
}

function pathLink() {
    return Variaveis::_getPathApp();
}


function usuLogado() {
    $usu = User::usuLogado();

    if ($usu!=null) {
        return $usu->getdesperson();
    } else {
        return "";
    }
}

?>