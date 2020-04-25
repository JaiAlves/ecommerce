<?php

namespace Hcode;
# coloquei a linha abaixo, sem isso da erro que nao encontra a classe Page
require_once('Page.php');

class PageAdmin extends Page {

        public function __construct($opts = array(), $tpl_dir = "/views/admin/", $header="header", $footer="footer")
        {

                parent::__construct($opts, $tpl_dir, $header, $footer);

        }

}

?>
