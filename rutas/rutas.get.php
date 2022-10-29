<?php 

class GetMethod{

	/*=============================================
		Metodo para mostrar registros
	=============================================*/

	public function request(){
		$arrayRutas = explode ("/", $_SERVER['REQUEST_URI']);
		$table = explode("?", $arrayRutas[1])[0];

		if(count(array_filter($arrayRutas)) == 0){
			$json = array(

			"detalle"=>"no encontrado"

			);

			echo json_encode($json,true);
		}else if(array_key_exists(2,$arrayRutas) && isset(array_filter($arrayRutas)[2])){
			if(is_numeric(array_filter($arrayRutas)[2])){
				switch ($table) {
			    case 'usuarios':
			    	require_once "controladores/usuarios.controlador.php";
			        $usuario = new ControladorUsuarios();
					$usuario -> show($arrayRutas[2]);
			        break;
			    case 'ligas':
			    	require_once "controladores/ligas.controlador.php";
			        $liga = new ControladorLigas();
					$liga -> show($arrayRutas[2],true);
			        break;
			    case 'equipos':
			    	require_once "controladores/teams.controlador.php";
			        $team = new ControladorTeams();
					$team -> show($arrayRutas[2],true);
			        break;
			    default;
			    	$json = array("message"=>"Controller not found");
					echo json_encode($json,true);
					break;
				}
			}else{
				$json = array("message"=>"ID is a numeric value");
				echo json_encode($json,true);
			}
		}else{
			switch ($table) {
			    case 'login':
			        $usuario = new ControladorLogin();
					$usuario -> index();
			        break;
			    case 'usuarios':
			    	require_once "controladores/usuarios.controlador.php";
			        $usuario = new ControladorUsuarios();
					$usuario -> index(true);
			        break;
			    case 'token':
			    	require_once "controladores/usuarios.controlador.php";
			        $usuario = new ControladorUsuarios();
					$usuario -> renew();
			        break;
			    case 'ligas':
			    	require_once "controladores/ligas.controlador.php";
			        $usuario = new ControladorLigas();
					$usuario -> index(true);
			        break;
			    case 'equipos':
			    	require_once "controladores/teams.controlador.php";
			        $team = new ControladorTeams();
					$team -> index(true);
			        break;
			    default;
			    	$json = array("detalle"=>"controlador no encontrado");
					echo json_encode($table,true);
					break;
			}
		}
	}

}