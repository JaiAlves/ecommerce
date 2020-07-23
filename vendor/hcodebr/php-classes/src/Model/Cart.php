<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use Hcode\Model\User;
use \Hcode\Util\Variaveis;

class Cart extends Model {
    const SESSION = "Cart";
    const SESSION_ERROR="CartError";

    public static function getFromSession() {
        $cart = new Cart();
        $cart->setvlfreight((float) 0);
        $cart->setvlsubtotal((float) 0);
        $cart->setvltotal((float) 0);

        if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart']>0) {
            $cart->get((int) $_SESSION[Cart::SESSION]['idcart']);
            if ($cart->getvlfreight()==NULL)$cart->setvlfreight((float) 0);
            if ($cart->getvlsubtotal()==NULL)$cart->setvlsubtotal((float) 0);
            if ($cart->getvltotal()==NULL)$cart->setvltotal((float) 0);
        } else {
            $cart->getFromSessionID();

            if (!(int) $cart->getidcart()>0) {
        
                $cart->setdessessionid(session_id());
                $cart->setdeszipcode(null);
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

    public function addProduct(Product $product, $updateFreight=false) {
        $sql = new Sql();
  
        $strSql = "insert into tb_cartsproducts (idcart, idproduct) 
                    values (" .$this->getidcart() . "," .$product->getidproduct() .")";

        $sql->query($strSql);

        if ($updateFreight) $this->getCalculateTotal();
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

        $this->getCalculateTotal();
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

    public function getProductsTotals() {
        $strSql = "SELECT  sum(vlprice) as vlprice,
                            sum(vlwidth) as vlwidth,
                            sum(vlheight) as vlheight,
                            sum(vllength) as vllength,
                            sum(vlweight) as vlweight,
                            count(*) as nrqtd
                    FROM tb_products a
                    inner join tb_cartsproducts b on a.idproduct = b.idproduct
                    where b.idcart = " .$this->getidcart() ."
                    and dtremoved is null";

        $sql = new Sql();

        
        $results = $sql->select($strSql);

        if (count($results) >0) {
            return $results[0];
        }

        return [];
        
    }


    public function setFreight($nrzipcode) {
        $nrzipcode = str_replace("-","",$nrzipcode);

        $totals = $this->getProductsTotals();

        if ($nrzipcode!="" && $totals['nrqtd'] >0) {
            if($totals['vlheight']<2) $totals['vlheight']=2;

            if($totals['vllength']<16) $totals['vllength']=16;

            $qs =http_build_query([
                'nCdEmpresa'=>'',
                'sDsSenha'=>'',
                'nCdServico'=>'40010',
                'sCepOrigem'=>'09853120',
                'sCepDestino'=>$nrzipcode ,
                'nVlPeso'=>$totals['vlweight'],
                'nCdFormato'=>'1',
                'nVlComprimento'=>$totals['vllength'],
                'nVlAltura'=>$totals['vlheight'],
                'nVlLargura'=>$totals['vlwidth'],
                'nVlDiametro'=>'0',
                'sCdMaoPropria'=>'N',
                'nVlValorDeclarado'=>0, //$totals['vlprice'],
                'sCdAvisoRecebimento'=>'N'       
            ]);

            

           $xml = simplexml_load_file("http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?".$qs);

           $result = $xml->Servicos->cServico;

            if ($result->Erro!="0") {
                Cart::setMsgError($result->MsgErro);
                return;
            }  else {
                Cart::clearMsgError();
            }
            

            $this->setnrdays($result->PrazoEntrega);
            $this->setvlfreight(Cart::formatValueToDecimal($result->Valor));
            $this->setdeszipcode($nrzipcode);

            $this->save();

            return  $result;
           
        } 
            /*
            {
                "Servicos": {
                    "cServico": {
                        "Codigo": "40010",
                        "Valor": "21,00",
                        "PrazoEntrega": "3",
                        "ValorMaoPropria": "0,00",
                        "ValorAvisoRecebimento": "0,00",
                        "ValorValorDeclarado": "0,00",
                        "EntregaDomiciliar": "S",
                        "EntregaSabado": "S",
                        "Erro": "0",
                        "MsgErro": {},
                        "ValorSemAdicionais": "21,00",
                        "obsFim": {}
                    }
                }
            }
            */  
    }

    public static function setMsgError($msg) {
        $_SESSION[Cart::SESSION_ERROR] = $msg;
    }

    public static function formatValueToDecimal($value):float {
        return str_replace(',','.', str_replace('.','',$value));
    }

    public static function getMsgError() {
        $msg = (isset($_SESSION[Cart::SESSION_ERROR])) ? $_SESSION[Cart::SESSION_ERROR] : "";
        Cart::clearMsgError();

        return $msg;
    }

    public static function clearMsgError() {
        $_SESSION[Cart::SESSION_ERROR] = NULL;
    }

    public function updateFreight() {
        if ($this->getdeszipcode() != '') {
            $this->setFreight($this->getdeszipcode());
        }
    }


    public function getValues() {
        $this->getCalculateTotal();

        return parent::getValues();
    }

    public function getCalculateTotal() {
        $this->updateFreight();

        $totals = $this->getProductsTotals();

        $this->setvlsubtotal($totals['vlprice']);
        $this->setvltotal($totals['vlprice']+$this->getvlfreight());
    }
}

?>