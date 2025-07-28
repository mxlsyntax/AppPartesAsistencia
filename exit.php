<?php
	include("config.php");
	// Evitamos que tenga cach� la p�gina
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$redirect = "index.php";
	$session_id_to_destroy = session_id('id'.'_'.$id_app_android);



	//echo "<script>";
	//echo "alert('Entra en exit');";
	//echo "alert('El usuario es: " . $_REQUEST['id'.'_'.$id_app_android] . "');";
	//echo "alert('El usuario es: " . $_REQUEST['id'.'_'.$id_app_android] . "');";
	//echo "</script>";

	if (isset($_REQUEST['id'.'_'.$id_app_android])) {
		if ($_REQUEST['id'.'_'.$id_app_android] == 1) {	
			//GESTIONAMOS LAS VARIABLES DE SESSION	
			session_id($session_id_to_destroy);	
			@session_start();
			//GESTIONAMOS LAS COOKIES
			$_SESSION = array();
			setcookie("user_id".'_'.$id_app_android, "");
			setcookie("last_activity".'_'.$id_app_android, "");
			setcookie("nombre_trab_login".'_'.$id_app_android, "");
			setcookie("tipo_trab_login".'_'.$id_app_android, "");
			setcookie("sesion_ini".'_'.$id_app_android, false);



			$_COOKIE['user_id'.'_'.$id_app_android] = "";
			$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

			unset($_COOKIE['user_id'.'_'.$id_app_android]);
			setcookie('user_id'.'_'.$id_app_android,'',time()-100);

			$mensaje = "No ha iniciado sesi&oacuten";
			//session_destroy();

		} else if ($_REQUEST['id'.'_'.$id_app_android] == 2) {
			$mensaje = "Login incorrecto";
		} else if ($_REQUEST['id'.'_'.$id_app_android] == 3) {
			//GESTIONAMOS LAS VARIABLES DE SESSION	
			session_id("AppWeb_".$id_app_android);
			@session_start();
			//GESTIONAMOS LAS COOKIES
			unset($_COOKIE['last_activity'.'_'.$id_app_android]);
			unset($_COOKIE['user_id'.'_'.$id_app_android]);
			$_SESSION = array();
			setcookie("user_id".'_'.$id_app_android, "");
			setcookie("last_activity".'_'.$id_app_android, "");
			setcookie("nombre_trab_login".'_'.$id_app_android, "");
			setcookie("tipo_trab_login".'_'.$id_app_android, "");
			setcookie("sesion_ini".'_'.$id_app_android, false);



			$_COOKIE['user_id'.'_'.$id_app_android] = "";
			$_COOKIE['last_activity'.'_'.$id_app_android] = "";
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = ""; 
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

			$mensaje = "Sesi&oacuten desconectada";
			//session_destroy();

		} else if ($_REQUEST['id'.'_'.$id_app_android] == 11) {		
			//GESTIONAMOS LAS VARIABLES DE SESSION
			session_id("AppWeb_".$id_app_android);		
			@session_start();
			
			//GESTIONAMOS LAS COOKIES
			unset($_COOKIE['user_id'.'_'.$id_app_android]);
			unset($_COOKIE['last_activity'.'_'.$id_app_android]);
			$_SESSION = array();			
			setcookie("user_id".'_'.$id_app_android, "");			
			setcookie("last_activity".'_'.$id_app_android, "");
			setcookie("nombre_trab_login".'_'.$id_app_android, "");
			setcookie("tipo_trab_login".'_'.$id_app_android, "");
			setcookie("sesion_ini".'_'.$id_app_android, false);
			$_COOKIE['user_id'.'_'.$id_app_android] = "";
			$_COOKIE['last_activity'.'_'.$id_app_android] = ""; 
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = false;

			$mensaje = "Demasiado tiempo de inactividad.";
			//print("<script>setTimeout('window.close();', 3000);</script>");
			//session_destroy();

		} else if ($_REQUEST['id'.'_'.$id_app_android] == 12) {	
			//GESTIONAMOS LAS VARIABLES DE SESSION
			session_id("AppWeb_".$id_app_android);						
			@session_start();
			//GESTIONAMOS LAS COOKIES	
			setcookie("user_id".'_'.$id_app_android, "");			
			setcookie("last_activity".'_'.$id_app_android, "");	
			setcookie("nombre_trab_login".'_'.$id_app_android, "");
			setcookie("tipo_trab_login".'_'.$id_app_android, "");	
			unset($_COOKIE['user_id'.'_'.$id_app_android]);
			unset($_COOKIE['last_activity'.'_'.$id_app_android]);
			$mensaje = "Usuario no registrado.";
			$_SESSION = array();
			setcookie("sesion_ini".'_'.$id_app_android, false);
			$_COOKIE['user_id'.'_'.$id_app_android] = "";
			$_COOKIE['last_activity'.'_'.$id_app_android] = "";
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = ""; 
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = false; 
			print("<script>setTimeout('window.close();', 3000);</script>");
			//session_destroy();

		} else if ($_REQUEST['id'.'_'.$id_app_android] == 13) {	
			//GESTIONAMOS LAS VARIABLES DE SESSION
			session_id("AppWeb_".$id_app_android);					
			@session_start();
			//GESTIONAMOS LAS COOKIES
			unset($_COOKIE['user_id'.'_'.$id_app_android]);
			unset($_COOKIE['last_activity'.'_'.$id_app_android]);
			//$_SESSION['ok_cookies'] = false;
			$_SESSION = array();						
			setcookie("ok_cookies".'_'.$id_app_android, false);			
			setcookie("last_activity".'_'.$id_app_android, "");			
			setcookie("user_id".'_'.$id_app_android, "");
			setcookie("nombre_trab_login".'_'.$id_app_android, "");
			setcookie("tipo_trab_login".'_'.$id_app_android, "");
			setcookie("sesion_ini".'_'.$id_app_android, false);
			$_COOKIE['user_id'.'_'.$id_app_android] = "";
			$_COOKIE['last_activity'.'_'.$id_app_android] = "";
			$_COOKIE['nombre_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['tipo_trab_login'.'_'.$id_app_android] = "";
			$_COOKIE['sesion_ini'.'_'.$id_app_android] = false; 
			$mensaje = "Sin las cookies, la aplicaci�n no tendr&aacute un correcto funcionamiento.";
			print("<script>setTimeout('window.close();', 3000);</script>");
			//session_destroy();
			
		} else if ($_REQUEST['id'.'_'.$id_app_android] == 14) {	
			//GESTIONAMOS LAS VARIABLES DE SESSIOn
			//unset($_COOKIE['tabID']);
			//$_COOKIE['tabID'] = "";			
			setcookie("tabID".'_'.$id_app_android, "");
			$mensaje = "S&oacutelo se puede tener una sesi&oacuten abierta.";
			print("<script>setTimeout('window.close();', 3000);</script>");
		}
	}
	//session_unset();
	print("<script>setTimeout('window.location.href = \'".$redirect."\';', 3000);</script>");
?>
<!DOCTYPE html>
<html lang="es">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 

<html>
	<head>

	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplicación Web para registrar partes de asistencia" />
    <meta name="author" content="NG" />

    <!-- Para acceso directo-->
    <meta name="apple-mobile-web-app-title" content="Partes Asistencia">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="shortcut icon" sizes="16x16" href="/assets/favicon-16x16.png">
    <link rel="shortcut icon" sizes="192x192" href="/assets/icon-192x192.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/mstile-150x150.png">


    <title>Partes Asistencia</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Con el manifest saldrá la pregunta, al menos en Chrome de si queremos instalarla-->
    <link rel="manifest" href="manifest.json">

	<script languaje="JavaScript">
	</script>


	<STYLE TYPE="text/css">
		
		body {
			background-image: url(Imagenes_grid/PageBg.gif);
			background-repeat: repeat;
			margin-top: 10px;
			margin-left: 0px;
			}
			a {text-decoration: none} 
			a { color:}
			a:hover{color:;} 

		.art-postcontent h2 {
		    font-family: Arial, Helvetica, Sans-Serif;
		    font-size: 29px;
		    color: #888d8e;
		    margin: 10px 0 0;
		    text-shadow: 2px 0px 0px #6b6f72;
		}



		 div #sesion{
		    height: 70%;
		    width: 50%;
		    text-align: justify;
		    margin-left: auto;
		    margin-right: auto;
		    margin-top: 20%;
			margin-bottom: auto;
		    align-items: center;
		    font-family: Arial, Helvetica, Sans-Serif;
   			font-size: 29px;
    		color: #888d8e;
    		text-shadow: 2px 0px 0px #6b6f72;
		}
		
	    </STYLE>
	    
	    <link rel="stylesheet" href="./geslive/style.css" type="text/css" media="screen" />	    
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	    
	</head>
	<body>
		<div id="Navegador" title="Geslive"  align="center"  hidden="true" >
			<table align="center">
				<tr><td  align="center">Su navegador Internet Explorer est� desactualizado. Algunos elementos de esta aplicaci�n podrian no funcionar bien.</td></tr>
				
				<tr><td  align="center">Navegadores v�lidos: Chrome, Mozilla Firefox, Internet Explorer 9 o superior, Opera y Safari.</td></tr>
				<tr>
					<td   align="center" style="font-weight:400; font-size:14px; font-family:Verdana, Geneva, sans-serif"> <a href="http://windows.microsoft.com/es-es/internet-explorer/downloads/ie-9/worldwide-languages"> Por favor actualicelo a la �ltima versi�n haciendo click aqu�.</a	></td></tr>
				</table>
			</div>
			
			<div id="art-page-background-middle-texture">
				<div id="art-page-background-glare-wrapper">
					<div id="art-page-background-glare"></div>
				</div>
				<div id="art-main">
					<div class="cleared reset-box"></div>
					<div class="art-header">
						<div class="art-header-position">
							<div class="art-header-wrapper">
								<div class="cleared reset-box"></div>
								<div class="art-header-inner">
									<div class="art-logo">
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="cleared reset-box"></div>
					<div class="art-bar art-nav" style="background-image:url(./geslive/footer.png)">
					</div>
					<div class="cleared reset-box"></div>
				</div>
					<div class="art-box art-sheet">
						<div class="art-box-body art-sheet-body">
							<div class="art-layout-wrapper">
								<div class="art-content-layout">
									<div class="art-content-layout-row">
										<div class="art-layout-cell art-content">
											<div class="art-box art-post">
												<div class="art-box-body art-post-body">
													<div class="art-post-inner art-article">			
														<div class="art-postcontent">
															<div id ="sesion" align="center">
																<p id = "psesion" align="center">		<?php echo $mensaje?></p>				
																<!--<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=D9jd8D7eEkAwbXNss6cmBp9d44ycAwhR4FxWxQ8OKGPQ7zHbkETwLa3EYbm3"></script></span> -->
															</div>
															<div align="center"></div>
															<div align="center">
																<div align="center" style="font-size:18px; font-weight:600">
																	
																	
																</div>
															</div>
															
														</div>
														
													</div>
												</div>
												<div class="art-box art-post">
													<div class="art-box-body art-post-body">
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="cleared"></div>
								
								<div class="cleared"></div>
							</div>
						</div>
						
						<div class="cleared"></div>
					</div>
					<div class="art-footer">
					</div>
			</body>
</html>