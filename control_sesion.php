<?php
	include("config.php");
	//PHP para contral el tiempo de sesión con cookies

	//echo "<script>";
	//echo "alert('Entra en control_sesion.php');";
	//echo "alert('El usuario es: " . $previous_name . "');";
	//echo "</script>";

	//funcion para verificar que dentro del arreglo global $_SESSION existe el nombre del usuario
	$des_user = false;
	$des_last_act = false;
	$des_cookie_rec = false;

	$mensaje = "TEST";

	$session_id_to_destroy = session_id('id'.'_'.$id_app_android);


	$previous_name = session_id("AppWeb_".$id_app_android);
	@session_start();

	//echo "<script>";
	//echo "alert('Entra en control_sesion.php');";
	//echo "alert('El usuario es: " . $_COOKIE['user_id'.'_'.$id_app_android] . "');";
	//echo "</script>";
	

	$user_cookie_id = isset($_COOKIE['user_id'.'_'.$id_app_android]) ? $_COOKIE['user_id'.'_'.$id_app_android] : '';
	if ($user_cookie_id == null) {
		//parent.desconectar('3');
		$des_user = true;
	} else if ($user_cookie_id == ""){
		//parent.desconectar('3');
		$des_user = true;
	} else if ($user_cookie_id == ''){
		//parent.desconectar('3');
		$des_user = true;
	}


	// Comprobar si la sesión ha expirado por falta de actividad	
	$now = time();
	$limit = 60 * 90; // Límite de sesión, el multiplicador son los minutos
	//$limit = 10; // Límite de sesión, el multiplicador son los minutos
	$diferencia = $now - $_COOKIE['last_activity'.'_'.$id_app_android];


	/*echo "<script>";
	//echo "alert('El usuario es: " . $_COOKIE['user_id'] . "');";
	//echo "alert('last_activity PHP es: " . $_COOKIE['last_activity'.'_'.$id_app_android] . " now es: " . $now . " y limit: " . $limit . "');";
	echo "alert('Han pasado : " . $diferencia . " segundos y el limite es: " . $limit . "');";
	if(($now - $_COOKIE['last_activity'.'_'.$id_app_android]) > $limit){
		echo "alert('Por lo tanto debería salir');";
		//echo "alert('Demasiado tiempo de inactividad. Desconectando usuario');";
	} else {
		echo "alert('Por lo tanto NO debería salir');";
	}
	echo "</script>";*/

	//ULTIMA ACTIVIDAD POR COOKIE Y POR SESSION
	$last_activity = isset($_COOKIE['last_activity'.'_'.$id_app_android]) ? $_COOKIE['last_activity'.'_'.$id_app_android] : '';
	if ($last_activity == null) {
		//alert("La cookie user_id no sexiste");
		//parent.desconectar('11');
		$des_last_act = true;
	} else if ($last_activity == ""){
		//parent.desconectar('11');
		$des_last_act = true;
	} else if ($last_activity == ''){
		//parent.desconectar('11');
		$des_last_act = true;
	} else {

		if (isset($_COOKIE['last_activity'.'_'.$id_app_android]) && ($now - $_COOKIE['last_activity'.'_'.$id_app_android]) > $limit) {
			//echo "<script>";
			//echo "alert('Entra en salir');";
			//echo "alert('Superado el tiempo de inactividad (php) de:' + (30 * 1) + ' segundos.');";
			//echo "parent.desconectar('11');";
			//echo "</script>";


			$des_last_act = true;

			//$_SESSION = array();
			//Mandamos al exit por demasiado tiempo de inactividad
			header('location:exit.php?id=11');
			
		} else {
			// Como no ha expirado, renovamos la variable de tiempo de última actividad para la siguiente comprobación
			setcookie("last_activity".'_'.$id_app_android, $now);
			
		}

	}

	$sesion_ini = isset($_COOKIE['sesion_ini'.'_'.$id_app_android]) ? $_COOKIE['sesion_ini'.'_'.$id_app_android] : '';


	if ($des_user){
		$_COOKIE['user_id'.'_'.$id_app_android] = "";
		$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
		$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

		unset($_COOKIE['user_id'.'_'.$id_app_android]);
		unset($_COOKIE['last_activity'.'_'.$id_app_android]);		
		unset($_COOKIE['sesion_ini'.'_'.$id_app_android]);

		//unset($_SESSION['user_id']);
		//unset($_SESSION['last_activity']);

		
		header('location:exit.php?id_'.$id_app_android.'=3');
	} else if ($des_last_act){
		$_COOKIE['user_id'.'_'.$id_app_android] = "";
		$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
		$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

		
		header('location:exit.php?id_'.$id_app_android.'=11');
	} else if ($des_cookie_rec){
		$_COOKIE['user_id'.'_'.$id_app_android] = "";
		$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
		$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

		
		header('location:exit.php?id_'.$id_app_android.'=11');
	} else if (!$sesion_ini){
		$_COOKIE['user_id'.'_'.$id_app_android] = "";
		$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
		$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

		unset($_COOKIE['user_id'.'_'.$id_app_android]);
		unset($_COOKIE['last_activity'.'_'.$id_app_android]);		
		unset($_COOKIE['sesion_ini'.'_'.$id_app_android]);

		
		header('location:exit.php?id_'.$id_app_android.'=1');
	}

	/*echo "<script>";
	echo "alert('Entra en salir');";
	echo "alert('El usuario es: " . $_COOKIE['user_id'] . "');";
	echo "</script>";*/


?>
