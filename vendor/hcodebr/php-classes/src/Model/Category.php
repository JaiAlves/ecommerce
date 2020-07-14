<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use \Hcode\Util\Variaveis;

class Category extends Model {

    public static function listAll() {
        $sql = new Sql();
        $strSql ="SELECT * FROM db_ecommerce.tb_categories order by descategory";

        return $sql->select($strSql);
    }    

    public function save() {
        $sql = new Sql();

        $strSql = "CALL sp_categories_save(:idcategory, :descategory)";

        $array =  array(":idcategory"    =>0, 
                        ":descategory"   =>$this->getdescategory());        

        $results = $sql->select($strSql, $array);

        $this->setData($results[0]);

        Category::updateFile();
    }   
    
    public function _get($idcategory) {
        $sql = new Sql();
        $strSql ="SELECT * FROM db_ecommerce.tb_categories WHERE idcategory= :idecategory";

        $results = $sql->select($strSql, array(":idecategory"=>$idcategory));

		if (count($results) === 0) {
			throw new \Exception("Categoria nao encontrada.");
        }
        //$lg->log("results[0]=".implode(";", $results));

        $this->setData($results[0]);
    }
    
    public function update() {
        $sql = new Sql();

        $strSql = "CALL sp_categories_save(:idcategory, :descategory)";

        $array =  array(":idcategory"=>$this->getidcategory(), 
                        ":descategory"=>$this->getdescategory());        

        $results = $sql->select($strSql, $array);

        $this->setData($results[0]);        
        
        Category::updateFile();
    }

    public function delete($idcategory) {
        $sql = new Sql();
        $strSql = "DELETE FROM tb_categories WHERE idcategory=:idcategory";

        $sql->query($strSql, array(":idcategory"=>$idcategory));

        Category::updateFile();

    }    

    public static function updateFile() {
        $categories = Category::listAll();

        $html =[];

        foreach($categories as $row) {
            array_push($html, '<li><a href="'.Variaveis::_getPathApp(). '/categories/'.$row['idcategory'] . '">'.$row['descategory'] .'</a></li>');
        }

        file_put_contents($_SERVER['DOCUMENT_ROOT'].Variaveis::_getPathApp().DIRECTORY_SEPARATOR ."views".DIRECTORY_SEPARATOR. "categories-menu.html", 
            implode('',$html));

    }

    public function getProducts($related=true) {
        $sql = new Sql();

        if ($related==true) {
            return $sql->select("select a.* from tb_products a 
                                inner join tb_productscategories b on a.idproduct = b.idproduct 
                                where b.idcategory = :idcategory ", 
                   [
                       ':idcategory'=>$this->getidcategory()
                   ]);
        } 

        return $sql->select("select * from tb_products where idproduct not in (
                            select a.idproduct from tb_products a 
                            inner join tb_productscategories b on a.idproduct = b.idproduct)");
    }

    public function addProduct($idproduct){
        $sql = new Sql();

        $strSql = "INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)";
        $parametros =[':idcategory'=>$this->getidcategory(), ':idproduct'=>$idproduct];

        $sql->query($strSql, $parametros);
    }

    public function removeProduct($idproduct){
        $sql = new Sql();

        $strSql ="DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct";
        $parametros =[':idcategory'=>$this->getidcategory(), ':idproduct'=>$idproduct];

        $result = $sql->query($strSql,$parametros);

    }    
}

?>