<?php
//NG20240617 PARA PERMITIR CONEXIONES DE OTRO HTML QUE NO ESTE EN ESTE SERVIDOR (LOCALHOST PARA PRUEBAS)
header("Access-Control-Allow-Origin: www.globalsystem.es");
// Configuración de alias para variables de servidor
$ip_port = $_SERVER['HTTP_HOST']; //IP:PORT
$server_name = $_SERVER['SERVER_NAME']; //IP
$server_port = $_SERVER['SERVER_PORT']; //PUERTO

if (($server_name == 'localhost')) {
	$web_local = true;
} else {
	$web_local = false;
}

$dbhostname = 'globalsynet.mysql.db';
$dbusername = 'globalsynet';
$dbpassword = '7System20';
$dbname = 'globalsynet';

if (!$web_local) {
	$dbhostname = 'globalsynet.mysql.db';
	$dbusername = 'globalsynet';
	$dbpassword = '7System20';
	$dbname = 'globalsynet';
} else {
	$dbhostname = $server_name;
	$dbusername = 'global';
	$dbpassword = '7System20';
	$dbname = 'extranet';
}

//NG20240617 ESTE ID DEBEREMOS CAMBIARLO A HUEVO, CUANDO COPIEMOS LA WEB PARA OTRA APP.
//APP FICHAJES WEB (00201)
//APP PORTAL EMPLEADO (00203)
//APP PORTAL CEMENTOS (00206)
//APP VIAJES DIETAS (00210)
//APP PARTES ASISTENCIA (00212)
$id_app_android = '00212'; //Se utiliza en toda la app.
$gsbase_user = "__*global";
$gsbase_pass = "global";

$phpmailer_plugindir = './libs/phpmailer/';
$phpmailer_smtp_host = 'ssl0.ovh.net';
$phpmailer_smtp_port = 465;
$phpmailer_smtp_username = 'soporte@globalsystem.es';
$phpmailer_smtp_password = '1607Soporte';
$phpmailer_tipo = 'sendmail'; //smtp/ sendmail
//$phpmailer_tipo = 'smtp'; //smtp/ sendmail

// Configurar si se genera log para depurar
$debug_sql = true;
$debug_json = true;
$display_errors = '1'; // '0': No; '1': Sí

// Configuración de direcciones de correo electrónico
$email_web = 'no-reply@globalsystem.es';
$email_admin = 'ngalvez@globalsystem.es';

$offset_hash = "globalsystem";
$aceptar_rechazar_web = false;

?>
