<?php

namespace Hcode;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options =[];
    private $deafaults = [
        "data"=>[]
    ];

    public function __construct($opts = array(), $tpl_dir = "/views/") {
        $app_folder = "/ecommerce";

        $this->options = array_merge($this->deafaults, $opts);

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$app_folder.$tpl_dir,
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"].$app_folder."/views-cache/",
            "debug"         => false
           );
        
        Tpl::configure( $config );   

        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        $this->tpl->draw("header");

    }

    private function setData($data = array()) {

        foreach ($data as $key => $value) { 
            $this->tpl->assign($key, $value);
        }
    }

    public function setTpl($nome, $data = array(), $returnHTML = false) {

        $this->setData($data);

        return $this->tpl->draw($nome, $returnHTML);

    }

    public function __destruct() {
        $this->tpl->draw("footer");

    }

}

?>