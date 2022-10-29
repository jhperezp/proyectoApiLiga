<?php 

require_once "conexion.php";

class ModeloInvitaciones{


	/*=============================================
			Mostrar todos los registros
	=============================================*/

	static public function index($FETCH_CLASS = false){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM leagues");

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

		$stmt = Conexion::conectar()->prepare("SELECT * FROM leagues WHERE id_league=?");
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

	/*=============================================
				Crear un registro
	=============================================*/

	static public function create($datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO leagues(id_type_league, name_league, owner_league, amount_league, date_created_league, date_updated_league) VALUES (:id_type_league, :name_league, :owner_league, :amount_league, :date_created_league, :date_updated_league)");

		$stmt -> bindParam(":id_type_league", $datos["id_type_league"], PDO::PARAM_STR);
		$stmt -> bindParam(":name_league", $datos["name_league"], PDO::PARAM_STR);
		$stmt -> bindParam(":owner_league", $datos["owner_league"], PDO::PARAM_STR);
		$stmt -> bindParam(":amount_league", $datos["amount_league"], PDO::PARAM_STR);
		$stmt -> bindParam(":date_created_league", $datos["date_created_league"], PDO::PARAM_STR);
		$stmt -> bindParam(":date_updated_league", $datos["date_updated_league"], PDO::PARAM_STR);

		if($stmt -> execute()){
			return "ok";
		}else{
			print_r(Conexion::conectar()->errorInfo());
		}
		
		$stmt = null;
	}


	/*=============================================
			     Eliminar un registro
	=============================================*/

	static public function delete($id,$user_id){

		$stmt = Conexion::conectar()->prepare("DELETE FROM leagues WHERE id_league=? and owner_league=?");
		$stmt->bindParam(1, $id, PDO::PARAM_INT);
		$stmt->bindParam(2, $user_id, PDO::PARAM_INT);

		$stmt -> execute();

		if($stmt -> execute()){
			return true;
		}else{
			print_r(Conexion::conectar()->errorInfo());
		}
		
		$stmt = null;
	}


}