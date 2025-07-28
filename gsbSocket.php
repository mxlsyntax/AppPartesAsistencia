<?php
class gsbSocket {

	public $socket;
	public $LastErrorMsg;
	public $Estado;
	public $SEP;

	function __construct() {
		$this->Estado = 0;
		$this->SEP = chr(2);
		$this->LastErrorMsg = "";
		return 0;
	}

	function GetLastErrorStr() {
		return $this->LastErrorMsg;
	}

	function Connect($ip, $port) {
		$this->LastErrorMsg = "";

		if ($this->Estado != 0) {
			$this->Disconnect();
			$this->LastErrorMsg = "Error: La conexi�n no estaba cerrada!";
			return -1;
		}

		$errno = "";
		$errstr = "";
		$socket = fsockopen($ip, $port, $errno, $errstr);
		if (!$socket) {
			$this->LastErrorMsg = "No se pudo conectar. Error: " . $errno . " " . $errstr;
			return -1;
		}

		$welcome = "";
		while (true) {
			$c = fgets($socket, 2);
			$welcome .= $c;
			if (chr(1) . chr(2) == substr($welcome, -2)) break;
		}

		$this->socket = $socket;
		$this->Estado = 1;

		return 0;
	}
	
	//
	// Cerrar la conexi�n con el Servidor gsBase
	//
	function Disconnect(){
		$this->LastErrorMsg="";
		
		try{
			fclose($this->socket);
		}
		catch(Exception $ex){
			$this->LastErrorMsg="Error al Desconectar: " . $ex->getMessage();
			return -1;
		}
		
		$this->Estado=0;	 //Desconectado
		return 0;
	}
	
	//
	// Identificarse en el sistema
	//
	function Logon($EmpGes, $User, $Pass, $Aplic, $Ejer, $Pw_Aplic, $Pw_Ejer){
		$gsbCommand = "p_logon";
		$argum = $EmpGes.",".$User.",".$Pass.",".$Aplic.",".$Ejer.",".$Pw_Aplic.",".$Pw_Ejer;
		
		$respu="";
		if ($this->Envia($gsbCommand,$argum,$respu) != 0){
			$this->LastErrorMsg = "No se pudo realizar el Logon: " . $this->LastErrorMsg;
			return -1;
		}
		
		if ($respu == ""){
			return -1;
		}
			
		$spl = explode($this->SEP, $respu);
		if ($spl[0] != "Ok"){
			$this->LastErrorMsg = "No se pudo realizar el Logon: "+utf8_encode($spl[1]);
			return -1;
		}
		
		return 0;
	}
	
	//
	// Ejecutar una acci�n de servidor en gsBase
	//
	function rgsb($accion, $ventana, $argum){
		$respu="";
		$gsbCommand = $accion . "|" . $ventana;
		 
		if ($this->Envia($gsbCommand,$argum,$respu) != 0){
			return "";
		}
		
		$spl = explode($this->SEP, $respu);
		if ($spl[1] != ""){
			$this->LastErrorMsg = $spl[1];
			return "";
		}
		
		return $spl[0];
	}
	
	//
	// Comunicaci�n con el servidor
	//
	function Envia($accion,$arg,&$respu){
		$command = $accion . $this->SEP . $arg;
		$this->LastErrorMsg="";
		$this->Estado = 3;
		
		$lenx = dechex(strlen($command));
		while (strlen($lenx)<6) $lenx = "0".$lenx;
		$command = $lenx . $command;
		
		fputs($this->socket,$command);
		
		$chlen = fgets($this->socket, 7);
		$len = hexdec($chlen);
		$max = 1024;
		
		$respu = "";
		$cont = 0;
		while (true){
			if ($len<1) break;
			if ($len<$max) $max=$len;
			$data= fgets($this->socket, $max+1);
			$respu .= $data;
			$len = $len - strlen($data);
			if ($cont>1000) break; //Para no entrar en bucle infinito
			$cont+=1;
		}
		return 0;
	}
	
}
?>