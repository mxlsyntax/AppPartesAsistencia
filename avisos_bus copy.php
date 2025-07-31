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

$cd_trabajador_bus_det = isset($_REQUEST['cd_trabajador_bus']) ? $_REQUEST['cd_trabajador_bus'] : "";
$deno_trabajador_bus_det = isset($_REQUEST['deno_trabajador_bus']) ? $_REQUEST['deno_trabajador_bus'] : "";
$fecha_ini_bus_det = isset($_REQUEST['fecha_ini_bus']) ? $_REQUEST['fecha_ini_bus'] : "";
$fecha_fin_bus_det = isset($_REQUEST['fecha_fin_bus']) ? $_REQUEST['fecha_fin_bus'] : "";

//echo "<script>";
//echo "alert('codigo: " . $cd_trabajador_bus_det . "');";
//echo "alert('nombre: " . $deno_trabajador_bus_det . "');";
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

        <!-- GUARDAMOS NUESTRO PROPIO HISTORIAL PARA GESTIONAR EL BOTON NEXT Y PREV DEL NAVEGADOR -->
        <script src="js/historial.js"></script>

        <script>
            const cdappfijo = "<?php echo $id_app_android; ?>";           


            //NG20241024 CONTROL DE HISTORIAL ETC, PARA CONTROLAR EL ATRAS DEL NAV.
            const miHistorial = new Historial(cdappfijo);
            miHistorial.push('documentacion_bus');
            //alert(miHistorial.mostrarHistorial());

            var hostname = window.location.hostname;          

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

            //var acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_devolver_documentos, a_devolver_grupo (iframe)";
            var acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_devolver_avisos, a_leer_trabajadores (en iframe)";

            //NG20240725 COMO EL ATRAS NO FUNCIONA PARA EL CD DEL TRABAJADOR, CUANDO VOLVEMOS DEL DETALLE
            //LO GESTIONAMOS MANUALMENTE.
            const cd_trabajador_bus_det = "<?php echo $cd_trabajador_bus_det; ?>";
            const deno_trabajador_bus_det = "<?php echo $deno_trabajador_bus_det; ?>";
            const fecha_ini_bus_det = "<?php echo $fecha_ini_bus_det; ?>";
            const fecha_fin_bus_det = "<?php echo $fecha_fin_bus_det; ?>";

            if (tipo_trab_login == 'G'){
                var cdresponsable = cdtrabajador_login;
            } else {
                var cdresponsable = ""; 
            }

            //alert(Fecha_aNum("24/07/2024"));
            //alert(Num_aFecha(9137));

            function GetVariableLocalStorage(nombre_variable){
                return localStorage.getItem(nombre_variable + "_" + cdappfijo);
            }

            function Mostrar_acciones_info(){
                alert(acciones_info);
            }

            function Atras(){
                //habra que cambiar para cerrar sesion y esas cosas
                //if(confirm("Seguro que desea cerrar la aplicación")){
                //    location.href='exit.php?id=3';
                //}
                location.href='main.php';
                //window.location.href = 'main.php';
            }

            //Este metodo hay que implementarlo en las ventanas en las que llamemos a gsBase
            //NG20240702 comprobamos los datos guardados en el localstorage en el lado del cliente
            //NG20240702 comprobamos los datos guardados en el localstorage en el lado del cliente
            //ingresa los datos en localstorage si no lo están ya, para siguientes ventanas.
            //Este metodo hay que implementarlo en las ventanas en las que llamemos a gsBase
            function ComprobarConfiguracion(){
                //deshabilitaRetroceso();

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
                    document.getElementById('botonSelTrabajador').disabled = true;
                } else {
                    nom_tipo_trab = "Usuario Normal";
                    document.getElementById('botonSelTrabajador').disabled = true;
                }
                
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;                
                document.getElementById('fecha_html').innerText = new Date(Date.now()).toLocaleDateString();
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;

                if (modo_dios == 1){
                    document.getElementById('img_modo_dios').style.display = ''; 
                    document.getElementById('btn_sup_avisos_ant').style.display = ''; 
                }
            }

            //NG20240723 Abrimos seleccion modal de obras
            function abrirModalSeleccionTrabajador(){               
                var src = "seleccion_trabajador.php?vent=fichaje_historico";
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
                //NG20240724 HAY QUE REFRESCAR, SI NO NO CARGAN LOS DATOS NUEVOS.
                $table.bootstrapTable('refresh');
            }
            function MostrarInfoLeyenda(){
                $('#modalInfoLeyenda').modal('show');
                //alert("Arrascate los huevos");
            }
        </script>
    </head>
    <body id="page-top" onload="ComprobarConfiguracion()">        
        <!-- APP BAR-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#" onclick="Atras()"><i class="fa-solid fa-backward-step" style="font-size: 30px; color: #FFFFFF;"></i></a>
                <a class="navbar-brand" style="margin-left: 10%;" id="tit_vent">Avisos</a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1" style="display: none;" id="img_modo_dios"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="" href="#" title="Modo Dios activado"><img src="assets/modo_dios2.png" width="30" height="30"></a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="Mostrar_acciones_info()" href="#">Info</a></li>
                        <li class="nav-item mx-0 mx-lg-1" style="display: none;" id="btn_sup_avisos_ant"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="Confirm_eliminar_antiguos()" href="#" title="(Vis en ModoDios) Elimina avisos antigüos leidos con mas de 15 días y no leidos de mas de 30 días." style="color: #ffc107;">Sup. Antigüos</a></li>
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

                <div class="container" style="margin-top:0px;">
                    <div class="input-group mb-3">
                        <div class="form-floating" style="margin-right: 10px;">                                   
                            <input class="form-control" id="fecha_ini_bus" name="fecha_ini_bus" type="date" placeholder="Fch. Inicio" style="padding-left:8px" onchange="$table.bootstrapTable('refresh');">
                            <label for="fecha_ini_bus">Fch. Inicio</label>
                        </div>
                        <div class="form-floating" style="margin-left: 10px;">                                   
                            <input class="form-control" id="fecha_fin_bus" name="fecha_fin_bus" type="date" placeholder="Fch. Fin" style="padding-left:8px" onchange="$table.bootstrapTable('refresh');">
                            <label for="fecha_fin_bus">Fch. Fin</label>
                        </div>
                    </div>
                </div>

                <div class="row container-fluid d-flex flex-nowrap" style="margin-top:0px">
                    <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" style="display: flex; justify-content: center; align-items: center;">
                        <!-- Si no ponemos el type="button" hace submit siempre-->
                        <button class="btn btn-global2" type="button" onclick="$table.bootstrapTable('refresh');" style=" min-height: 40px; min-width: 140px;">Buscar</button>
                        <svg onclick="MostrarInfoLeyenda()" style="margin-left:5px;width:24px;height:24px;vertical-align:middle;" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"></path></svg>

                    </div>
                </div>

                <div class="row container-fluid d-flex flex-nowrap" style="margin-top:5px">
                    <div class="col-md-6 col-xs-6 col-sm-6 d-flex align-items-center justify-content-center">
                        <div class="form-floating">                            
                            <div class="form-check" title="Ver los avisos Leidos también">
                                <input class="granCheckbox" type="checkbox" value="Seleccionar todos" id="cbxSelAll2" onchange="MarDesVisibilidad(this)"/>
                                <label class="form-check-label" for="cbxSelAll2" style="margin-left:8px; font-weight:bold; font-size: 18px;">
                                 Ver.Todos
                                </label>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-6 col-xs-6 col-sm-6 d-flex align-items-center justify-content-center">
                        <div class="form-floating">
                            <div class="form-check"  title="Seleccionar todos los avisos del listado">
                                <input class="granCheckbox" type="checkbox" value="Seleccionar todos" id="cbxSelAll" onchange="SelDesTodos(this)"/>
                                <label class="form-check-label" for="cbxSelAll" style="margin-left:8px;font-weight:bold; font-size: 18px;">
                                 Sel.Todos
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container"  style="margin:0px; padding:3px;">
                    <!-- TABLA CON INICIO SOLO SI PINCHAMOS EN BUSCAR (COMENTADO EN EL METODO AJAX DE ABAJO)-->
                    <table id="tableHistorico" class="table table-striped" 
                        data-toggle="table"
                        data-toolbar="#toolbar" 
                        data-locale="es-ES" 
                        data-search="true" 
                        data-show-refresh="false"
                        data-show-toggle="false"
                        data-show-fullscreen="false"
                        data-ajax="a_devolver_avisos"
                        data-mobile-responsive="true" 
                        data-auto-refresh="false"
                        data-show-export="true"
                        data-click-to-select="true"
                        data-show-footer="false"
                        data-id-field="cdaviso">
                        <thead>
                            <tr>
                                <th data-field="state" data-checkbox="true"></th>
                                <th data-field="cdaviso" data-sortable="true" style="display:none">cdaviso</th>
                                <th data-field="cdTrabajador" data-sortable="true">cdTrabajador</th>
                                <th data-field="denoTrab" data-sortable="true">Trabajador</th>
                                <th data-field="asunto" data-sortable="true" data-formatter="cellStyleGeneral">Asunto</th>
                                <th data-field="remit" data-sortable="false" data-visible="false" >Remitente</th>
                                <th data-field="fecha" data-sortable="true">Fecha</th>
                                <th data-field="hora" data-sortable="true">Hora</th>
                                <th data-field="vent" data-sortable="true">Ventana</th>
                                <th data-field="cd_reg_aso" data-sortable="true">cd_reg_aso</th>
                                <!-- data-card-visible="false" con esta opcion indicamos que no queremos que se muestre en la version movil -->
                                <th data-field="texto" data-sortable="true" data-formatter="cellStyleGeneral" data-card-visible="false">Texto</th>
                                <th data-field="est_lei" data-sortable="true" data-formatter="cellStyleEstado">Estado</th>
                                <th data-field="ejercicio_cl" data-sortable="false" data-visible="false" data-formatter="cellStyleEstado">Ejercicio</th>
                            </tr>
                        </thead>
                    </table>
                    <script>
                        //ESTABLECEMOS ESTE CODIGO JAVASCRIPT DESPUES DE LA TABLA HTML PORQUE 
                        //DE LO CONTRARIO NO SE EJECUTA BIEN.

                        //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                        var $table = $('#tableHistorico');

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

                        //alert("cdresponsable: " + cdresponsable); 
                        var tabla_datos = [];

                        var mostrar_todos = 0;

                        document.getElementById('fecha_ini_bus').value = formatDate(new Date(Date.now() - 30 * 86400000)); 
                        document.getElementById('fecha_fin_bus').value = formatDate(new Date(Date.now() + 7 * 86400000));

                        //NG20240725 INICIALIZAMOS AQUI LOS CAMPOS PORQUE SI NO PARA LA CONSULTA NO TENEMOS ACCESO A
                        //ELLOS SI LO HACEMOS EN LOS SCRIPTS DE ARRIBA, PARECE SER QUE ESTE CODIGO SE EJECUTA ANTES QUE EL
                        //DE ARRIBA.
                        //document.getElementById('fecha_ini_bus').value = formatDate(new Date(Date.now()));
                        //new Date(Date.now()).toLocaleDateString().split('/').reverse().join('-');
                        //document.getElementById('fecha_fin_bus').value = formatDate(new Date(Date.now()));

                        if (tipo_trab_login != 'G'){
                            //NG20240725 COMO NO ES JEFE DE GRUPO ESTABLECEMOS POR DEFECTO SU TRABAJADOR PARA LA BUSQUEDA                   
                            document.getElementById('cd_trabajador_bus').value = cdtrabajador_login;
                            document.getElementById('deno_trabajador_bus').value = nombre_trab_login;
                        }

                        //NG20240725 SI TENEMOS ESTA VARIABLE RELLENA, SIGNIFICA QUE HEMOS ESTADO EN EL DETALLE
                        //Y HEMOS VUELTO DE EL, PARA NO PERDER LA BUSQUEDA QUE TUVIESEMOS.
                        if (cd_trabajador_bus_det != ""){
                            document.getElementById('cd_trabajador_bus').value = cd_trabajador_bus_det;
                            document.getElementById('deno_trabajador_bus').value = deno_trabajador_bus_det;
                        }

                        if (fecha_ini_bus_det != ""){
                            document.getElementById('fecha_ini_bus').value = fecha_ini_bus_det.split('/').reverse().join('-');
                        }


                        if (fecha_fin_bus_det != ""){
                            document.getElementById('fecha_fin_bus').value = fecha_fin_bus_det.split('/').reverse().join('-');
                        }

                        function Confirm_eliminar_antiguos(){
                            if (confirm("Se borrarán los avisos leidos con mas de 15 días y los avisos sin leer con mas de 30 días ¿Desea proseguir?")){
                                //procedemos a la llamada a gsb
                                Proc_elim_antiguos();
                            } else {
                                alert("Proceso cancelado");
                            }
                        }

                        function Proc_elim_antiguos(){
                            //Obtenemos los seleccionados.
                                var lista_avi = construirListaAvisos();
                                //alert("Avisos seleccionados: " + construirListaAvisos);
                                var arg = '{'
                                           +'"cdtrabajador" : "' + cdtrabajador_login + '",'
                                           +'"cdaplicacion" : "' + cdaplicacion + '"'//NG20250421 LOS ARRAYS VAN SIN COMILLAS DOBLES ACOTANDOLOS
                                           +'}';
                                    
                                //alert(url_conexion);

                                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                                //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                                var accion = "ejecutar_accion_gsb";
                                var accion_gsb = "a_eliminar_avisos_ant";
                                //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + cdtrabajador_login + ". ");

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
                                                alert(respuesta['datos']);
                                                $table.bootstrapTable('refresh');
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
                                     }}); 
                        }

                        function MarDesVisibilidad(element){
                            //element.checked = !element.checked;
                            if (element.checked){
                                mostrar_todos = 1;
                            } else {                    
                                mostrar_todos = 0;
                            }
                        }

                        function Alta_solicitud(){
                            var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                            var deno_trabajador_bus = document.getElementById('deno_trabajador_bus').value;
                            var fecha_ini_bus = document.getElementById('fecha_ini_bus').value;
                            var fecha_fin_bus = document.getElementById('fecha_fin_bus').value;

                            location.href='fichaje_ausencias_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&ventana_alta=' + 1;

                        }

                        function SelDesTodos(element){
                            //element.checked = !element.checked;
                            if (element.checked){
                                $table.bootstrapTable('checkAll');
                            } else {                    
                                $table.bootstrapTable('uncheckAll')
                            }
                        }

                        //NG20250421 FUNCION QUE DEVUELVE LOS AVISOS SELECCIONADOS TAL COMO ESPERA RECIBIRLOS GSBASE.
                        function construirListaAvisos(){
                            jsonObject = $table.bootstrapTable('getSelections');
                            
                            avisos_sel = [];
                            for (let i in jsonObject) {
                                var item = jsonObject[i];

                                avisos_sel.push(item["cdaviso"]);
                            }

                            console.log("Preparamos el listado de avisos seleccionados como lo necesita gsbase: " + JSON.stringify(avisos_sel));
                            //console.log("A pelo: " + JSON.stringify(jsonObject));

                            return JSON.stringify(avisos_sel);
                        }


                        function ValidarRevisionMultiple(){
                            valido = false;
                            jsonObject = $table.bootstrapTable('getSelections');
                            //alert("El tamaño de los avisos seleccionados es: " + jsonObject.length);
                            if (jsonObject.length > 0){
                                valido = true;
                            }

                            return valido;
                        }



                        function Revisar_multiples(){
                            var respuesta = "";

                            if (ValidarRevisionMultiple()){
                                //Obtenemos los seleccionados.
                                var lista_avi = construirListaAvisos();
                                //alert("Avisos seleccionados: " + construirListaAvisos);
                                var arg = '{'
                                           +'"cdtrabajador" : "' + cdtrabajador_login + '",'
                                           +'"cdaplicacion" : "' + cdaplicacion + '",'
                                           +'"cdaviso" : "",'
                                           +'"lista_avi" : ' + lista_avi + '' //NG20250421 LOS ARRAYS VAN SIN COMILLAS DOBLES ACOTANDOLOS
                                           +'}';
                                    
                                //alert(url_conexion);

                                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                                //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                                var accion = "ejecutar_accion_gsb";
                                var accion_gsb = "a_revisar_aviso";
                                //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + cdtrabajador_login + ". ");

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
                                                alert(respuesta['datos']);
                                                $table.bootstrapTable('refresh');
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
                                     }});   
                            } else {
                                alert("¡¡¡Por favor seleccione al menos un aviso para poder revisarlo!!!");
                            }
                                        
                        }


                        function validarBusqueda(){
                            validado = false;

                            var fecha_ini = Fecha_aNum(format_fecha_local(document.getElementById('fecha_ini_bus').value));
                            var fecha_fin = Fecha_aNum(format_fecha_local(document.getElementById('fecha_fin_bus').value));

                            if (fecha_ini == 0 & fecha_fin == 0){
                                alert("Por favor rellene al menos una fecha");
                            } else if (fecha_ini > fecha_fin & fecha_fin > 0){                                
                                $table.bootstrapTable('hideLoading');
                                alert("La fecha de inicio no puede ser mayor que la de fin");
                            } else {
                                validado = true;
                            }

                            return validado;

                        }


                        //https://examples.bootstrap-table.com/#view-source
                        //NG20240709 LLAMADA A GSBASE PARA OBTENER TRABAJADORES
                        function a_devolver_avisos(params) {
                            tabla_datos = [];
                            var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                            //alert("Trabajador bus" + cd_trabajador_bus);
                            //Tiramos de utilidades.js para no engrosar el codigo aqui.
                            var fecha_ini = Fecha_aNum(format_fecha_local(document.getElementById('fecha_ini_bus').value));
                            var fecha_fin = Fecha_aNum(format_fecha_local(document.getElementById('fecha_fin_bus').value));

                            //alert("mostrar_todos = " + mostrar_todos);

                            if (validarBusqueda()){
                                if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                                    alert("Faltan valores de conexión, revise los parametros");
                                } else {

                                    var arg = '{'
                                       +'"cdtrabajador" : "' + cd_trabajador_bus + '",'
                                       +'"cdresponsable" : "' + cdresponsable + '",'
                                       +'"fechaDesde" : ' + fecha_ini + ','
                                       +'"fechaHasta" : ' + fecha_fin + ','
                                       +'"todos" : ' + mostrar_todos //Que no se nos olvide quitar la coma solo en el ult.element
                                       +'}';

                                    //alert(arg);

                                    //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                                    var accion = "ejecutar_accion_gsb";
                                    var accion_gsb = "a_devolver_avisos";

                                    var num_avisos = 0;

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
                                                    //alert(respuesta['datos'].length);

                                                    //https://stackoverflow.com/questions/37814493/how-to-load-json-data-into-bootstrap-table
                                                    //Ahora tendriamos que cargar los elementos a la tabla.
                                                    for (var i = 0; i < respuesta['datos'].length; i++) {
                                                        //alert(respuesta['datos'][i][0]);

                                                        tabla_datos.push({
                                                            cdaviso: respuesta['datos'][i][0],
                                                            cdTrabajador: respuesta['datos'][i][1],
                                                            denoTrab: respuesta['datos'][i][2],
                                                            remit: respuesta['datos'][i][3],
                                                            fecha: Num_aFecha(respuesta['datos'][i][4]),
                                                            hora: respuesta['datos'][i][5],
                                                            vent: respuesta['datos'][i][6],
                                                            cd_reg_aso: respuesta['datos'][i][7],
                                                            asunto: respuesta['datos'][i][8],
                                                            texto: respuesta['datos'][i][9],
                                                            est_lei: respuesta['datos'][i][10],
                                                            ejercicio_cl: respuesta['datos'][i][11]
                                                        })
                                                    }

                                                    //NG20240710 CON ESTE COMANDO METEMOS LOS DATOS EN LA TABLA YA INICIALIZADA.
                                                    //NG20240726 HAY DOS FORMAS DE INICIAR LA TABLA. QUITANDO data-ajax="a_devolver_avisos" DEL HTML, COMENTANDO EL PARAM.SUCCESS Y PONIENDO LAS DOS SIGUIENTES LINEAS REMOVEALL Y APPEND LA TABLA NO SE RELLENARA HASTA QUE NO LE DEMOS A BUSCAR.
                                                    $table.bootstrapTable('removeAll');
                                                    $table.bootstrapTable('append',tabla_datos);
                                                    params.success(tabla_datos);

                                                    num_avisos = respuesta['datos'].length;
                                                    document.getElementById('tit_vent').innerText = "Avisos (" + num_avisos + ")";

                                                    document.getElementById('tableHistorico').style.display = '';
                                                } else {
                                                    alert(respuesta['datos']);
                                                    document.getElementById('tableHistorico').style.display='none';
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

                                            document.getElementById('tableHistorico').style.display='none';
                                        }

                                    });

                                }
                            }
                        }

                        //DOCUMENTACION DE LAS SIGUIENTES TABLAS DE BOOTSTRAP EN EL SIGUIENTE ENLACE
                        //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                        $table.bootstrapTable({
                          data: [],
                          striped : true,
                          iconSize: "lg",
                          //hay que meter el tipo pdf, por defecto no sale, mas importar en la 
                          //cabecera los elementos correspondientes.
                          exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf']
                        });

                        //DOCUMENTACION DE LAS SIGUIENTES TABLAS DE BOOTSTRAP EN EL SIGUIENTE ENLACE
                        //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                        $(function() {
                            //OCULTACION DE CAMPOS
                            $table.bootstrapTable('hideColumn', 'cdaviso');
                            $table.bootstrapTable('hideColumn', 'cdTrabajador');
                            $table.bootstrapTable('hideColumn', 'denoTrab');
                            $table.bootstrapTable('hideColumn', 'vent');
                            $table.bootstrapTable('hideColumn', 'cd_reg_aso');

                            //Para listados que no cojen en pantalla de movil, lo ponemos por defecto
                            //toggleView y se ven mucho mejor
                            //CON EL PLUGIN PARA TABLAS MOBIL DE BOOTSTR.. LO HACE AUTOMATICAMENTE
                            //SI NOTA QUE EN EL DISPOSITIVO NO TIENE TAMAÑO PARA MOSTRAR TODAS LAS COLUMNAS.
                            //$table.bootstrapTable('toggleView');

                        });

                        //NG20240711 OBTENEMOS LA FILA QUE HAN SELECCIONADO
                        $('#tableHistorico').on('click-row.bs.table', function (row, $element, field) {
                            var fila = $element;

                            //var id_elemento = fila['id'];
                            //alert(fila['id']);
                            var cdaviso = fila['cdaviso'];
                            var cd_trabajador_sel = fila['cdTrabajador'];
                            var deno_trabajador_sel = fila['denoTrab'];
                            var fecha = fila['fecha'];
                            var hora = fila['hora'];
                            var remit = fila['remit'];
                            var asunto = fila['asunto'];
                            var vent = fila['vent'];
                            var cd_reg_aso = fila['cd_reg_aso'];
                            var texto = fila['texto'];
                            var est_lei = fila['est_lei'];
                            var ejercicio_cl = fila['ejercicio_cl'];

                            var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                            var deno_trabajador_bus = document.getElementById('deno_trabajador_bus').value;
                            var fecha_ini_bus = document.getElementById('fecha_ini_bus').value;
                            var fecha_fin_bus = document.getElementById('fecha_fin_bus').value;

                            //alert(texto);

                            location.href='avisos_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + "" + '&fecha_fin_bus=' + "" + '&cdaviso=' + cdaviso + '&cd_trabajador_sel=' + cd_trabajador_sel + '&deno_trabajador_sel=' + deno_trabajador_sel + '&fecha=' + fecha + '&hora=' + hora + '&remit=' + remit + '&asunto=' + asunto + '&vent=' + vent + '&cd_reg_aso=' + cd_reg_aso + '&texto=' + texto + '&est_lei=' + est_lei + '&ejercicio_cl=' + ejercicio_cl + '&ventana_alta=' + 0;


                            //window.parent.document.getElementById("cd_trabajador_bus").value = id_elemento; 
                            //window.parent.document.getElementById("deno_trabajador_bus").value = deno_elemento;
                            //window.parent.cerrarModalSeleccionTrabajador();

                        })

                        function cellStyleGeneral(value, row, index) {
                            var color_campo = "#003061";
                            //Podriamos poner iconos o botones si quisieramos.
                            //'<div style="color: ' + color_campo + '; font-weight:bold;">' + '<i class="fa fa-dollar-sign"></i>' + value +'</div>'
                            var style = '';
                            if (value != ""){
                               style = '<div style="color: ' + color_campo + '; font-weight:bold;">' + value +'</div>'; 
                            }

                            return style;
                        }

                        function cellStyleEstado(value, row, index) {
                            var color_campo = "";
                            //alert(row['est_lei']);

                            if (value == "Nuevo") {
                                color_campo = '#0EAC1E';
                            } else if (value == "Leído"){
                                color_campo = '#003061';
                            } else if (value == "Resuelto"){
                                color_campo = '#B31414';
                            } else {                                                             
                                color_campo = '#636363';  
                            }

                            //Podriamos poner iconos o botones si quisieramos.
                            //'<div style="color: ' + color_campo + '; font-weight:bold;">' + '<i class="fa fa-dollar-sign"></i>' + value +'</div>'

                            return '<div style="color: ' + color_campo + '; font-weight:bold; font-size:18px">' + value +'</div>';
                        }
                    </script>
                </div>          
            </div>

            <!-- Listado Historico -->
            <section class="container" id="demo-content" style="margin-top:20px;">
                <div class="col-xl-12" align="center">
                    
                </div>
            </section>

            <!-- Modal Crear Pass-->
            <div class="modal fade" id="modalInfoLeyenda" tabindex="-1" aria-labelledby="modalInfoLeyenda" aria-hidden="true" style="z-index: 2000;">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCodigoPref">Funcionamiento ventana</h5>
                            <div class="modal-header border-0">
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        </div>
                        <div class="modal-body text-center pb-5">
                            <div class="container">
                                <div class="row justify-content-center">

                                    <div class="col-xl-12">
                                        <!-- Masthead Avatar Image-->
                                        <img src="assets/images/info_leyenda1.jpg" style="border-radius: 10px; border-color: #003061; border-style: solid; margin-bottom:10px; border-width: 2px;"/>
                                        <p>En la ventana de búsqueda de viajes, por defecto aparecerán todos independientemente del estado.</p>
                                        <p>(1) Si pulsamos sobre el botón azul con la flecha donde pone Selección trabajador, limitaremos la búsqueda a los viajes de un trabajador.</p>
                                        <p>(2) Si pulsamos sobre los iconos podremos acotar la búsqueda por fecha de inicio y fecha de fin, pudiendo seleccionar el rango de fechas que queramos.</p>
                                        <p>(3) Con el botón Buscar, realizaremos la búsqueda de viajes según los criterios establecidos.
                                            Si pulsamos sobre el icono de información, se mostrará esta ventana de ayuda.</p>
                                        <br>
                                        <br>


                                        <img src="assets/images/info_leyenda2.jpg" style="border-radius: 10px; border-color: #003061; border-style: solid; margin-bottom:10px; border-width: 2px;"/>
                                        <p>(4) Si pulsamos sobre el botón Filtrar, se mostrará un desplegable con los estados de los viajes, pudiendo seleccionar entre Iniciados, Finalizados o Todos.<br>
                                        <br>
                                        <br>


                                        <img src="assets/images/info_leyenda3.jpg" style="border-radius: 10px; border-color: #003061; border-style: solid; margin-bottom:10px; border-width: 2px;"/>
                                        <p> (5) Si pulsamos sobre el botón recargará la página</p>
                                        <p>    (6) Si pulsamos cambiará a vista vertical</p>
                                        <p>    (7) Si pulsamos cambiará a pantalla completa</p>
                                        <p>    (8) Nos permitirá seleccionar para mostrar/ocultar columnas</p>
                                        <p>    (9) Si pulsamos nos permitirá exportar la tabla a diferentes formatos</p>
                                        <p>    (10) Si escribimos comenzará a automáticamente a filtrar según nuestra búsqueda
                                        </p>
                                        <br>
                                        <br>

                                        <div class="container d-flex align-items-center flex-column in-line"> 

                                            <!-- Si no ponemos el type="button" hace submit siempre-->
                                            <button type="button" class="btn btn-primary btn-xl" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                        <br>
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
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

        <a href="#" class="float"  onclick="Alta_solicitud()" style="display: none;">
            <i class="fa fa-plus my-float"></i>
        </a>

        <a href="#" class="float_alta2"  onclick="Revisar_multiples()" style="display:; background-color: rgb(255, 0, 0);" title="Revisar todos los seleccionados para que no aparezcan mas">Revisar Sel.</a>


        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
