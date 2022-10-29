<?php 

require_once "modelos/teams.modelo.php";

class ControladorTeams{

	/*=============================================
					Mostrar Ligas
	=============================================*/
	public function index($fetch){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$equipos = ModeloTeams::index($fetch);

		$json = array(

		"status"=>200,
		"total_registros"=>count($equipos),
		"detalle"=>$equipos

		);
		echo json_encode($json,true);
	}


	/*=============================================
			    	Mostrar una Liga
	=============================================*/
	public function show($id,$fetch){
		//Validamos que el usuario tengo token valido
		$usuario = new ControladorLogin();
		$usuario -> tokenValidate();
		$response = http_response_code();

		//Si no se tiene autorizacion se retorna con error 401
		if($response > 399) return;

		$equipos = ModeloTeams::show($id,$fetch);

		$json = array(

		"status"=>200,
		"total_registros"=>count($equipos),
		"detalle"=>$equipos

		);
		echo json_encode($json,true);
	}
}