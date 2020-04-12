<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use \Hcode\Util\Lg;
use \Hcode\Mailer;
use \Hcode\Util\Variaveis;

class User extends Model{
    const SESSION = "User";
    const SECRET = "@12345$7890*2345";
    const ALGORITIMO = "AES-256-CBC";
    const IV = "wNYtCnelXfOa6uiJ";
    

    public static function login($login, $password) {
        $db = new Sql();

		$results = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		if (count($results) === 0) {
			throw new \Exception("Não foi possível fazer login.");
		}

		$data = $results[0];

        if (password_verify($password, $data["despassword"])) {
            $user = new User();

            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;
        } else {
            throw new \Exception("Usuario inexistente ou senha inválida.", 1);
        }
    }


	public static function logout(){
		$_SESSION[User::SESSION] = NULL;
	}    

    public static function verifyLogin($inadmin = true) {
        if (!isset($_SESSION[User::SESSION]) || //se a sessao nao foi definida
            !$_SESSION[User::SESSION] || //se nao existe user na sessao
            !(int) $_SESSION[User::SESSION]["iduser"] > 0 || //se o id do user na sessao nao for maior q zero
            (bool) $_SESSION[User::SESSION]["inadmin"]!== $inadmin) { //se o user da sessao nao for admin

            //Entao redireciona para pagina de login
            header("Location: ".Variaveis::_getPathApp()."/admin/login");
            exit;
        }
    }

    public function _get($iduser) {
        $sql = new Sql();
        $strSql ="SELECT * FROM tb_users a INNER JOIN tb_persons b  USING(idperson) WHERE a.iduser =:IDUSER ORDER BY b.desperson ";

        $results = $sql->select($strSql, array(":IDUSER"=>$iduser));

		if (count($results) === 0) {
			throw new \Exception("Usuário nao encontrado.");
        }
        //$lg->log("results[0]=".implode(";", $results));

        $this->setData($results[0]);
    }

    public static function listAll() {
        $sql = new Sql();
        $strSql ="SELECT * FROM tb_users a INNER JOIN tb_persons b  USING(idperson) ORDER BY b.desperson ";

        return $sql->select($strSql);
    }

    public function delete($iduser) {
        if ((int) $_SESSION[User::SESSION]["iduser"]===$iduser) {
            echo "<script>
            alert('Nao é permitido excluir o usuario logado.');
            window.location.href = ".Variaveis::_getPathApp()."'/admin/users';
          </script>";
          exit;
      
            //throw new \Exception("Nao é permitido excluir o usuario logado.");
        }

        $sql = new Sql();
        $strSql = "CALL sp_users_delete(:IDUSER)";

        $sql->query($strSql, array(":IDUSER"=>$iduser));

    }

    public function update() {
        $sql = new Sql();

        $strSql = "CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)";

        $array =  array(":iduser"=>$this->getiduser(),
                        ":desperson"    =>$this->getdesperson(), 
                        ":deslogin"     =>$this->getdeslogin(), 
                        ":despassword"  =>$this->getdespassword(), 
                        ":desemail"     =>$this->getdesemail(), 
                        ":nrphone"      =>$this->getnrphone(), 
                        ":inadmin"      =>$this->getinadmin());        

        $results = $sql->select($strSql, $array);

        $this->setData($results[0]);        
    }

    public function save() {
        $sql = new Sql();
        $strSql = "CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)";

        $array =  array(":desperson"    =>$this->getdesperson(), 
                        ":deslogin"     =>$this->getdeslogin(), 
                        ":despassword"  =>$this->getdespassword(), 
                        ":desemail"     =>$this->getdesemail(), 
                        ":nrphone"      =>$this->getnrphone(), 
                        ":inadmin"      =>$this->getinadmin());        

        $results = $sql->select($strSql, $array);

        $this->setData($results[0]);
    }

    public static function getForgot($email) {
        $msgException = "Não foi possível recuperar a senha.";

        $sql = new Sql();
        $strSql ="SELECT * FROM tb_users a INNER JOIN tb_persons b  USING(idperson) WHERE b.desemail = :email";

        $results = $sql->select($strSql, array(":email"=>$email));

        //if (count($results===0)) {
        if(!isset($results) || count($results)===0 ){   
            throw new \Exception($msgException);
        }

        $data = $results[0];
        $desip=$_SERVER["REMOTE_ADDR"];

        $strSql = "CALL sp_userspasswordsrecoveries_create(:iduser, :desip)";

        $results2 = $sql->select($strSql, array(":iduser"=>$data["iduser"], ":desip"=>$desip));

        //if (count($results2===0)) {
        if(!isset($results2)) {
            throw new \Exception($msgException);
        }

        $dataRecovery = $results2[0];

        //$code = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, User::SECRET, $dataRecovery["idrecovery"],  MCRYPT_MODEL_ECB));
        //funcao mcrypt_encrypt foi descontinuada na versao 7.2 do php, entao usamos a openssl....

        $code = base64_encode(openssl_encrypt($dataRecovery["idrecovery"],User::ALGORITIMO, User::SECRET, OPENSSL_RAW_DATA, User::IV));

        $link = Variaveis::_getLink()."/admin/forgot/reset?code=".$code;

        $mailer = new Mailer($data["desemail"], $data["desperson"], "Recuperação de senha do ecommerce!", "forgot", 
            array(
                "name"=>$data["desperson"],
                "link"=>$link
            )
        );


        $mailer->send();

        return $data;

    }

    public static function validForgotDecrypt($code) {
        //$lg = new Lg();
        //$lg->log("Linha 185... cod = ".$code);


        $msgException = "Não foi possível recuperar a senha.";
        $sql = new Sql();

        $idrecovery = openssl_decrypt(base64_decode($code),User::ALGORITIMO, User::SECRET, OPENSSL_RAW_DATA, User::IV);

        if(!isset($idrecovery)){
            throw new \Exception("Erro ao obter recovery");
        }


        $strSql ="SELECT * FROM tb_userspasswordsrecoveries a
                           INNER JOIN tb_users b USING(iduser)
                           INNER JOIN tb_persons c USING(idperson)
                    WHERE a.idrecovery= :idrecovery 
                    AND a.dtrecovery is null 
                    AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR)>=NOW()";

        $results = $sql->select($strSql, array(":idrecovery"=>$idrecovery));


        if(!isset($results) || count($results)===0){   
            throw new \Exception($msgException);
        }

        return $results[0];

    }

    public static function closeRecovery($idrecovery) {
        $sql = new Sql();

        $strSql ="UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery=:idrecovery";
        $sql->query($strSql, array(":idrecovery"=>$idrecovery));

    }

    public static function updatePassword($iduser, $password ) {
        $msgException = "Não foi possível atualizar a senha.";
        $sql = new Sql();


        $strSql ="UPDATE tb_users SET despassword=:despassword WHERE iduser= :iduser";
        $sql->query($strSql, array(":despassword"=>$password,":iduser"=>$iduser));

    }

}

?>