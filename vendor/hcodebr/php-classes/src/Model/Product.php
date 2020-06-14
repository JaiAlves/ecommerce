<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use \Hcode\Util\Variaveis;

class Product extends Model {

    public static function listAll() {
        $sql = new Sql();
        $strSql ="SELECT * FROM db_ecommerce.tb_products order by desproduct";

        return $sql->select($strSql);
    }    

    public static function checkList($list) {
        foreach ($list as &$row) { //o fato de colocar & faz com que possa utilizar a variavel por referencia dentro do looping
            $p = new Product();
            $p->setData($row);
            $row = $p->getValues();
        }

        return $list; //como utilizamos & na var row que veio de dentro da lista, entao qdo fizemos a alteracao na row automaticamente alteramos o conteudo da list
    }

    public function save() {
        $idproduct=0;
        if ($this->getidproduct()!=null) {
            $idproduct=$this->getidproduct();
        }
        $sql = new Sql();

        $strSql = "CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)";

        $array =  array(":idproduct"    =>$idproduct, 
                        ":desproduct"   =>$this->getdesproduct(),   
                        ":vlprice"      =>$this->getvlprice(),
                        ":vlwidth"      =>$this->getvlwidth(),
                        ":vlheight"      =>$this->getvlheight(),
                        ":vllength"      =>$this->getvllength(),
                        ":vlweight"      =>$this->getvlweight(),
                        ":desurl"      =>$this->getdesurl()
    );       

        $results = $sql->select($strSql, $array);

        $this->setData($results[0]);

    }   
    
    public function _get($idproduct) {
        $sql = new Sql();
        $strSql ="SELECT * FROM db_ecommerce.tb_products WHERE idproduct= :idproduct";

        $results = $sql->select($strSql, array(":idproduct"=>$idproduct));

		if (count($results) === 0) {
			throw new \Exception("Priduto nao encontrado.");
        }
        //$lg->log("results[0]=".implode(";", $results));

        $this->setData($results[0]);
    }

    public function getValues() {
        $this->checkPhoto();

        $values = parent::getValues();

        //add desphoto
        return $values;
    }

    private function getPathImg() {
        return     "". $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 
                    Variaveis::_getPathApp() .
                    "res" . DIRECTORY_SEPARATOR . 
                    "site" . DIRECTORY_SEPARATOR . 
                    "img" . DIRECTORY_SEPARATOR .
                    "products" . DIRECTORY_SEPARATOR;
    }

    private function checkPhoto() {
        $url =  "/res/site/img/" .  "product.jpg";
   
        if (file_exists($this->getPathImg() . $this->getidproduct() . ".jpg")) {
                $url =  "/res/site/img/products/" . $this->getidproduct() . ".jpg";
            }
    
           return $this->setdesphoto($url);
    }

    public function delete($idproduct) {
        $sql = new Sql();
        $strSql = "DELETE FROM tb_products WHERE idproduct=:idproduct";

        $sql->query($strSql, array(":idproduct"=>$idproduct));
    }    

    public function setPhoto($file) {
        $image=null;
        $extention = explode('.', $file['name']); //pega tdo oq te logo apos o "."
        $extention = end($extention); //pega so o ultimo

        switch ($extention) {
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($file["tmp_name"]); //pega o file temporario q esta no servidor
            break;

            case "gif":
                $image = imagecreatefromgif($file["tmp_name"]); //pega o file temporario q esta no servidor
            break;
            case "png":
                $image = imagecreatefrompng($file["tmp_name"]); //pega o file temporario q esta no servidor
            break;
        }

        if ($image!=null) {
            imagejpeg($image, $this->getPathImg() . $this->getidproduct() .".jpg");

            imagedestroy($image);
    
            $this->checkPhoto();
        }
    }

}

?>