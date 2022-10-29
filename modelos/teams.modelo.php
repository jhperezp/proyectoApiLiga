<?php 

require_once "conexion.php";

class ModeloTeams{


	/*=============================================
			Mostrar todos los registros
	=============================================*/

	static public function index($FETCH_CLASS = false){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM teams");

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
			     Mostrar un registro
	=============================================*/

	static public function show($id,$FETCH_CLASS = false){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM teams WHERE id_team=?");
		$stmt->bindParam(1, $id, PDO::PARAM_INT);

		$stmt -> execute();

		if($FETCH_CLASS == true){
			return $stmt -> fetchAll(PDO::FETCH_CLASS);
		}else{
			return $stmt -> fetchAll();
		}

	    $stmt -> close();

	    $stmt = null;
	}

}