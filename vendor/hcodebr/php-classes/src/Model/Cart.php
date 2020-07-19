<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use Hcode\Model\User;
use \Hcode\Util\Variaveis;

class Cart extends Model {
    const SESSION = "Cart";

    public static function getFromSession() {
        $cart = new Cart();
        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart']>0) {
            $cart->get((int) $_SESSION[Cart::SESSION]['idcart']);
        } else {
            $cart->getFromSessionID();

            if (!(int) $cart->getidcart()>0) {
        
                $cart->setdessessionid(session_id());
                $cart->setdeszipcode(null);
                $cart->setvlfreight(null);
                $cart->setnrdays(null);
                $cart->setiduser(null);

                //se acha usu logado entao seta o iduser
                if (User::checkLogin(false)) {
                    $user = User::usuLogado();
                    $cart->setiduser($user->getiduser());
                }

                $cart->save();
                $cart->setToSession();
            }
        }

        return $cart;
    }

    public function setToSession(){
        $_SESSION[Cart::SESSION] = $this->getValues();
    }

    public function getFromSessionID() {
        $sql = new Sql();

        $results = $sql->select("select * from tb_carts where dessessionid = ".session_id());

        if (count($results)>0) {
            $this->setData($results[0]);
        } else {
            $this->setidcart(0);
        }

    }

    public function get(int $idcart) {
        $sql = new Sql();

        $results = $sql->select("select * from tb_carts where idcart = ".$idcart);

        if (count($results)>0) {
            $this->setData($results[0]);
        }
        
    }

    public function save() {
        $sql = new Sql();

        $strSql = "CALL sp_carts_save (:idcart ,:dessessionid ,:iduser ,:deszipcode ,:vlfreight ,:nrdays)";

        $parametros=[':idcart'=>$this->getidcart() , 
                    ':dessessionid'=>$this->getdessessionid() ,
                    ':iduser'=>$this->getiduser() ,
                    ':deszipcode'=>$this->getdeszipcode() ,
                    ':vlfreight'=>$this->getvlfreight() ,
                    ':nrdays'=>$this->getnrdays()];


        $results =$sql->select($strSql, $parametros);

        $this->setData($results[0]);

    }

    public function addProduct(Product $product) {
        $sql = new Sql();
  
        $strSql = "insert into tb_cartsproducts (idcart, idproduct) 
                    values (" .$this->getidcart() . "," .$product->getidproduct() .")";

        $sql->query($strSql);
    }

    public function removeProduct(Product $product, $all = false ) {
        $sql = new Sql();

        $strSql = "update tb_cartsproducts set dtremoved=NOW() where idcart = ".$this->getidcart() 
              ." and idproduct =".$product->getidproduct();

        if (!$all) {
            $strSql = $strSql ." and dtremoved is null limit 1";
        } else {
            $strSql = $strSql ." and dtremoved is null ";
        }

        $sql->query($strSql);
    }

    public function getProducts() {
        $sql = new Sql();
        $strSql = "SELECT   a.idcart, 
                            b.idproduct, 
                            b.desproduct,
                            b.vlprice,
                            b.vlwidth,
                            b.vlheight,
                            b.vllength,
                            b.desurl,
                            count(*) as nrqtd,
                            sum(b.vlprice) as vltotal
                    FROM tb_cartsproducts a 
                    inner join tb_products b on a.idproduct = b.idproduct
                    where dtremoved is null
                        and a.idcart = " .$this->getidcart() ."
                    group by a.idcart, 
                                b.idproduct, 
                                b.desproduct,
                                b.vlprice,
                                b.vlwidth,
                                b.vlheight,
                                b.vllength,
                                b.desurl
                    order by b.desproduct";
                     
        return Product::checkList($sql->select($strSql));
    }

}

?>