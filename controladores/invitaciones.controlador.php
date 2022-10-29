<?php 

require_once "modelos/invitaciones.modelo.php";

class ControladorInvitaciones{

	/*=============================================
				   Crear una invitacion
	=============================================*/
	public function create(){

		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$id_user = $usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		//Validamos que vengan los campos necesarios
		if(!isset($_POST['id_type_league']) || !isset($_POST['name_league']) || !isset($_POST['amount_league'])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error, campo tipo de invitacion, nombre de la invitacion y monto son campos requeridos."
			);
			echo json_encode($json, true);
			return;
		}

		$datos = array(
			"id_type_league"=>$_POST['id_type_league'],
			"name_league"=>$_POST['name_league'],
			"amount_league"=>$_POST['amount_league'],
		);

		//Validamos el nombre sea solo texto y numeros
		if(isset($datos["name_league"]) && !preg_match('/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]+$/', $datos["name_league"])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo nombre, unicamente letras y numeros."
			);
			echo json_encode($json, true);
			return;
		}
		//Validamos el monto sea valido
		if(isset($datos["amount_league"]) && !preg_match('/^[0-9]+([.][0-9][0-9])?$/', $datos["amount_league"])){
			http_response_code(404);
			$json = array(
				"status"=>404,
				"detalle"=>"Error en el campo monto, monto no valido."
			);
			echo json_encode($json, true);
			return;
		}

		//Validamos que no se repita el nombre
		$invitaciones = ModeloInvitaciones::index();
		foreach ($invitaciones as $key) {
			if($key["name_league"] == $datos["name_league"]){
				http_response_code(404);
				$json = array(
					"status"=>404,
					"detalle"=>"El nombre ya existe en la base de datos"
				);
				echo json_encode($json, true);
				return;
			}
		}

		
		//Guardamos el registro
		$datos = array("id_type_league"=>$datos["id_type_league"],
						"name_league"=>$datos["name_league"],
						"owner_league"=>$id_user,
						"amount_league"=>$datos["amount_league"],
						"date_created_league"=>date('Y-m-d h:i:s'),
						"date_updated_league"=>date('Y-m-d h:i:s')
						);

		$create = ModeloInvitaciones::create($datos);

		if($create == "ok"){
			$json = array(
					"status"=>200,
					"detalle"=>"Registro exitoso",
					"Invitacion"=>$datos["name_league"]
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
					Mostrar Invitaciones
	=============================================*/
	public function index($fetch){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$invitaciones = ModeloInvitaciones::index($fetch);

		$json = array(

		"status"=>200,
		"total_registros"=>count($invitaciones),
		"detalle"=>$invitaciones

		);
		echo json_encode($json,true);
	}


	/*=============================================
			    	Mostrar una Invitacion
	=============================================*/
	public function show($id,$fetch){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$invitaciones = ModeloInvitaciones::show($id,$fetch);

		$json = array(

		"status"=>200,
		"total_registros"=>count($invitaciones),
		"detalle"=>$invitaciones

		);
		echo json_encode($json,true);
	}

	/*=============================================
			    	Actualizar una Invitacion
	=============================================*/
	public function update($id){
		$json = array(

		"detalle" => "Invitacion editada con id ". $id

		);
		echo json_encode($json,true);
	}

	/*=============================================
			    	Eliminar una Invitacion
	=============================================*/
	public function delete($id){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$user_id = $usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$result = ModeloInvitaciones::delete($id,$user_id);

		if($result){
			$json = array(
					"status"=>200,
					"detalle"=>"Eliminacion exitosa",
					"Resultado"=>"Invitacion eliminada con id: $id"
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
}