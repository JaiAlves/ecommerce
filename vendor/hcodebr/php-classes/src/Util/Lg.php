<?php 

namespace Hcode\Util;


class Lg {

	private $path;
	
	//$path = "C:\\projetos\\ecommerce\\log\\"
	
	public function __construct($path = "C:\\projetos\\ecommerce\\log\\"){
		$this->path = $path;
	}
	
	
	public function log($msg) {
		date_default_timezone_set("America/Sao_Paulo");
		setlocale(LC_ALL, 'pt_BR');
 
		$nome_arquivo = date("d-m-yy");

		$file = fopen($this->path . $nome_arquivo ."_ecommerce.log" ,"a");

		fwrite($file, date("d-m-y H:i:s")." ".$msg ."\n");
		//fwrite($file, " ");		
		//fwrite($file, $msg ."\n");
	}

}


 ?>
