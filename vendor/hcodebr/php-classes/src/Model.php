<?php 

namespace Hcode;

use \Hcode\Util\Lg;

class Model {

	private $values = [];

	public function __call($name, $args)
	{

		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));

		//if (in_array($fieldName, $this->fields))
	//	{
			
			switch ($method)
			{

				case "get":
					return $this->values[$fieldName];
				break;

				case "set":
					$this->values[$fieldName] = $args[0];
				break;

			}

		//}

	}

	public function setData($data){
		//$lg =new  Lg();
		//$lg->log('setData');

		
		foreach ($data as $key => $value){
			//$lg->log(''.$key.'='.$value);
			//var_dump(''.$key.'='.$value);
			$this->{"set".$key}($value);
		}
	}


	public function getValues()
	{

		return $this->values;

	}

}

 ?>