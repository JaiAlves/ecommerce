<?php

use \Hcode\Util\Variaveis;

function formatPrice(float $value) {
    return number_format($value, 2, ",", ".");
}

function pathLink() {
    return Variaveis::_getPathApp();
}
?>