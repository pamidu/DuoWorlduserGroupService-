<?php

	if ($_SERVER['REQUEST_METHOD'] != "OPTIONS"){
		require_once ("config.php");
		require_once(ROOT_PATH . "/dwcommon.php");
		require_once ("common.php");

		require_once BASE_PATH . '/flight/Flight.php';
		require_once ("./services/usergroup.php");

		new usergroup();
		

		Flight::start();
	}

	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');  

?>
