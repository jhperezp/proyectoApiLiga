<?php 

class DeleteMethod{

	public function request(){
		$arrayRutas = explode ("/", $_SERVER['REQUEST_URI']);
		$table = explode("?", $arrayRutas[1])[0];

		if(count(array_filter($arrayRutas)) == 0){
			$json = array(

			"message"=>"request not found"

			);
			echo json_encode($json,true);
		}else if(array_key_exists(2,$arrayRutas) && isset(array_filter($arrayRutas)[2])){
			if(is_numeric(array_filter($arrayRutas)[2])){
				switch ($table) {
			    case 'usuarios':
			    	require_once "controladores/usuarios.controlador.php";
			        $usuario = new ControladorUsuarios();
					$usuario -> delete($arrayRutas[2]);
			        break;
			    case 'ligas':
			    	require_once "controladores/ligas.controlador.php";
			        $liga = new ControladorLigas();
					$liga -> delete($arrayRutas[2]);
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
			$json = array("message"=>"ID not found");
			echo json_encode($json,true);
		}
	}

}