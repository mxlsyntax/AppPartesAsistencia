<?php
//require_once("config.php");
//require_once("conex.php");
require_once("login.php"); //Para iniciar sesión si no está iniciada
include("control_sesion.php"); //Control de tiempo de sesion, etc
include("config.php"); //Para tener el id_app_fijo por ejemplo
error_reporting(E_ERROR | E_PARSE); //Para que no muestre los warnings

/*echo "<script>";
echo "alert('Entra en salir');";
echo "alert('El nombre_trab_login es: " . $_COOKIE['nombre_trab_login'] . "');";
echo "</script>";*/

//Si ventana alta es = 1, será para realizar alta, de lo contrario será para pintar los datos para ver detalle.
$ventana_alta = isset($_REQUEST['ventana_alta']) ? $_REQUEST['ventana_alta'] : 0;

//Valores para cuando volvamos a la ventana anterior.
$cd_trabajador_bus = isset($_REQUEST['cd_trabajador_bus']) ? $_REQUEST['cd_trabajador_bus'] : "";
$deno_trabajador_bus = isset($_REQUEST['deno_trabajador_bus']) ? $_REQUEST['deno_trabajador_bus'] : "";
$fecha_ini_bus = isset($_REQUEST['fecha_ini_bus']) ? $_REQUEST['fecha_ini_bus'] : "";
$fecha_fin_bus = isset($_REQUEST['fecha_fin_bus']) ? $_REQUEST['fecha_fin_bus'] : "";

//'cdAviso=' + cdAviso + '&cd_trabajador_sel=' + cd_trabajador_sel + '&deno_trabajador_sel=' + deno_trabajador_sel + '&fecha=' + fecha + '&hora=' + hora + '&remit=' + remit + '&asunto=' + asunto + '&vent=' + vent + '&cd_reg_aso=' + cd_reg_aso + '&texto=' + texto + '&ventana_alta=' + 0;

//Valores para pintarlos si vienen.
$cdaviso = isset($_REQUEST['cdaviso']) ? $_REQUEST['cdaviso'] : "";
$cd_trabajador_sel = isset($_REQUEST['cd_trabajador_sel']) ? $_REQUEST['cd_trabajador_sel'] : "";
$deno_trabajador_sel = isset($_REQUEST['deno_trabajador_sel']) ? $_REQUEST['deno_trabajador_sel'] : "";
$fecha = isset($_REQUEST['fecha']) ? $_REQUEST['fecha'] : "";
$hora = isset($_REQUEST['hora']) ? $_REQUEST['hora'] : "";

$remit = isset($_REQUEST['remit']) ? $_REQUEST['remit'] : "";
$asunto = isset($_REQUEST['asunto']) ? $_REQUEST['asunto'] : "";
$texto = isset($_REQUEST['texto']) ? $_REQUEST['texto'] : "";
$vent = isset($_REQUEST['vent']) ? $_REQUEST['vent'] : "";
$cd_reg_aso = isset($_REQUEST['cd_reg_aso']) ? $_REQUEST['cd_reg_aso'] : "";
$est_lei = isset($_REQUEST['est_lei']) ? $_REQUEST['est_lei'] : ""; //Nuevo, Leido, Resuelto

$ejercicio_cl = isset($_REQUEST['ejercicio_cl']) ? $_REQUEST['ejercicio_cl'] : "";

//echo "<script>";
//echo "alert('texto: " . $texto . "');";
//echo "alert('vent: " . $vent . "');";
//echo "</script>";

