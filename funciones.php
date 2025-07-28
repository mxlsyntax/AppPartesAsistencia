<?php
	require_once("llamadas_gsb.php");
	require_once("config.php");
	//NG20240617 ESTE ARCHIVO HAY QUE ACTUALIZARLO EN LA WEB, PORQUE PARA CONECTAR CON LA BD,
	//TIENE QUE ESTAR EN EL MISMO SITIO QUE LA BD, POR LO QUE A CADA CAMBIO, AUNQUE ESTEMOS PROBANDO
	//EN LOCAL, HAY QUE ACTUALIZARLO EN LA WEB.
	// Evitamos que tenga caché la página
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header('Access-Control-Allow-Origin: *');


	$post = file_get_contents('php://input');
	$accion = isset($_POST['accion']) ? $_POST['accion'] : "";
	$accion_gsb = isset($_POST['accion_gsb']) ? $_POST['accion_gsb'] : "";
	$uuid = isset($_POST['uuid']) ? $_POST['uuid'] : "";  
	$cd_pref_autogen = isset($_POST['cd_pref_autogen']) ? $_POST['cd_pref_autogen'] : "";
	$nav_y_disp = isset($_POST['nav_y_disp']) ? $_POST['nav_y_disp'] : "";


	$server = isset($_POST['servidor_ip_publica']) ? $_POST['servidor_ip_publica'] : "";
	$puerto = isset($_POST['puerto']) ? $_POST['puerto'] : "";
	$empges = isset($_POST['empresa_gestora']) ? $_POST['empresa_gestora'] : "";
	$apl = isset($_POST['aplicacion']) ? $_POST['aplicacion'] : "";
	$ejer = isset($_POST['ejercicio']) ? $_POST['ejercicio'] : "";
	$empresa_id = isset($_POST['empresa_id']) ? $_POST['empresa_id'] : "";
	$historico_activo = isset($_POST['historico_activo']) ? $_POST['historico_activo'] : "";
	
	$ventana = isset($_POST['ventana_pref']) ? $_POST['ventana_pref'] : "";
	$arg = isset($_POST['arg']) ? $_POST['arg'] : "";
	$respuesta = isset($_POST['respuesta']) ? $_POST['respuesta'] : "";

	//ng20240312 obtenemos la ip que se conecta con nosotros.
	function getRealIP(){
        if (isset($_SERVER["HTTP_CLIENT_IP"])){

            return $_SERVER["HTTP_CLIENT_IP"];

        }else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

            return $_SERVER["HTTP_X_FORWARDED_FOR"];

        }else if(isset($_SERVER["HTTP_X_FORWARDED"])){

            return $_SERVER["HTTP_X_FORWARDED"];

        }else if(isset($_SERVER["HTTP_FORWARDED_FOR"])){

            return $_SERVER["HTTP_FORWARDED_FOR"];

        }else if(isset($_SERVER["HTTP_FORWARDED"])){

            return $_SERVER["HTTP_FORWARDED"];

        }else if(isset($_SERVER["REMOTE_ADDR"])){

            return $_SERVER["REMOTE_ADDR"];

        } else {    	

            return 'UNKNOWN';
        }
    }  

	function StringValueSQL($valor) {
		//$valor = "'".mysql_real_escape_string($valor)."'"; #GTF20140205-002
		if (is_null($valor)) {
			$valor = 'NULL'; #NG20200104 para modificar registros como empresa_id con el que establecemos visibiliad, al quitarle la empresa, lo ven todas (para quitarselo hay que poner null)
		} else {
			$valor = "'".$valor."'"; #GTF20140205-002
		}
		
		return $valor;
	}

	//NG20240110 NO FUNCIONA EL HISTORICO EN LOCAL, DA UN ERROR.
	//AL LLAMARSE EL HISTORICO EN LA MISMA LLAMADA QUE LAS ACCIONES CON GSBASE, TIRA DEL funciones.php local
	//PERO PARA CONECTAR CON EL PHPMYADMIN DONDE TENEMOS EL HISTORICO TENDRIA QUE TIRAR DEL FUNCIONES.PHP QUE TENEMOS 
	//EN LA WEB. DE LO CONTRARIO NO CONECTARA.
	function EscribirHistorico($accion_gsb, $ventana, $server, $puerto, $empges, $apl, $ejer, $arg, $respuesta, $cd_pref_autogen){

		global $server_name, $dbhostname, $dbusername, $dbpassword, $dbname, $id_app_android;
		//NG20230909 AÑADIMOS NUEVA ACCION PARA ESCRIBIR EL HISTORICO SI LOS VALORES DE LAS PREF SON LOS ADECUADOS, ATACANDO AL PHP
		//print("<script>alert($dbhostname + $dbusername + $dbpassword +  $dbname)script>");			

		if($cd_pref_autogen != ''){
			$conexion = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);

			if (mysqli_connect_errno()) {
			  echo "Error: Failed to connect to MySQL: " . mysqli_connect_error();
			  exit();
			}

			if ($conexion) {
				$sql = "SELECT * FROM `preferencias_aplicacion` WHERE preferencias_aplicacion.codigo = ";
				$sql = $sql."( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = ".StringValueSQL($cd_pref_autogen)." LIMIT 1)";

				$sql = $sql. ";";

				//$resultado = $sql;

				//$query = mysqli_query($conexion, $sql);
				$query = mysqli_query($conexion, $sql);

				//$resultado = $resultado . "    " . $query . mysqli_errno($conexion);

				if ($query) {
					while ($row = mysqli_fetch_array($query)) {
						$row = array_map("utf8_decode", $row);
						$codigo = $row['codigo'];
						//print_r($historico_activo);
						//print("LLEGA3");
						break;
					}

					//$resultado = $resultado . "    " .  'El codigo del registro es:' . $codigo;

					if ($codigo != ''){
						$datosConexion = "(WEB);" . $server_name . ";" . $puerto . ";" . $empges . ";" . $apl . ";" . $ejer . ";";
						$accion_conect = $accion_gsb;
						$ventana = $ventana;
						$arg = $arg;
						$respuesta = $respuesta;
						$ip_disp = getRealIP();

						$sql_insert = "SET NAMES utf8;";
						$result1 = mysqli_query($conexion, $sql_insert);

						if ($result1) {

							$sql_insert = "INSERT INTO `pref_a_historico` (`cd_pref`, `datosConexion`, `accion_conect`, `ventana`, `arg`, `respuesta`, `ip_disp`) VALUES (".StringValueSQL($codigo).",".StringValueSQL($datosConexion).",".StringValueSQL($accion_conect).",".StringValueSQL($ventana).",".StringValueSQL($arg).",".StringValueSQL($respuesta).",".StringValueSQL($ip_disp).");";
						

							//print_r($sql_insert);
							$resultado = $sql_insert;
							
							if (mysqli_query($conexion, $sql_insert)) {
							      //$resultado = "New record created successfully";
							} else {
							      //$resultado = "ERROR DBLog : ".mysqli_errno($conexion2) . ": " . mysqli_errno($conexion2) . ";";
							}

						} else {
							$error_sql = "ERROR DBLog => ".mysqli_errno($connect2) . ": " . mysqli_error($connect2) . ";";
							//throw new Exception($error_sql);
						}

						$sql_delete = "SET NAMES utf8;";
						$result2 = mysqli_query($conexion, $sql_delete);

						if ($result2) {
							$sql_delete = "DELETE FROM `pref_a_historico` where `codigo` IN (SELECT `codigo` FROM (SELECT `codigo` FROM `pref_a_historico` WHERE `fecha` is NOT NULL AND `cd_pref` = ".StringValueSQL($codigo)." ORDER BY `fecha` DESC	LIMIT 1400, 10000) AS `x`);";
						

							//print_r($sql_delete);
							
							if (mysqli_query($conexion, $sql_delete)) {
							      $resultado = "--->Tabla limpiada satisfactoriamente";
							} else {
							      $resultado = "--->ERROR DBLog : ".mysqli_errno($conexion) . ": " . mysqli_errno($conexion) . ";";
							}

						} else {
							$error_sql = "ERROR DBLog => ".mysqli_errno($conexion) . ": " . mysqli_error($conexion) . ";";
							//throw new Exception($error_sql);
						}

						//print_r("GustavoEsiten");
						mysqli_close($conexion);

					}
						
				}

			}
		}

	}

	try {
		$resultado = '';
		global $server_name, $dbhostname, $dbusername, $dbpassword, $dbname, $id_app_android;


		//echo("server_name: " . $server_name . ", " . "dbhostname: " . $dbhostname . ", " . "dbusername: " . $dbusername . ", " . "dbpassword: " . $dbpassword . ", " . "dbname: " . $dbname . ", " . "cd_aplicacion: " . $id_app_android);

		//NG20240615 COMPROBAMOS SI TENEMOS EL UID REGISTRADO Y DEVOLVEMOS EL CDAUTOGEN SI LO TUVIERA.
		if ($accion == 'comprobar_uid'){
			if($uuid == ''){
				$resultado = "Error: No se ha especificado el uuid.";
			} else {

				$conexion = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);

				if (mysqli_connect_errno()) {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  exit();
				}

				//$sql = "SELECT `cd_autogen` FROM uuid_autogen_web WHERE uuid_autogen_web.cd_aplicacion = ".StringValueSQL($id_app_android)." and uuid_autogen_web.uuid = ".StringValueSQL($uuid);

				$sql = "SELECT `cd_pref_autogen` FROM uuid_autogen_web WHERE uuid_autogen_web.id_app_android = ".StringValueSQL($id_app_android)." and uuid_autogen_web.uuid = ".StringValueSQL($uuid);

				$sql = $sql. ";";

				// Perform query
				if ($result = mysqli_query($conexion, $sql)) {
					//echo "Returned rows are: " . mysqli_num_rows($result);
					// Free result set
					//NG20240617 AUNQUE ES UN WHILE SOLO ESPERAMOS UN RESULTADO.
					while ($row = mysqli_fetch_array($result)) {
						$row = array_map("utf8_decode", $row);
						$resultado = $row[0];
					}
				}

				//echo "llega";
				//echo $resultado;
				//echo "prosibue";
				
				if ($conexion == null){
					mysqli_close($conexion);
				}

			}

		} else if($accion == 'comprobar_cdautogen'){
			//NG20240617 ESTA ACCION COMPRUEBA EL AUTOGEN, DEVUELVE PREF ASOCIADAS Y LO ESCRIBE EN LA TABLA uuid_autogen_web
			//PARA QUE NO LO PIDA LA SIGUIENTE VEZ.
			if($uuid == ''){
				$resultado = "Error: No se ha especificado el uuid.";
			} else {

				$conexion = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);

				if (mysqli_connect_errno()) {
				  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				  exit();
				}

				$sql = "SELECT * FROM `preferencias_aplicacion` WHERE preferencias_aplicacion.codigo = ";
				$sql = $sql."( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = ".StringValueSQL($cd_pref_autogen)." LIMIT 1)";

				//$sql = "SELECT * FROM `preferencias_aplicacion` WHERE preferencias_aplicacion.codigo = ( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = '84D9EE199' LIMIT 1)";

				$sql = $sql. ";";

				// Perform query
				if ($result = mysqli_query($conexion, $sql)) {
					//echo "Returned rows are: " . mysqli_num_rows($result);
					// Free result set
					//NG20240617 AUNQUE ES UN WHILE SOLO ESPERAMOS UN RESULTADO.
					while ($row = mysqli_fetch_array($result)) {
						$row = array_map("utf8_decode", $row);
						$resultado = $row[0];
					}
				}

				//NG20240626 LLEGADOS A ESTE PUNTO, DEBEREMOS BORRAR SI EXISTIESE ESE UUID, DE ESTA ID_APP_ANDROID YA CON OTRO CD_PREF_AUTOGEN, SIGNIFICARÁ QUE SE HA ENTRADO A LAS PREF Y SE HA MODIFICADO EL CD_PREF
				if ($resultado != ''){

					$sql_delete = "SET NAMES utf8;";
					$result2 = mysqli_query($conexion, $sql_delete);

					if ($result2) {
						$sql_delete = "DELETE FROM `uuid_autogen_web` where `uuid` = ".StringValueSQL($uuid)." AND `id_app_android`= ".StringValueSQL($id_app_android).";";					

						//print_r($sql_delete);
						
						if (mysqli_query($conexion, $sql_delete)) {
							//$resultado = "--->Registro de anteriores pref eliminado.";


							$fecha_act = date('Y-m-d H:i:s');
							//NG20240617 Proseguimos con la inserción del autogen en la nueva tabla.
							//para que quede registrado y no lo vuelva a pedir en la dupla dispositivo/navegador.
							$sql_insert = "INSERT INTO `uuid_autogen_web`(`uuid`, `id_app_android`, `cd_pref_autogen`, `fecha_alta`, `nav_y_disp`) ";
							$sql_insert = $sql_insert . "VALUES (".StringValueSQL($uuid).",".StringValueSQL($id_app_android).",".StringValueSQL($cd_pref_autogen).",".StringValueSQL($fecha_act).",".StringValueSQL($nav_y_disp).")";

							$sql_insert = $sql_insert. ";";


							if (mysqli_query($conexion, $sql_insert)) {
								//echo "New record created successfully";
								//$resultado = "New record created successfully";
								$resultado = 'Ok';
							} else {
								$resultado = "ERROR DBLog : ".mysqli_errno($conexion) . ": " . mysqli_errno($conexion) . ";";
							}


						} else {
						      $resultado = "--->ERROR DBLog : ".mysqli_errno($conexion) . ": " . mysqli_errno($conexion) . ";";
						}

					} else {
						$error_sql = "ERROR DBLog => ".mysqli_errno($conexion) . ": " . mysqli_error($conexion) . ";";
						//throw new Exception($error_sql);
					}

				}

				//echo "llega";
				//echo $resultado;
				//echo "prosibue";
				
				if ($conexion == null){
					mysqli_close($conexion);
				}

			}

		} else if ($accion == 'pref_autogen'){
			//NG20230909 AÑADIMOS NUEVA ACCION PARA OBTENER LAS PREFERENCES POR DEFECTO, ATACANDO AL PHP

			if($cd_pref_autogen != ''){

				//print("eentra");

				$conexion = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);

				if (mysqli_connect_errno()) {
				  echo "Error: Failed to connect to MySQL: " . mysqli_connect_error();
				  exit();
				}

				if ($conexion) {
					//$sql = "SELECT * FROM `productos_versiones` ";
					//$sql = $sql." WHERE `cd_pref_autogen` = '".mysql_real_escape_string($cd_pref_autogen)."'";

					//SELECT * FROM productos_ver_pref WHERE productos_ver_pref.codigo = ( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = '47D1E9148' );
					$sql = "SELECT * FROM preferencias_aplicacion WHERE preferencias_aplicacion.codigo = ";
					$sql = $sql."( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = ".StringValueSQL($cd_pref_autogen)." LIMIT 1)";

					$sql = $sql. ";";

					//print($sql);
            		// Ejemplo: http://www.globalsystem.es/check_updates.php?accion=pref_autogen&cd_pref_autogen=F89913100


					$query = mysqli_query($conexion, $sql);
					
					if ($query) {
						/**while ($row = mysql_fetch_assoc($query)) {
							$row = array_map("utf8_decode", $row);
							$resultado = "{\n    \"forzar_cambio\":".$row['forzar_cambio'].",\n    \"servidor_ip_publica\":\"".$row['servidor_ip_publica']."\"\n}";
							break;
						}*/

						//NG20230911 MANERA NUEVA, PARA NO TENER QUE MODIFICAR ESTE PHP CADA VEZ QUE METEMOS UN CAMPO NUEVO EN LA TABLA.
						while($row2 = mysqli_fetch_array($query)) {
							$row2 = array_map("utf8_decode", $row2);
							$array[] = $row2;

							//NG20230911 Lo metemos dentro del while porque solo esperamos el primer resultado.
							$resultado = json_encode($array);
							//NG20230911 Quitamos el primer y ultimo caracter, son corchetes, por no cambiar la forma en Android.
							//if (strlen($resultado)>0){
							//	$resultado = substr($resultado, 0, -1);
							//	$resultado = substr($resultado, 1);					
							//}

							break;
						}

						//echo $resultado;
						
					}

					if ($conexion == null){
						mysqli_close($conexion);
					}

				}

			} else {				
				$resultado = "Error: No se ha especificado el código de licencia.";
			}

		} else if ($accion == 'historico_conection'){
			//NG20230909 AÑADIMOS NUEVA ACCION PARA ESCRIBIR EL HISTORICO SI LOS VALORES DE LAS PREF SON LOS ADECUADOS, ATACANDO AL PHP
			//print("<script>alert('que te den');</script>");			

			if($cd_pref_autogen != ''){
				$conexion = mysqli_connect($dbhostname, $dbusername, $dbpassword, $dbname);

				if (mysqli_connect_errno()) {
				  echo "Error: Failed to connect to MySQL: " . mysqli_connect_error();
				  exit();
				}

				if ($conexion) {
					$sql = "SELECT * FROM `preferencias_aplicacion` WHERE preferencias_aplicacion.codigo = ";
					$sql = $sql."( SELECT productos_versiones.id_pref FROM productos_versiones WHERE productos_versiones.cd_pref_autogen = ".StringValueSQL($cd_pref_autogen)." LIMIT 1)";

					$sql = $sql. ";";

					//$resultado = $sql;

					//$query = mysqli_query($conexion, $sql);
					$query = mysqli_query($conexion, $sql);

					//$resultado = $resultado . "    " . $query . mysqli_errno($conexion);

					if ($query) {
						while ($row = mysqli_fetch_array($query)) {
							$row = array_map("utf8_decode", $row);
							$codigo = $row['codigo'];
							//print_r($historico_activo);
							//print("LLEGA3");
							break;
						}

						//$resultado = $resultado . "    " .  'El codigo del registro es:' . $codigo;

						if ($codigo != ''){
							$datosConexion = $server_name . ";" . $puerto . ";" . $empges . ";" . $apl . ";" . $ejer . ";";
							$accion_conect = $accion_gsb;
							$ventana = $ventana;
							$arg = $arg;
							$respuesta = $resultado;
							$ip_disp = getRealIP();

							$sql_insert = "SET NAMES utf8;";
							$result1 = mysqli_query($conexion, $sql_insert);

							if ($result1) {

								$sql_insert = "INSERT INTO `pref_a_historico` (`cd_pref`, `datosConexion`, `accion_conect`, `ventana`, `arg`, `respuesta`, `ip_disp`) VALUES (".StringValueSQL($codigo).",".StringValueSQL($datosConexion).",".StringValueSQL($accion_conect).",".StringValueSQL($ventana).",".StringValueSQL($arg).",".StringValueSQL($respuesta).",".StringValueSQL($ip_disp).");";
							

								print_r($sql_insert);
								$resultado = $sql_insert;
								
								if (mysqli_query($conexion, $sql_insert)) {
								      //$resultado = "New record created successfully";
								} else {
								      //$resultado = "ERROR DBLog : ".mysqli_errno($conexion2) . ": " . mysqli_errno($conexion2) . ";";
								}

							} else {
								$error_sql = "ERROR DBLog => ".mysqli_errno($connect2) . ": " . mysqli_error($connect2) . ";";
								//throw new Exception($error_sql);
							}

							$sql_delete = "SET NAMES utf8;";
							$result2 = mysqli_query($conexion, $sql_delete);

							if ($result2) {
								$sql_delete = "DELETE FROM `pref_a_historico` where `codigo` IN (SELECT `codigo` FROM (SELECT `codigo` FROM `pref_a_historico` WHERE `fecha` is NOT NULL AND `cd_pref` = ".StringValueSQL($codigo)." ORDER BY `fecha` DESC	LIMIT 1400, 10000) AS `x`);";
							

								print_r($sql_delete);
								
								if (mysqli_query($conexion, $sql_delete)) {
								      $resultado = "--->Tabla limpiada satisfactoriamente";
								} else {
								      $resultado = "--->ERROR DBLog : ".mysqli_errno($conexion) . ": " . mysqli_errno($conexion) . ";";
								}

							} else {
								$error_sql = "ERROR DBLog => ".mysqli_errno($conexion) . ": " . mysqli_error($conexion) . ";";
								//throw new Exception($error_sql);
							}

							//print_r("GustavoEsiten");
							mysqli_close($conexion);

						}
							
					}

				}
			}

		} else if ($accion == "a_comprobar_conexion"){
			//$resultado = "accion: " . $accion . ", ventana: " . $ventana . ", server: " . $server . ", puerto: " . $puerto . ", empges: " . $empges . ", apl: " . $apl . ", ejer: " . $ejer . ", arg: " . $arg;
			$resultado = EjecutarAccionVelneo($accion, $ventana, $server, $puerto, $empges, $apl, $ejer, $arg);
			$array = json_decode($resultado, true);
			$resultado =  $array["resultado"];
			if ($resultado == "ok"){
				$resultado = "La conexión con gsBase se ha establecido con éxito!!!";
			}

		} else if ($accion == "ejecutar_accion_gsb"){
			//$resultado = "accion: " . $accion . ", ventana: " . $ventana . ", server: " . $server . ", puerto: " . $puerto . ", empges: " . $empges . ", apl: " . $apl . ", ejer: " . $ejer . ", arg: " . $arg . ", historico_activo: " . $historico_activo . ", cd_pref_autogen: " . $cd_pref_autogen;
			$resultado = EjecutarAccionVelneo($accion_gsb, $ventana, $server, $puerto, $empges, $apl, $ejer, $arg, $cd_pref_autogen);
			/*$array = json_decode($resultado, true);
			$resultado =  $array["resultado"];
			if ($resultado == "ok"){
				$resultado = "La conexión con gsBase se ha establecido con éxito!!!";
			}*/


			if ($historico_activo == "S"){
				//$resultado = "caca negra";
				$respuesta = $resultado;
				//NG20240110 NO FUNCIONA EL HISTORICO EN LOCAL, MAS INFO EN METODO
				EscribirHistorico($accion_gsb, $ventana, $server, $puerto, $empges, $apl, $ejer, $arg, $respuesta, $cd_pref_autogen);
			}

		} else if ($accion == "escribir_historico"){

			$arg = "cdaplicacion: " . $id_app_android . ", " . $arg;

			//NG20240718 DE MOMENTO SOLO LO UTILIZAMOS PARA SALIR DE LA APLICACION
			//EL RESTO DE VECES QUE ESCRIBIMOS EL HISTORICO ES CUANDO EJECUTAMOS LAS ACCIONES DE GSBASE
			//NG20240110 NO FUNCIONA EL HISTORICO EN LOCAL, MAS INFO EN METODO
			$resultado = EscribirHistorico($accion_gsb, $ventana, $server, $puerto, $empges, $apl, $ejer, $arg, $respuesta, $cd_pref_autogen);

		} else {
			$resultado = "Error: No se ha especificado la acción!!!";
		}	

		print($resultado);
		
	} catch (Exception $ex) {
		print($ex->getMessage());
	}
?>