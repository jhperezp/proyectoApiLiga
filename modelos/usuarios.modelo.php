<?php 

require_once "conexion.php";

class ModeloUsuarios{


	/*=============================================
			Mostrar todos los registros
	=============================================*/

	static public function index($FETCH_CLASS = false){

		/*$stmt = Conexion::conectar()->prepare("SELECT id_user, name_user, email_user, token_user, FROM_UNIXTIME(token_exp_user) AS token_exp, date_created_user, date_updated_user FROM users");*/

		$stmt = Conexion::conectar()->prepare("SELECT id_user, name_user, email_user, date_created_user, date_updated_user FROM users");

		$stmt -> execute();

		if($FETCH_CLASS == true){
			return $stmt -> fetchAll(PDO::FETCH_CLASS);
		}else{
			return $stmt -> fetchAll();
		}

	    $stmt -> close();

	    $stmt = null;
	}

	/*=============================================
				Crear un registro
	=============================================*/

	static public function create($datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO users(name_user, email_user, password_user, token_user, token_exp_user, date_created_user, date_updated_user) VALUES (:name_user, :email_user, :password_user, :token_user, :token_exp_user, :date_created_user, :date_updated_user)");

		$stmt -> bindParam(":name_user", $datos["name_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":email_user", $datos["email_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":password_user", $datos["password_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":token_user", $datos["token_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":token_exp_user", $datos["token_exp_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":date_created_user", $datos["date_created_user"], PDO::PARAM_STR);
		$stmt -> bindParam(":date_updated_user", $datos["date_updated_user"], PDO::PARAM_STR);

		if($stmt -> execute()){
			return "ok";
		}else{
			print_r(Conexion::conectar()->errorInfo());
		}

		$stmt-> close();
		$stmt = null;
	}

	static public function login($email,$password){

		$stmt = Conexion::conectar()->prepare("SELECT name_user, email_user, token_user, FROM_UNIXTIME(token_exp_user) token_exp FROM users WHERE email_user= '". $email ."' and password_user = '". $password ."'");

		$stmt -> execute();
		return $stmt -> fetchAll(PDO::FETCH_CLASS);
	    $stmt -> close();
	    $stmt = null;
	}


	static public function token($token){
		$stmt = Conexion::conectar()->prepare("SELECT IF(COUNT('x')>0, id_user, 0) as 'existe' FROM users WHERE token_exp_user > UNIX_TIMESTAMP() AND token_user ='".$token."'");

		$stmt -> execute();
		return $stmt -> fetchAll(PDO::FETCH_CLASS);
	    $stmt -> close();
	    $stmt = null;
	}

	static public function renew($datos){

		$stmt = Conexion::conectar()->prepare("UPDATE users SET token_user = :new_token_user, token_exp_user = :token_exp_user WHERE email_user= :email_user and password_user = :password_user AND token_user = :token_user");

		if($stmt -> execute($datos)){
			return $stmt->rowCount();
		}else{
			print_r(Conexion::conectar()->errorInfo());
		}
	}


}