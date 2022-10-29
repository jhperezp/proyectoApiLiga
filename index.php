<?php 
require_once "controladores/login.controlador.php";


$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case 'GET':
    	require_once "rutas/rutas.get.php";
    	$method = new GetMethod();
    	$method -> request();
        break;
    case 'POST':
        require_once "rutas/rutas.post.php";
    	$method = new PostMethod();
    	$method -> request();
        break;
    case 'PUT':
        require_once "rutas/rutas.put.php";
    	$method = new PutMethod();
    	$method -> request();
        break;
    case 'DELETE':
        require_once "rutas/rutas.delete.php";
    	$method = new DeleteMethod();
    	$method -> request();
        break;
    default;
    	$json = array("response"=>"Request method not supported");
		echo json_encode($json,true);
		break;
}