<?php 

class PostMethod{

	/*=============================================
		Metodo para crear registros
	=============================================*/

	public function request(){
		$arrayRutas = explode ("/", $_SERVER['REQUEST_URI']);
		$table = explode("?", $arrayRutas[1])[0];

		if(count(array_filter($arrayRutas)) == 0){
			$json = array(

			"detalle"=>"no encontrado"

			);

			echo json_encode($json,true);
		}else{
			switch ($table) {
				case 'registro':
			    	require_once "controladores/usuarios.controlador.php";
			        $usuario = new ControladorUsuarios();
					$usuario -> create();
			        break;
			    case 'ligas':
			    	require_once "controladores/ligas.controlador.php";
			        $usuario = new ControladorLigas();
					$usuario -> create();
			        break;
			    default;
			    	$json = array("detalle"=>"controlador no encontrado");
					echo json_encode($json,true);
					break;
			}
			//echo '<pre>'; print_r($arrayRutas); echo '</pre>';
		}
	}

}