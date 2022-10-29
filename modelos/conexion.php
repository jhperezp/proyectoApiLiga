<?php 

class Conexion{

	static public function conectar(){

		$link = new PDO("mysql:host=sql473.main-hosting.eu;dbname=u541845672_api",
						"u541845672_api",
						"O+jn#mzgl1");

		$link->exec("set names utf8");

		return $link;

	}

}