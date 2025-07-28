<?php
	require_once ('llamadas_gsb.php');	
	$orderby = "";
	$order = "";
	$accion = "a_comprobar_conexion";
	$ventana = "_app_fichajes";
	$server = "192.168.0.105";
	$puerto = "8122";
	$empges = "gss";
	$apl = "gsnila";
	$ejer = "eja";

	


	print($accion);
	//$respu = EjecutarAccion("a_leer_tareas", "_android_tareas", "|||1|1|1||||".$orderby."|".$order."|||");
	$respu = "";

	if ($accion == "a_comprobar_conexion"){
		$respu = EjecutarAccionVelneo($accion, $ventana, $server, $puerto, $empges, $apl, $ejer, "");		
	}	 
	print($respu);
?>