?>
<!DOCTYPE html>
<html lang="es">
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
        <link rel="shortcut icon" sizes="192x192" href="/assets/icons/icon-192x192.png">
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
        <!-- JQUERY -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Tablas bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
        <!-- <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
            Icons bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <!-- Autorefresco bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>

        <!-- Export bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/libs/jsPDF/jspdf.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/export/bootstrap-table-export.min.js"></script>

        <!-- El siguiente script es para que alterne automaticamente el tipo de vista de la tabla, si nota que no cogen
         todas las columnas ademas hay que establecer en la def html de la tabla lo siguiente: data-mobile-responsive="true" establece la vista que establecemos con toggle... si no coge la tabla completa -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table-locale-all.min.js"></script>
        

        <!-- Utilidades diversas, como gestion de fechas gsBase -->        
        <script src="js/utilidades.js"></script>

        <script>
            const cdappfijo = "<?php echo $id_app_android; ?>";

            var hostname = window.location.hostname;
            //NG20240628 PARA LAS CONEXIONES CON LA BD, PARA CUANDO HACEMOS PRUEBAS
            //EN LOCAL, NECESITAMOS LLAMAR A LO QUE HAY EN LA WEB, PORQUE SI NO NO TENEMOS ACCESO 
            //A LA BD DE PHPMYADMIN. El php para acceder a la base de datos del phpmyadmin tienen que estar
            //en el mismo lugar que la bd.
            //y para acceder a gsbase tiene que estar publico o tambien en el mismo sitio.
            //NG20241003 AHORA ESTABLECEMOS LA URL SOLO EN INDEX
            /*var url_conexion = "funciones.php";
            if (hostname == "localhost"){
                url_conexion = "https://www.globalsystem.es/AppWeb/PortalEmpleado/" + url_conexion;
            }*/            

            const llamadas_php = GetVariableLocalStorage("llamadas_php");
            const servidor_ip_publica = GetVariableLocalStorage("servidor_ip_publica");
            const puerto = GetVariableLocalStorage("puerto");
            const empresa_gestora = GetVariableLocalStorage("empresa_gestora");
            const ventana_pref = GetVariableLocalStorage("ventana_pref");
            const aplicacion = GetVariableLocalStorage("aplicacion");
            const ejercicio = GetVariableLocalStorage("ejercicio");
            const empresa_id = GetVariableLocalStorage("empresa_id");
            const cdaplicacion = GetVariableLocalStorage("cdaplicacion");
            const cd_pref_autogen = GetVariableLocalStorage("cd_pref_autogen");
            const historico_activo = GetVariableLocalStorage("historico_activo");
            const url_conexion = GetVariableLocalStorage("url_conexion");

            const cdtrabajador_login = GetVariableLocalStorage("cdtrabajador_login");
            const nombre_trab_login = GetVariableLocalStorage("nombre_trab_login");               
            const tipo_trab_login = GetVariableLocalStorage("tipo_trab_login");

            const modo_dios = GetVariableLocalStorage("modo_dios");

            //NG20240726 PARTICULARES DE ESTA VENTANA.
            const cd_trabajador_bus = "<?php echo $cd_trabajador_bus; ?>";
            const deno_trabajador_bus = "<?php echo $deno_trabajador_bus; ?>";

            const cdaviso = "<?php echo $cdaviso; ?>";

            const cd_trabajador_sel = "<?php echo $cd_trabajador_sel; ?>";
            const deno_trabajador_sel = "<?php echo $deno_trabajador_sel; ?>";
            const fecha_ini_bus = "<?php echo $fecha_ini_bus; ?>";
            const fecha_fin_bus = "<?php echo $fecha_fin_bus; ?>";



            const remit = "<?php echo $remit; ?>";
            const asunto = "<?php echo $asunto; ?>";

            const fecha = "<?php echo $fecha; ?>";
            const hora = "<?php echo $hora; ?>";
            const texto = "<?php echo $texto; ?>";
            const vent = "<?php echo $vent; ?>";
            const cd_reg_aso = "<?php echo $cd_reg_aso; ?>";
            const ejercicio_cl = "<?php echo $ejercicio_cl; ?>";

            //NG20240725 COMO EL ATRAS NO FUNCIONA PARA EL CD DEL TRABAJADOR, CUANDO VOLVEMOS DEL DETALLE
            //LO GESTIONAMOS MANUALMENTE.
            const ventana_alta = "<?php echo $ventana_alta; ?>";  

            var acciones_info = "";
            if (ventana_alta == 0){
                acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_revisar_aviso, a_devolver_documentos";
            } else {
                acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_crear_nuevo_aviso";
            }

            if (tipo_trab_login == 'G'){
                var cdresponsable = cdtrabajador_login;
            } else {
                var cdresponsable = ""; 
            }

            //alert("vENTANA ALTA: " + ventana_alta);
            //alert(Num_aFecha(9137));

            function GetVariableLocalStorage(nombre_variable){
                return localStorage.getItem(nombre_variable + "_" + cdappfijo);
            }

            function Mostrar_acciones_info(){
                alert(acciones_info);
            }

            function Atras(auto = false){
                //habra que cambiar para cerrar sesion y esas cosas
                if(ventana_alta == 1 & !auto){
                    if(confirm("¿Seguro que desea cancelar la solicitud?")){
                        location.href='avisos_bus.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus  + '&fecha_fin_bus=' + fecha_fin_bus;
                    }
                } else {
                    location.href='avisos_bus.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus  + '&fecha_fin_bus=' + fecha_fin_bus;
                }                
            }

            //Este metodo hay que implementarlo en las ventanas en las que llamemos a gsBase
            //NG20240702 comprobamos los datos guardados en el localstorage en el lado del cliente
            //NG20240702 comprobamos los datos guardados en el localstorage en el lado del cliente
            //ingresa los datos en localstorage si no lo están ya, para siguientes ventanas.
            //Este metodo hay que implementarlo en las ventanas en las que llamemos a gsBase
            function ComprobarConfiguracion(){

                if (nombre_trab_login != ""){
                    document.getElementById('nombre_trab_html').innerText = nombre_trab_login;
                }


                var vis_solo_jefegrupo = 'none';
                var nom_tipo_trab = localStorage.getItem("nom_tipo_trab");
                if ((nom_tipo_trab == "")||(nom_tipo_trab === null)){                    
                    nom_tipo_trab = "Tipo de trab. desconocido";
                }

                //NG20240704 ESTABLECEMOS EL TIPO DE TRABAJADOR Y DAMOS VISIBILIDAD A LOS 
                //BOTONES SEGUN EL TIPO.


                if (tipo_trab_login == 'A'){
                    nom_tipo_trab = "Administrador";
                    vis_solo_jefegrupo = '';
                    document.getElementById('botonSelTrabajador').disabled = false;
                } else {
                    nom_tipo_trab = "Usuario Normal";
                    document.getElementById('botonSelTrabajador').disabled = true;
                }
                
                
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;                
                document.getElementById('fecha_html').innerText = new Date(Date.now()).toLocaleDateString();
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;

                if (ventana_alta == 0){
                    document.getElementById('cd_trabajador_bus').value = cd_trabajador_sel;
                    document.getElementById('deno_trabajador_bus').value = deno_trabajador_sel;
                    document.getElementById('deno_trabajador_bus').disabled = true;
                    document.getElementById('botonSelTrabajador').disabled = true;                    
                    document.getElementById('remit').value = remit;
                    document.getElementById('remit').disabled = true;
                    document.getElementById('botonSelRemitente').disabled = true; 
                    document.getElementById('asunto').value = asunto;
                    document.getElementById('asunto').disabled = true;
                    document.getElementById('fecha').value = fecha.split('/').reverse().join('-'); 
                    document.getElementById('fecha').disabled = true; 
                    document.getElementById('hora').value = hora;
                    document.getElementById('hora').disabled = true;
                    document.getElementById('texto').value = texto; 
                    document.getElementById('texto').disabled = true;

                    RevisarAviso();

                } else {
                    //NG20240807 SI ES UN ALTA Y NO ES UN JEFE DE GRUPO, ESTABLECEMOS SUS VALORES  
                    if (tipo_trab_login != 'G'){                      
                        document.getElementById('cd_trabajador_bus').value = cdtrabajador_login;
                        document.getElementById('deno_trabajador_bus').value = nombre_trab_login;
                    }
                    document.getElementById('btn_ir_documentos').style.display='none';
                }

                if (vent == 'doperarios'){
                    document.getElementById('btn_ir_documentos').style.display='';
                } else if (vent == 'viajes'){                        
                    document.getElementById('btn_ir_documentos').style.display='';
                }

                if (modo_dios == 1){
                    document.getElementById('img_modo_dios').style.display = ''; 
                }

            }

            function ValidarAlta(){                
                validado = false;

                var cdtrabajador = document.getElementById('cd_trabajador_bus').value;
                var cdtipoausencia = document.getElementById('cd_tipo_aus').value;
                var fecha = Fecha_aNum(format_fecha_local(document.getElementById('fecha').value));
                var hora = document.getElementById('hora').value;

                if (cdtrabajador == ""){
                    alert("Por favor seleccione un trabajador");
                } else if (cdtipoausencia == ""){
                    alert("Por favor seleccion un tipo de ausencia");
                } else if (fecha == 0){
                    alert("La fecha es obligatoria");
                } else {
                    validado = true;
                }

                return validado;
            }

            function RevisarAviso(){
                var respuesta = "";

                //alert("Código de obra" + cod_obra);
                var arg = '{'
                           +'"cdtrabajador" : "' + cdtrabajador_login + '",'
                           +'"cdaplicacion" : "' + cdaplicacion + '",'
                           +'"cdaviso" : "' + cdaviso + '"'
                           +'}';
                    
                //alert(url_conexion);

                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                var accion = "ejecutar_accion_gsb";
                var accion_gsb = "a_revisar_aviso";
                //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + cd_trabajador + ". ");

                //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                $("#overlay").fadeIn(300);

                $.ajax({
                    url: url_conexion,
                    //con esta url si llega a conectar con el php pero no llega a gsbase                    
                    //url: 'https://www.globalsystem.es/AppWeb/PortalEmpleado/funciones.php',
                    data: {"servidor_ip_publica": servidor_ip_publica, "puerto": puerto, "empresa_gestora": empresa_gestora, "aplicacion": aplicacion, "ejercicio": ejercicio, "empresa_id": empresa_id, "ventana_pref": ventana_pref, "arg": arg, "accion": accion, "accion_gsb": accion_gsb, "cd_pref_autogen": cd_pref_autogen, "historico_activo": historico_activo },
                    type: "POST",
                    //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                    timeout: 2000,
                    success: function (response) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        $("#overlay").fadeOut(300);
                        
                        //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                        //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                        //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                        try {
                            respuesta = $.parseJSON(response);
                            /*alert(respuesta);*/

                            if (respuesta['resultado'] == "ok"){                                
                                //alert(respuesta['datos']);
                                console.log(respuesta['datos']);
                            } else {                                                                
                                alert(respuesta['datos']);
                            }

                        } catch (error) {
                            alert(error + "Resp. gsBase: " + response);
                        } 

                    },
                    error: function(xmlhttprequest, textstatus, message) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        $("#overlay").fadeOut(300);

                        if(textstatus==="timeout") {
                            alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                        } else {
                            alert(textstatus);
                        }

                        console.log("Volvemos a intentar revisar el aviso");
                        RevisarAviso();
                     }
                });               
            }

            function controlRelacionado(){
                if (vent == 'doperarios'){
                    a_devolver_documentos();
                } else if(vent == 'viajes'){
                    a_devolver_viaje();
                } else {
                    alert('No se pueden ver los documentos de ' + vent);
                }
            }

            function a_devolver_viaje(){


                var arg = '{'
                           +'"cdtrabajador" : "' + cdtrabajador_login + '",'
                           +'"cdaplicacion" : "' + cdaplicacion + '",'
                           +'"cdaplicacion" : "' + cdaplicacion + '",'
                           +'"cdviaje" : "' + cd_reg_aso + '"'//Que no se nos olvide quitar la coma solo en el ult.element
                           +'}';

                //alert(arg);

                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                var accion = "ejecutar_accion_gsb";
                var accion_gsb = "a_leer_viaje";

                //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                //$("#overlay").fadeIn(300);

                $.ajax({
                    url: url_conexion,
                    //con esta url si llega a conectar con el php pero no llega a gsbase                    
                    //url: 'https://www.globalsystem.es/AppWeb/PortalEmpleado/funciones.php',
                    data: {"servidor_ip_publica": servidor_ip_publica, "puerto": puerto, "empresa_gestora": empresa_gestora, "aplicacion": aplicacion, "ejercicio": ejercicio, "empresa_id": empresa_id, "ventana_pref": ventana_pref, "arg": arg, "accion": accion, "accion_gsb": accion_gsb, "cd_pref_autogen": cd_pref_autogen, "historico_activo": historico_activo},
                    type: "POST",
                    //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                    timeout: 2000,
                    success: function(response) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        //$("#overlay").fadeOut(300);
                        //alert(response);

                        //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                        //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                        //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                        try {
                            var respuesta = $.parseJSON(response);

                            if (respuesta['resultado'] == "ok") {
                                //alert(respuesta['datos']);
                                if (respuesta['datos'].length == 0){
                                    alert('El viaje ya no existe o no se ha podido encontrar.');
                                } else {

                                    var codigo = respuesta['datos'][0][0];
                                    var denominacion = respuesta['datos'][0][1];
                                    var cdproy = respuesta['datos'][0][2];
                                    var deno_proy = respuesta['datos'][0][3];
                                    var wp = respuesta['datos'][0][4];
                                    var motivo = respuesta['datos'][0][5];
                                    var fecha_ini = Num_aFecha(respuesta['datos'][0][6]);
                                    var fecha_fin = Num_aFecha(respuesta['datos'][0][7]);
                                    var total = respuesta['datos'][0][8];
                                    var cdtb = respuesta['datos'][0][9];
                                    var deno_trab = respuesta['datos'][0][10];
                                    var estado = respuesta['datos'][0][11];
                                    var origen = respuesta['datos'][0][12];
                                    var destino = respuesta['datos'][0][13];
                                    var obs = respuesta['datos'][0][14];

                                    var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                                    var deno_trabajador_bus = document.getElementById('deno_trabajador_bus').value;
                                    var pal_filtro = "";


                                    location.href='viajes_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&codigo=' + codigo + '&denominacion=' + denominacion + '&cdproy=' + cdproy + '&deno_proy=' + deno_proy + '&wp=' + wp + '&motivo=' + motivo + '&fecha_ini=' + fecha_ini + '&fecha_fin=' + fecha_fin + '&total=' + total + '&cdtb=' + cdtb + '&deno_trab=' + deno_trab + '&estado=' + estado + '&origen=' + origen + '&destino=' + destino + '&obs=' + obs + '&pal_filtro=' + pal_filtro + '&ventana_alta=' + 0 + '&vent_origen=avisos_detalle.php';
                                        }

                            } else {
                                alert(respuesta['datos']);
                            }  

                        } catch (error) {
                            alert(error + "Resp. gsBase: " + response);
                        }
                        
                    },
                    error: function(xmlhttprequest, textstatus, message) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        //$("#overlay").fadeOut(300);

                        if (textstatus === "timeout") {
                            alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                        } else {
                            alert(textstatus);
                        }
                    }
                });
            }



            //NG20240709 LLAMADA A GSBASE PARA OBTENER DOCUMENTOS
            function a_devolver_documentos() {
                var arg = '{'
                           +'"cdtrabajador" : "' + cdtrabajador_login + '",'
                           +'"cdaplicacion" : "' + cdaplicacion + '",'
                           +'"cddocumento" : "' + cd_reg_aso + '"'//Que no se nos olvide quitar la coma solo en el ult.element
                           +'}';

                //alert(arg);

                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                var accion = "ejecutar_accion_gsb";
                var accion_gsb = "a_devolver_documentos";

                //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                //$("#overlay").fadeIn(300);

                $.ajax({
                    url: url_conexion,
                    //con esta url si llega a conectar con el php pero no llega a gsbase                    
                    //url: 'https://www.globalsystem.es/AppWeb/PortalEmpleado/funciones.php',
                    data: {"servidor_ip_publica": servidor_ip_publica, "puerto": puerto, "empresa_gestora": empresa_gestora, "aplicacion": aplicacion, "ejercicio": ejercicio, "empresa_id": empresa_id, "ventana_pref": ventana_pref, "arg": arg, "accion": accion, "accion_gsb": accion_gsb, "cd_pref_autogen": cd_pref_autogen, "historico_activo": historico_activo},
                    type: "POST",
                    //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                    timeout: 2000,
                    success: function(response) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        //$("#overlay").fadeOut(300);
                        //alert(response);

                        //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                        //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                        //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                        try {
                            var respuesta = $.parseJSON(response);

                            if (respuesta['resultado'] == "ok") {
                                //alert(respuesta['datos']);
                                if (respuesta['datos'].length == 0){
                                    alert('El documento ya no existe o no se ha podido encontrar.');
                                } else {
                                    //alert(respuesta['datos'][0][1]);
                                    //0002img_part1.jpg,img_part1.jpg,0002,JAVIER BUENO,global,9231,N,019,CONTRATO TRABAJADOR,1,0
                                    deno_documento = respuesta['datos'][0][1];
                                    autor = respuesta['datos'][0][4];
                                    fecha_doc = Num_aFecha(respuesta['datos'][0][5]);
                                    vigencia = respuesta['datos'][0][6];
                                    tipo_doc = respuesta['datos'][0][7];
                                    deno_tipo_doc = respuesta['datos'][0][8];
                                    est_firma_doc = devuelveEstadoFirma(respuesta['datos'][0][9], respuesta['datos'][0][10]);

                                    location.href='documentacion_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&cdDocumento=' + cd_reg_aso + '&deno_documento=' + deno_documento + '&cd_trabajador_sel=' + cdtrabajador_login + '&deno_trabajador_sel=' + deno_trabajador_sel + '&autor=' + autor + '&fecha=' + fecha_doc + '&vigencia=' + vigencia + '&tipo=' + tipo_doc + '&deno_tipo=' + deno_tipo_doc + '&est_firma=' + est_firma_doc + '&ventana_alta=' + 0 + '&vent_origen=avisos_detalle.php';
                                }

                            } else {
                                alert(respuesta['datos']);
                            }  

                        } catch (error) {
                            alert(error + "Resp. gsBase: " + response);
                        }
                        
                    },
                    error: function(xmlhttprequest, textstatus, message) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        //$("#overlay").fadeOut(300);

                        if (textstatus === "timeout") {
                            alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                        } else {
                            alert(textstatus);
                        }
                    }
                });
            }

            //https://examples.bootstrap-table.com/#view-source
            //NG20240723 Abrimos seleccion modal de obras
            function abrirModalSeleccionTrabajador(){               
                var src = "seleccion_trabajador_grupos.php?vent=fichaje_historico";
                src += "&time=" + new Date().getTime();
                document.getElementById('iframeSelTrab').src = src;
                //NG20240627 ABRIMOS MODAL SIN ABRIR EL TECLADO DE PRIMERAS.
                //$('#modalEscaneoCodigo2').modal({backdrop: 'static',keyboard: false})  
                //NG20240618 ABRIMOS EL MODAL DESDE JAVASCRIPT.
                $('#modalSeleccionTrabajador').modal('show');
            }

            //NG20240711 FUNCION UTILIZADA DESDE LOS MODALES/IFRAME PARA CERRAR EL MODAL Y RESETEAR LOS
            //VALORES DEL IFRAME
            function cerrarModalSeleccionTrabajador(){
                document.getElementById('iframeSelTrab').src = "";
                $('#modalSeleccionTrabajador').modal('hide');
            }

        </script>
    </head>
    <body id="page-top" onload="ComprobarConfiguracion()">        
        <!-- APP BAR-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#" onclick="Atras()"><i class="fa-solid fa-backward-step" style="font-size: 30px; color: #FFFFFF;"></i></a>
                <a class="navbar-brand" style="margin-left: 10%;">Detalle Avisos</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1" style="display: none;" id="img_modo_dios"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="" href="#" title="Modo Dios activado"><img src="assets/modo_dios2.png" width="30" height="30"></a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="Mostrar_acciones_info()" href="#">Info</a></li>
                        <!--<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contact</a></li>-->
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Portfolio Section-->
        <section class="page-section portfolio" id="portfolio">
            <div class="container d-flex align-items-center flex-column" >
                <div style="border-radius: 10px; border-color: #003061; border-style: solid; padding: 10px; margin-bottom: 10px; align-content: center; display: none;">

                    <div class="container d-flex align-items-center flex-column" >
                        <h4 id="nombre_trab_html" class="portfolio-modal-title text-secondary text-uppercase mb-0">Nombre trabajador</h4>
                        <p id="tipo_trab_html" style="color: #404040; font-weight: bold; font-size: 20px;margin-bottom: 0px;">Tipo de trabajador</p>
                        <hr style="color: #003061;border-top-width: 5px;width: 200px;opacity: 100;">
                        <p id="fecha_html" style="color: #3F51B5; font-weight: bold; font-size: 24px;margin-bottom: 0px;">Fecha</p>
                    </div>
                </div>

                <div class="container">
                    <div class="input-group mb-3">
                        <input class="form-control" id="cd_trabajador_bus" name="cd_trabajador_bus" type="text" placeholder="cd_trabajador_bus" style="max-width: 80px; display:none;" disabled> 
                        <div class="form-floating">                                   
                            <input class="form-control" id="deno_trabajador_bus" name="deno_trabajador_bus" type="text" placeholder="Trabajador" style="padding-left:8px" disabled>
                            <label for="deno_trabajador_bus">Selección trabajador</label>
                        </div>
                        <!-- Selección de trabajador -->
                        <button class="btn btn-outline-secondary" type="button" id="botonSelTrabajador" style="width: 80px; background: #003061" onclick="abrirModalSeleccionTrabajador()" title="Selección de trabajador"><i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i></button>
                    </div>
                </div>

                <div class="container">
                    <div class="input-group mb-3"> 
                        <div class="form-floating">                                   
                            <input class="form-control" id="remit" name="remit" type="text" placeholder="Remitente" style="padding-left:8px" disabled>
                            <label for="remit">Remitente</label>
                        </div>
                        <!-- Selección de Remitente -->
                        <button class="btn btn-outline-secondary" type="button" id="botonSelRemitente" style="width: 80px; background: #003061" onclick="abrirModalSeleccionTrabajador()" title="Selección de Remitente"><i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i></button>
                    </div>
                </div>

                <div class="container">
                    <div class="input-group mb-3">
                        <div class="form-floating">                                   
                            <input class="form-control" id="asunto" name="asunto" type="text" placeholder="Asunto" style="padding-left:8px" disabled>
                            <label for="asunto">Asunto</label>
                        </div>
                    </div>
                </div>

                <div class="container" style="margin-top:0px;">
                    <div class="input-group mb-3">
                        <div class="form-floating" style="margin-right: 10px;">                                   
                            <input class="form-control" id="fecha" name="fecha" type="date" placeholder="Fecha envío" style="padding-left:8px">
                            <label for="fecha">Fecha</label>
                        </div>
                        <div class="form-floating" style="margin-right: 10px;">                                   
                            <input class="form-control" id="hora" name="hora" type="time" placeholder="Hora Desde" style="padding-left:8px">
                            <label for="hora">Hora</label>
                        </div>
                    </div>                   
                </div>

                <div class="container" style="margin-top:0px;">
                    <div class="input-group mb-3">
                        <div class="form-floating">                                   
                            <textarea class="form-control" id="texto" name="texto" type="text" placeholder="texto" style="height: 168px;"></textarea>
                            <label for="texto">texto</label>
                        </div>
                    </div>                   
                </div>

                <div class="container d-flex align-items-center flex-column">                            
                    <!-- Si no ponemos el type="button" hace submit siempre-->
                    <button class="btn btn-global2" type="button" id="btn_ir_documentos" onclick="controlRelacionado()" style=" min-height: 70px; min-width: 170px; display:none;">Ver</button>
                </div>       
            </div>

            <!-- Modal seleccion trabajador-->
            <div class="modal fade" id="modalSeleccionTrabajador" tabindex="-1" aria-labelledby="modalSeleccionTrabajador" aria-hidden="true" height="500px" style="z-index: 2200;">
                <div class="modal-dialog modal-l">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalScrollableTitle">Selección de trabajador</h5>
                            <div class="modal-header border-0">
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        </div>                        
                        <iframe id="iframeSelTrab" src="" height="550px"></iframe>
                    </div>
                </div>
            </div>
        </section>


        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
