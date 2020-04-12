<?php

namespace Hcode;

use Rain\Tpl;
use \Hcode\Util\Variaveis;

class Page {

    private $tpl;
    private $options =[];
    private $deafaults = [
        "header"=>true,
        "footer"=>true,
        "data"=>[]
    ];

    public function __construct($opts = array(), $tpl_dir = "/views/") {
        $path_app=Variaveis::_getPathApp();
        $this->options = array_merge($this->deafaults, $opts);

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$path_app.$tpl_dir,
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"].$path_app."/views-cache/",
            //"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
            //"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"         => false
           );
        
        Tpl::configure( $config );   

        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        if ($this->options["header"]) {
            $this->tpl->draw("header");
        }
        

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
        if ($this->options["footer"]) {
            $this->tpl->draw("footer");
        }
    }

}

?>