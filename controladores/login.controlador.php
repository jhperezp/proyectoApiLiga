<?php 

require_once "modelos/usuarios.modelo.php";

class ControladorLogin{

	/*=============================================
			   Login para obtener token
	=============================================*/
	public function index(){

		//Validamos que vengan los campos necesarios
		if(!isset($_GET['email']) || !isset($_GET['password'])){
			$json = array(
				"status"=>404,
				"detalle"=>"Error, campo email y password requerido."
			);
			echo json_encode($json, true);
			return;
		}

		//Validamos el email sea valido
		if(isset($_GET['email'])&& !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$_GET['email'])){
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo email, email no valido."
			);
			echo json_encode($json, true);
			return;
		}
		//Validamos que la contraseña cumpla con los requisitos minimos
		if(isset($_GET['password']) && !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,40}$/', $_GET['password'])) {
			$json = array(
				"status"=>404,
				"detalle"=>"Password, debe contener una letra, un numero y un caracter especial[!@#$%] entre 8 y 40 caracteres."
			);
			echo json_encode($json, true);
			return;
		}

		$password = str_replace("$", "o", crypt($_GET['email'].$_GET['password'], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));

		$login = ModeloUsuarios::login($_GET['email'],$password);

		$json = array(

		"status"=>200,
		"total_registros"=>count($login),
		"detalle"=>$login
		);
		echo json_encode($json,true);
	}


	/*=============================================
			Validar el token de seguridad
	=============================================*/
	static public function tokenValidate(){

		$HEADERS = apache_request_headers();
		if(!isset($HEADERS['Authorization']) || $HEADERS['Authorization']=='' || is_null($HEADERS['Authorization'])){
			http_response_code(401);
			$json = array(
					"status"=>401,
					"detalle"=>"No se encontro el Header Authorization."
					);
			echo json_encode($json, true);
		}else{
			$token = $HEADERS['Authorization'];
			$user = ModeloUsuarios::token($token);
			$istoken = $user[0]->existe;
			if($istoken==0){
				http_response_code(403);
				$json = array(
					"status"=>403,
					"detalle"=>"Usuario sin autorizacion para este recurso."
					);
				echo json_encode($json, true);
			}else{
				return $istoken;
			}
		}
		return;
	}
}