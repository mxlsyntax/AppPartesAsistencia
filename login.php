<?php
	include("config.php");
	// Evitamos que tenga caché la página
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");


    //echo $id_app_android;

	//NG20240608 HAY QUE HACER UN SESSION_START CADA VEZ QUE QUERAMOS OBTENER UNA VARIABLE DE SESION.
	$previous_name = session_id("AppWeb_".$id_app_android);
	@session_start();
	//$user_cookie_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
	//echo "<script>";
	//echo "alert('Entra en salir');";
	//echo "alert('El usuario es: " . $user_cookie_id . "');";
	//echo "</script>";

	if (!isset($_COOKIE['user_id'.'_'.$id_app_android])) {
		if ((isset($_POST['trabajador_login'])) && (isset($_POST['password_login']))) {

			//@session_start(); //PELIGRO AL QUITAR, NO SE COMO FUNCINARA PUESTO DENTRO DE LOGIN CORRECTO
			$user = $_POST['trabajador_login'];
			$user = str_replace(" ","%20",$user);
			$password = $_POST['password_login'];
			$password = str_replace(" ","%20",$password);
			$id = 0;
			unset($_POST['trabajador_login']);
			unset($_POST['password_login']);
			//unset($_POST['nombre_trab_login']);

			//Nombre de trabajador y tipo de trabajador
			$nombre_trab_login = $_POST['nombre_trab_login'];
			$tipo_trab_login = $_POST['tipo_trab_login'];



			/*echo "<script>";
			echo "alert('Entra en salir');";
			echo "alert('El usuario es: " . $nombre_trab_login . "');";
			echo "</script>";*/
			
			session_id("AppWeb_".$id_app_android);
			@session_start();

			$_SESSION['user_id'.'_'.$id_app_android] = $user; //usuario que introducimos en el inico de sesión.	
			$_SESSION['nombre_trab_login'.'_'.$id_app_android] = $nombre_trab_login;
			$_SESSION['tipo_trab_login'.'_'.$id_app_android] = $tipo_trab_login;			
			$_SESSION['last_activity'.'_'.$id_app_android] = time(); //Para el control de tiempo de inactividad
			$_SESSION['ok_cookies'.'_'.$id_app_android] = true; //Control de rechazo de cookies
			setcookie("user_id".'_'.$id_app_android, $user); //usuario que introducimos en el inico de sesión.	Para la gestion en javascript
			setcookie("nombre_trab_login".'_'.$id_app_android, $nombre_trab_login);
			setcookie("tipo_trab_login".'_'.$id_app_android, $tipo_trab_login);
			$_COOKIE['user_id'.'_'.$id_app_android] = $user;
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = $nombre_trab_login;
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = $tipo_trab_login;
			setcookie("last_activity".'_'.$id_app_android, time());
			$_COOKIE['last_activity'.'_'.$id_app_android] = time();
			setcookie("ok_cookies".'_'.$id_app_android, true);
			$_COOKIE['ok_cookies'.'_'.$id_app_android] = true;				
			//setcookie("sestab", $uniqid);
			setcookie("sesion_ini".'_'.$id_app_android, true);
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = true;
			setcookie("cdapp".'_'.$id_app_android, $id_app_android);
			$_COOKIE['cdapp'.'_'.$id_app_android] = $id_app_android;

		} else {
			header('location:exit.php?id_'.$id_app_android . '=1'); //
			exit();	//
		}
	}
?>