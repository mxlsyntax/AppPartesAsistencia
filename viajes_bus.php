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

//NG20250225 PARA GESTIONAR EL COMBOBOX DEL FILTRO DE FORMATO
$pal_filtro_det = isset($_REQUEST['pal_filtro']) ? $_REQUEST['pal_filtro'] : "todos";

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
        <meta name="description" content="Aplicación Web para registrar dietas en viajes" />
        <meta name="author" content="NG" />

        <!-- Para acceso directo-->
        <meta name="apple-mobile-web-app-title" content="Viajes y Dietas">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="shortcut icon" sizes="16x16" href="/assets/favicon-16x16.png">
        <link rel="shortcut icon" sizes="192x192" href="/assets/icons/icon-192x192.png">
        <link rel="apple-touch-icon-precomposed" href="/assets/mstile-150x150.png">


        <title>Viajes y Dietas</title>
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
            var acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_devolver_viajes, a_leer_trabajadores (en iframe)";

            //NG20240725 COMO EL ATRAS NO FUNCIONA PARA EL CD DEL TRABAJADOR, CUANDO VOLVEMOS DEL DETALLE
            //LO GESTIONAMOS MANUALMENTE.
            const cd_trabajador_bus_det = "<?php echo $cd_trabajador_bus_det; ?>";
            const deno_trabajador_bus_det = "<?php echo $deno_trabajador_bus_det; ?>";
            const fecha_ini_bus_det = "<?php echo $fecha_ini_bus_det; ?>";
            const fecha_fin_bus_det = "<?php echo $fecha_fin_bus_det; ?>";

            //NG20250225 GESTION COMBOBOX FILTRO FORMATO
            var pal_filtro_det = "<?php echo $pal_filtro_det; ?>";
            //NG20250225 COMO LA PRIMERA BUSQUEDA SE EJECUTA ANTES QUE EL ONLOAD, DEFINIMOS LA PRIMERA VEZ
            //PARA COGER LO QUE VENGA DE PHP O OBTENER EL VALOR DEL COMPONENTE DEL SELEC DE HTML
            var primera_vez = 0;

            if (tipo_trab_login == 'A'){
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
                    document.getElementById('botonSelTrabajador').disabled = false;
                    document.getElementById('botonEliminarTrabajador').disabled = false;
                } else {
                    nom_tipo_trab = "Usuario Normal";
                    document.getElementById('botonSelTrabajador').disabled = true;
                    document.getElementById('botonEliminarTrabajador').disabled = true;
                }
                
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;                
                document.getElementById('fecha_html').innerText = new Date(Date.now()).toLocaleDateString();
                document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;


                //NG20250225 GESTION DEL COMBOBOX PARA EL FILTRO
                document.getElementById("select").value = pal_filtro_det;

                if (modo_dios == 1){
                    document.getElementById('img_modo_dios').style.display = ''; 
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
                <a class="navbar-brand" style="margin-left: 10%;" id="tit_vent">Viajes</a>
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
                        <button class="btn btn-outline-secondary" type="button" id="botonEliminarTrabajador" style="width: 80px; background:rgb(222, 9, 9);" onclick="eliminarTrabajadorSeleccionado()" title="Añadir trabajador"><i class="fa-solid fa-xmark" style="font-size: 2em; color: #FFFFFF;"></i></button>

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

                <style>
                #select {
                    width: 100px;
                    display: inline-block;
                }
                </style>

                <div class="container-xxxl">
                    <!-- TABLA CON INICIO SOLO SI PINCHAMOS EN BUSCAR (COMENTADO EN EL METODO AJAX DE ABAJO)-->
                    <!-- https://examples.bootstrap-table.com/index.html#welcome.html#view-source -->
                    <!-- Usar data-card-visible="false" para las que queremos que se muestren
                    en modo movil -->
                    <div id="toolbar">
                        <select id="select" class="form-control" name="filterAlgorithm">
                            <option value="iniciados" selected>Iniciados</option>
                            <option value="finalizados">Finalizados</option>
                            <option value="todos">TODOS</option>
                        </select>
                        <button class="btn btn-secondary" id="filterBy">Filtrar</button>
                    </div>
                    <table id="tableHistorico" class="table table-striped" 
                        data-toggle="table"
                        data-toolbar="#toolbar" 
                        data-locale="es-ES" 
                        data-search="true" 
                        data-show-refresh="true"
                        data-show-toggle="false"
                        data-show-fullscreen="true"
                        data-ajax="a_devolver_viajes"
                        data-mobile-responsive="true" 
                        data-auto-refresh="false"
                        data-show-export="true"
                        data-visible-search="true"
                        data-click-to-select="true"
                        data-show-footer="false"
                        data-height="760"
                        data-id-field="codigo"
                        data-fixed-scroll="true"
                        data-show-columns="true"
                        data-show-columns-toggle-all="true">
                        <thead>
                            <tr>
                                <th data-field="codigo" data-sortable="true" data-formatter="cellStyleEstado">Código</th>
                                <th data-field="denominacion" data-sortable="true" data-formatter="cellStyleEstado">Denominación</th>
                                <th data-field="origen" data-sortable="true" data-visible="false">origen</th>
                                <th data-field="destino" data-sortable="true" data-visible="false">destino</th>
                                <th data-field="cdproy" data-sortable="true" data-visible="false">cdproy</th>
                                <th data-field="deno_proy" data-sortable="true" data-formatter="cellStyleGeneral">Proyecto</th>
                                <th data-field="wp" data-sortable="false" data-formatter="cellStyleGeneral">WP</th>
                                <th data-field="motivo" data-sortable="true" data-formatter="cellStyleGeneral">Motivo</th>
                                <th data-field="fecha_ini" data-sortable="true" data-formatter="cellStyleGeneral">Fch.Ini</th>
                                <th data-field="fecha_fin" data-sortable="true" data-formatter="cellStyleGeneral">Fch.Fin</th>
                                <th data-field="cdtb" data-sortable="true" data-formatter="cellStyleGeneral">cdtb</th>
                                <th data-field="deno_trab" data-sortable="true" data-formatter="cellStyleGeneral">Trabajador</th>
                                <!-- data-card-visible="false" con esta opcion indicamos que no queremos que se muestre en la version movil-->
                                <!-- data-visible="false" con esta opcion indicamos que no queremos que se muestre-->
                                <th data-field="estado" data-sortable="true" data-visible="false" data-formatter="cellStyleEstado">Estado</th>
                                <th data-field="estado_viaje" data-sortable="true" data-formatter="cellStyleEstado">Estado Viaje</th>
                                <th data-field="total" data-sortable="true" data-formatter="cellStyleEstado">Total</th>                                
                                <th data-field="obs" data-sortable="true" data-visible="false">Observaciones</th>
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

                        document.getElementById('fecha_ini_bus').value = formatDate(new Date(Date.now() - 30 * 86400000)); 
                        document.getElementById('fecha_fin_bus').value = formatDate(new Date(Date.now() + 7 * 86400000));

                        //NG20240725 INICIALIZAMOS AQUI LOS CAMPOS PORQUE SI NO PARA LA CONSULTA NO TENEMOS ACCESO A
                        //ELLOS SI LO HACEMOS EN LOS SCRIPTS DE ARRIBA, PARECE SER QUE ESTE CODIGO SE EJECUTA ANTES QUE EL
                        //DE ARRIBA.
                        //document.getElementById('fecha_ini_bus').value = formatDate(new Date(Date.now()));
                        //new Date(Date.now()).toLocaleDateString().split('/').reverse().join('-');
                        //document.getElementById('fecha_fin_bus').value = formatDate(new Date(Date.now()));

                            //NG20240725 COMO NO ES JEFE DE GRUPO ESTABLECEMOS POR DEFECTO SU TRABAJADOR PARA LA BUSQUEDA                   
                            document.getElementById('cd_trabajador_bus').value = cdtrabajador_login;
                            document.getElementById('deno_trabajador_bus').value = nombre_trab_login;
                        

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
                        

                        function Alta_solicitud(){
                            var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                            var deno_trabajador_bus = document.getElementById('deno_trabajador_bus').value;
                            var fecha_ini_bus = document.getElementById('fecha_ini_bus').value;
                            var fecha_fin_bus = document.getElementById('fecha_fin_bus').value;

                            var pal_filtro = $('[name="filterAlgorithm"]').val();

                            location.href='viajes_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&pal_filtro=' + pal_filtro + '&ventana_alta=' + 1;

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
                        function a_devolver_viajes(params) {
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
                                       +'"fechaHasta" : ' + fecha_fin + ''
                                       +'}';

                                    //alert(arg);

                                    //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                                    var accion = "ejecutar_accion_gsb";
                                    var accion_gsb = "a_devolver_viajes";

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
                                                        estado_viaje = "Sin estado";
                                                        if (respuesta['datos'][i][11] == 'I'){
                                                            estado_viaje = "Iniciado";
                                                        } else if (respuesta['datos'][i][11] == 'F'){
                                                            estado_viaje = "Finalizado";
                                                        }
                                                        tabla_datos.push({
                                                            codigo: respuesta['datos'][i][0],
                                                            denominacion: respuesta['datos'][i][1],
                                                            cdproy: respuesta['datos'][i][2],
                                                            deno_proy: respuesta['datos'][i][3],
                                                            wp: respuesta['datos'][i][4],
                                                            motivo: respuesta['datos'][i][5],
                                                            fecha_ini: Num_aFecha(respuesta['datos'][i][6]),
                                                            fecha_fin: Num_aFecha(respuesta['datos'][i][7]),
                                                            total: respuesta['datos'][i][8],
                                                            cdtb: respuesta['datos'][i][9],
                                                            deno_trab: respuesta['datos'][i][10],
                                                            estado: respuesta['datos'][i][11],
                                                            estado_viaje: estado_viaje,
                                                            origen: respuesta['datos'][i][12],
                                                            destino: respuesta['datos'][i][13],
                                                            obs: respuesta['datos'][i][14]
                                                        })
                                                    }

                                                    //NG20240710 CON ESTE COMANDO METEMOS LOS DATOS EN LA TABLA YA INICIALIZADA.
                                                    //NG20240726 HAY DOS FORMAS DE INICIAR LA TABLA. QUITANDO data-ajax="a_devolver_viajes" DEL HTML, COMENTANDO EL PARAM.SUCCESS Y PONIENDO LAS DOS SIGUIENTES LINEAS REMOVEALL Y APPEND LA TABLA NO SE RELLENARA HASTA QUE NO LE DEMOS A BUSCAR.
                                                    $table.bootstrapTable('removeAll');
                                                    $table.bootstrapTable('append',tabla_datos);

                                                    if (primera_vez == 0){
                                                        var pal_filtro = pal_filtro_det;
                                                        primera_vez += 1;
                                                    } else {                                                    
                                                        var pal_filtro = $('[name="filterAlgorithm"]').val()
                                                    }

                                                    //alert("al ejecutar la acción: " + pal_filtro);

                                                    if (pal_filtro == 'iniciados'){
                                                        $table.bootstrapTable('filterBy', {
                                                            estado: ['I']
                                                        })
                                                    } else if (pal_filtro == 'finalizados'){
                                                        //alert('entra');
                                                        $table.bootstrapTable('filterBy', {                                        
                                                            estado: ['F']
                                                        })
                                                    }

                                                    params.success(tabla_datos);

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

                        $(function() {
                        if (modo_dios == 1){
                            $table.bootstrapTable('refreshOptions', {
                                showColumns: true,
                                showToggle: true
                            })
                        }

                        //Para filtrar por un valor en concreto.
                        $('#filterBy').click(function () {
                            var pal_filtro = $('[name="filterAlgorithm"]').val()
                            //alert(pal_filtro);

                            if (pal_filtro != 'todos'){
                                
                                $table.bootstrapTable('refreshOptions', {
                                    filterOptions: {
                                        filterAlgorithm: 'or'
                                    }
                                })

                                if (pal_filtro == 'iniciados'){
                                    $table.bootstrapTable('filterBy', {
                                        estado: ['I']
                                    })
                                } else if (pal_filtro == 'finalizados'){
                                    //alert('entra');
                                    $table.bootstrapTable('filterBy', {                                        
                                        estado: ['F']
                                    })
                                }

                                
                            } else {
                                $table.bootstrapTable('filterBy', {});
                                $table.bootstrapTable('refresh');  
                            }                         
                        })
                    });

                        //DOCUMENTACION DE LAS SIGUIENTES TABLAS DE BOOTSTRAP EN EL SIGUIENTE ENLACE
                        //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                        $table.bootstrapTable({
                          data: [],
                          striped : true,
                          iconSize: "lg",
                          //hay que meter el tipo pdf, por defecto no sale, mas importar en la 
                          //cabecera los elementos correspondientes.
                          exportTypes: ['excel', 'pdf']
                        });

                        //DOCUMENTACION DE LAS SIGUIENTES TABLAS DE BOOTSTRAP EN EL SIGUIENTE ENLACE
                        //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                        $(function() {
                            //OCULTACION DE CAMPOS
                            //$table.bootstrapTable('hideColumn', 'codigo');

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
                            var codigo = fila['codigo'];
                            var denominacion = fila['denominacion'];
                            var cdproy = fila['cdproy'];
                            var deno_proy = fila['deno_proy'];
                            var wp = fila['wp'];
                            var motivo = fila['motivo'];
                            var fecha_ini = fila['fecha_ini'];
                            var fecha_fin = fila['fecha_fin'];
                            var total = fila['total'];
                            var cdtb = fila['cdtb'];
                            var deno_trab = fila['deno_trab'];
                            var estado = fila['estado'];
                            var origen = fila['origen'];
                            var destino = fila['destino'];
                            var obs = fila['obs'];

                            var cd_trabajador_bus = document.getElementById('cd_trabajador_bus').value;
                            var deno_trabajador_bus = document.getElementById('deno_trabajador_bus').value;
                            var fecha_ini_bus = document.getElementById('fecha_ini_bus').value;
                            var fecha_fin_bus = document.getElementById('fecha_fin_bus').value;

                            var pal_filtro = $('[name="filterAlgorithm"]').val();

                            //alert(texto);

                            location.href='viajes_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&codigo=' + codigo + '&denominacion=' + denominacion + '&cdproy=' + cdproy + '&deno_proy=' + deno_proy + '&wp=' + wp + '&motivo=' + motivo + '&fecha_ini=' + fecha_ini + '&fecha_fin=' + fecha_fin + '&total=' + total + '&cdtb=' + cdtb + '&deno_trab=' + deno_trab + '&estado=' + estado + '&origen=' + origen + '&destino=' + destino + '&obs=' + obs + '&pal_filtro=' + pal_filtro + '&ventana_alta=' + 0;


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
                               style = '<div style="color: ' + color_campo + '; font-weight:bold; font-size:14px">' + value +'</div>'; 
                            }

                            return style;
                        }

                        function cellStyleEstado(value, row, index) {
                            var color_campo = "";
                            //alert(row['estado']);

                            if (row['estado'] == "I") {
                                color_campo = '#a70074';
                            } else if (row['estado'] == "F"){
                                color_campo = '#20c200';
                            } else {                                                             
                                color_campo = '#000000';  
                            }

                            //Podriamos poner iconos o botones si quisieramos.
                            //'<div style="color: ' + color_campo + '; font-weight:bold;">' + '<i class="fa fa-dollar-sign"></i>' + value +'</div>'

                            return '<div style="color: ' + color_campo + '; font-weight:bold; font-size:14px">' + value +'</div>';
                        }

                        function eliminarTrabajadorSeleccionado() {
                            document.getElementById('cd_trabajador_bus').value = "";
                            document.getElementById('deno_trabajador_bus').value = "";
                            $table.bootstrapTable('refresh');
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

        <a href="#" class="float_alta2"  onclick="Alta_solicitud()" style="display:;">Alta
            <i class="fa fa-plus my-float"></i>
        </a>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
