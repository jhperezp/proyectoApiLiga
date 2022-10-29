<?php 

require_once "modelos/usuarios.modelo.php";
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class ControladorUsuarios{

	/*=============================================
				   Crear un usuario
	=============================================*/
	public function create(){
		

		//Validamos que vengan los campos necesarios
		if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error, campo name, email y password requerido."
			);
			echo json_encode($json, true);
			return;
		}

		$datos = array(
			"name"=>$_POST['name'],
			"email"=>$_POST['email'],
			"password"=>$_POST['password'],
		);

		//Validamos el nombre sea solo texto
		if(isset($datos["name"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["name"])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo nombre, unicamente letras."
			);
			echo json_encode($json, true);
			return;
		}
		//Validamos el email sea valido
		if(isset($datos["email"]) && !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $datos["email"])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo email, email no valido."
			);
			echo json_encode($json, true);
			return;
		}
		//Validamos que la contraseña cumpla con los requisitos minimos
		if(isset($datos["password"]) && !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,40}$/', $datos["password"])) {
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Password, debe contener una letra, un numero y un caracter especial[!@#$%] entre 8 y 40 caracteres."
			);
			echo json_encode($json, true);
			return;
		}

		//Validamos que no se repita el email
		$usuarios = ModeloUsuarios::index();
		foreach ($usuarios as $key) {
			if($key["email_user"] == $datos["email"]){
				http_response_code(404);
				$json = array(
					"status"=>404,
					"detalle"=>"El email ya existe en la base de datos"
				);
				echo json_encode($json, true);
				return;
			}
		}

		//Generamos las credenciales del usuario
		$password = str_replace("$", "o", crypt($datos["email"].$datos["password"], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));


		//Generamos un nuevo token para el usuario
		$time = time();

		$jwt = array(

			"iat" =>  $time,//Tiempo en que inicia el token
			"exp" => $time + (60*60*24), // Tiempo en que expirará el token (1 día)
			"data" => [
				"email" => $datos["email"],
				"password"=>$password
			]

		);

		$token = JWT::encode($jwt, "dfhsdfg34dfchs4xgsrsdry46", 'HS256');

		//Guardamos el registro
		$datos = array("name_user"=>$datos["name"],
						"email_user"=>$datos["email"],
						"password_user"=>$password,
						"token_user"=>$token,
						"token_exp_user"=>$jwt["exp"],
						"date_created_user"=>date('Y-m-d h:i:s'),
						"date_updated_user"=>date('Y-m-d h:i:s')
						);

		$create = ModeloUsuarios::create($datos);

		if($create == "ok"){
			$json = array(
					"status"=>200,
					"detalle"=>"Registro exitoso",
					"token"=>$token
					);
			echo json_encode($json, true);
			return;
		}else{
			$json = array(
				"detalle" => "Error al guardar el registro"
			);
			echo json_encode($json,true);
		}
	}


	/*=============================================
				   Mostrar un usuario
	=============================================*/
	public function show($id){
		$json = array(

		"detalle" => "Mostrando usuario con id ". $id

		);
		echo json_encode($json,true);
	}


	/*=============================================
					Mostrar usuarios
	=============================================*/
	public function index($fetch){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$usuarios = ModeloUsuarios::index($fetch);

		$json = array(

		"status"=>200,
		"total_registros"=>count($usuarios),
		"detalle"=>$usuarios

		);
		echo json_encode($json,true);
	}


	/*=============================================
				 Actualizar un usuario
	=============================================*/
	public function update($id){
		$json = array(

		"detalle" => "Usuario editado con id ". $id

		);
		echo json_encode($json,true);
	}


	/*=============================================
				 Validar un token
	=============================================*/
	public function token($id){
		$time = time();

		$token = array(
			"iat" =>  $time,//Tiempo en que inicia el token
			"exp" => $time + (60*60*24), // Tiempo en que expirará el token (1 día)
			"data" => [
				"id" => $id,
				"email" => $email
			]
		);

		$jwt = JWT::encode($token, "dfhsdfg34dfchs4xgsrsdry46");

		$data = array(
			"token_user" => $jwt,
			"token_exp_user" => $token["exp"]
		);

		echo json_encode($data,true);
	}


	/*=============================================
			 Renovar el token de seguridad
	=============================================*/
	public function renew(){

		//Validamos que vengan el token anterior
		$HEADERS = apache_request_headers();
		if(!isset($HEADERS['Authorization']) || $HEADERS['Authorization']=='' || is_null($HEADERS['Authorization'])){
			http_response_code(401);
			$json = array(
					"status"=>401,
					"detalle"=>"No se encontro el Header Authorization."
					);
			echo json_encode($json, true);
			return;
		}

		//Validamos que vengan los campos necesarios
		if(!isset($_GET['email']) || !isset($_GET['password'])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error, campo email y password requerido."
			);
			echo json_encode($json, true);
			return;
		}

		//Validamos el email sea valido
		if(isset($_GET['email'])&& !preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$_GET['email'])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo email, email no valido."
			);
			echo json_encode($json, true);
			return;
		}
		//Validamos que la contraseña cumpla con los requisitos minimos
		if(isset($_GET['password']) && !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,40}$/', $_GET['password'])) {
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Password, debe contener una letra, un numero y un caracter especial[!@#$%] entre 8 y 40 caracteres."
			);
			echo json_encode($json, true);
			return;
		}

		//Generamos un nuevo token para el usuario
		$email = $_GET['email'];
		$password = str_replace("$", "o", crypt($email.$_GET['password'], '$2a$07$afartwetsdAD52356FEDGsfhsd$'));
		$time = time();
		$jwt = array(

			"iat" =>  $time,//Tiempo en que inicia el token
			"exp" => $time + (60*60*24), // Tiempo en que expirará el token (1 día)
			"data" => [
				"email" => $email,
				"password"=>$password
			]

		);

		$token = $HEADERS['Authorization'];

		$renewed = JWT::encode($jwt, "dfhsdfg34dfchs4xgsrsdry46", 'HS256');

		//Guardamos el nuevo token
		$datos = array("email_user"=>$email,
						"password_user"=>$password,
						"token_user"=>$token,
						"new_token_user"=>$renewed,
						"token_exp_user"=>$jwt["exp"]
						);

		$login = ModeloUsuarios::renew($datos);

		if($login > 0){
			$datos['token_exp_user']= gmdate("Y-m-d\TH:i:s\Z", $datos['token_exp_user']);
			unset($datos['password_user']);
			unset($datos['token_user']);
			$json = array(
					"status"=>200,
					"detalle"=>"Registro exitoso",
					"token"=>$datos
					);
		}else{
			$json = array(
				"detalle" => "Error al guardar el registro"
			);
		}
		echo json_encode($json, true);
		return;
	}


	/*=============================================
				 Eliminar un usuario
	=============================================*/
	public function delete($id){
		$json = array(

		"detalle" => "Usuario eliminado con id ". $id

		);
		echo json_encode($json,true);
	}

}