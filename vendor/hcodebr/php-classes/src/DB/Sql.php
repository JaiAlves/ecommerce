<?php 

namespace Hcode\DB;

use Hcode\Util\Lg;

class Sql {

	const HOSTNAME = "127.0.0.1";
	const USERNAME = "root";
	const PASSWORD = "Senhanova1@";
	const DBNAME = "db_ecommerce";

	private $conn;
	private $lg;

	public function __construct()
	{
		$this->lg = new Lg("C:\\projetos\\ecommerce\\log\\sql\\");

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
			Sql::USERNAME,
			Sql::PASSWORD
		);

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array(), $debug = false):array
	{
		if ($debug) {
			$this->log('strSql= '.$rawQuery);
			if (!isset($params)) {
				$this->log('array vazio ...');
			} else {
				$this->log('array: '.implode("|  ", $params));
			}
		}

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}
	
	public function log($msg) {
		/*
		date_default_timezone_set("America/Sao_Paulo");
		setlocale(LC_ALL, 'pt_BR');

 
		$nome_arquivo = date("d-m-y H") ." hs ";

		$file = fopen("C:\\projetos\\ecommerce\\log\\sql\\" . $nome_arquivo .".txt" ,"a");

		fwrite($file, date("d-m-y H:i:s"));
		fwrite($file, " ");		
		fwrite($file,'strSql= '. $msg ."\n");
		*/
		$this->lg->log($msg);
		
	}

}


 ?>
