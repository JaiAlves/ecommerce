<?php
namespace Hcode\Model;

use \Hcode\DB\Sql;
use Hcode\Model;
use \Hcode\Util\Lg;

date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');


class User extends Model{
    const SESSION = "User";

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
            header("Location: /admin/login");
            exit;
        }
    }

    public function _get($iduser) {
        $sql = new Sql();
        $strSql ="SELECT * FROM tb_users a INNER JOIN tb_persons b  USING(idperson) WHERE a.iduser =:IDUSER ORDER BY b.desperson ";

        $results = $sql->select($strSql, array(":IDUSER"=>$iduser), true);

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
            window.location.href = '/admin/users';
          </script>";
          exit;
      
            //throw new \Exception("Nao é permitido excluir o usuario logado.");
        }

        $sql = new Sql();
        $strSql = "CALL sp_users_delete(:IDUSER)";

        $sql->select($strSql, array(":IDUSER"=>$iduser), true);

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

        $results = $sql->select($strSql, $array, true);

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

        $results = $sql->select($strSql, $array, true);

        $this->setData($results[0]);
    }

}

?>