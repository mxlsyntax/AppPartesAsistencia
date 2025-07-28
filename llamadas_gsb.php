<?php
require_once('config.php');
require('gsbSocket.php');

//print("llega");


// GTF20160825-002: Ejecutar acción servidor gsbase
function EjecutarAccionVelneo($accion, $ventana, $server, $port, $empges, $apl, $ejer, $arg) {
	global $gsbase_user, $gsbase_pass, $id_app_android;
	
	$resultado = "";
	
	// Creamos el objeto conexión
	$miconexion = new gsbSocket;
	//die ("<br>Servidor:" . $server . "<br>Puerto:" . $port);
	
	// Abrimos la conexión al servidor gsBase
	$respu = $miconexion->Connect($server, $port);
	if ($respu != 0){
		die ("Error al conectar:" . $miconexion->GetLastErrorStr());
	}
	
	// Hacemos la identificación en el servidor gsBase
	$respu = $miconexion->Logon($empges, $gsbase_user, $gsbase_pass, $apl, $ejer, "", "");
	if ($respu != 0){
		$miconexion->Disconnect();
		echo("Error al iniciar sesion: " . $miconexion->GetLastErrorStr());
	}
	
	// Ejecutamos la consulta
	$respu = $miconexion->rgsb($accion, $ventana, $arg);
	if ($respu==""){ //Si devuelve cadena vacia "" es que ha habido error en la consulta
		if ($miconexion->GetLastErrorStr() == ""){
			//echo "<br><b>No se encontraron datos con los parametros indicados</b><br>";
		} else{
			echo("Error al realizar la consulta: " . $miconexion->GetLastErrorStr());
		}
	} else {
		$resultado = $respu;
	}
	
	// Cerramos la conexión con el servidor gsBase
	$respu = $miconexion->Disconnect();
	if ($respu != 0){
		die ("Error al cerrar la conexión con el servidor gsBase:" . $miconexion->GetLastErrorStr());
	}

	return $resultado;
}



?>
