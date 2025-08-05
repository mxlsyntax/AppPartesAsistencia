<?php
//require_once("config.php");
//require_once("conex.php");
require_once("login.php"); //Para iniciar sesión si no está iniciada
include("control_sesion.php"); //Control de tiempo de sesion, etc
include("config.php"); //Para tener el id_app_fijo por ejemplo
error_reporting(E_ERROR | E_PARSE); //Para que no muestre los warnings


//Gestionamos los elementos que vienen desde el login.

/*echo "<script>";
echo "alert('Entra en salir');";
echo "alert('El usuario es: " . $_COOKIE['user_id'] . "');";
echo "</script>";*/

$cdtrabajador = $_COOKIE['user_id' . '_' . $id_app_android];
$nombre_trab_login = $_COOKIE['nombre_trab_login' . '_' . $id_app_android];
$tipo_trab_login = $_COOKIE['tipo_trab_login' . '_' . $id_app_android];
//$nombre_trab_login = isset($_POST['nombre_trab_login']) ? $_POST['nombre_trab_login'] : "";
//$tipo_trab_login = isset($_POST['tipo_trab_login']) ? $_POST['tipo_trab_login'] : "";


$mensaje = $cdtrabajador;
//$mensaje = $cdtrabajador . ", " . $password_login . ".";
//NG20240703 INICIAMOS SESION CUANDO TRABAJADOR Y PASS VENGAN RELLENOS.


$vis_solo_jefegrupo = 'none';
if ($tipo_trab_login == 'S') {
    $nom_tipo_trab = "Usuario Normal";
    $vis_solo_jefegrupo = '';
} else if ($tipo_trab_login == 'A') {
    $nom_tipo_trab = "Administrador";
} else {
    $nom_tipo_trab = "Tipo de trab. desconocido";
}

//echo "<script>";
//echo "alert('El usuario es: " . $nombre_trab_login . "');";
//echo "</script>";
//$mensaje = $tipo_trab_login;
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

    <!-- Utilidades diversas, como gestion de fechas gsBase -->
    <script src="js/utilidades.js"></script>

    <!-- GUARDAMOS NUESTRO PROPIO HISTORIAL PARA GESTIONAR EL BOTON NEXT Y PREV DEL NAVEGADOR -->
    <script src="js/historial.js"></script>

    <script>
        const cdappfijo = "<?php echo $id_app_android; ?>";
        var salido = false;
        //alert("llega");


        //NG20241024 CONTROL DE HISTORIAL ETC, PARA CONTROLAR EL ATRAS DEL NAV.
        /*let miHistorial = new Historial(cdappfijo);
        miHistorial.push('main');
        //alert(miHistorial.mostrarHistorial());

        window.navigation.addEventListener("navigate", (event) => {
            //alert('Cada fiera a su cubil');
            const url = "" + new URL(event.destination.url);
            const url_prev = miHistorial.prev();

            //alert("Vamos a: " + url + " y la previa es: " + url_prev);

            //alert(url.indexOf('index') > -1);
            if (url.indexOf('index') > -1){
                if (!salido){
                    Salir2();
                }
                                   
            }
        });  */

        //alert("<?php echo $mensaje; ?>");

        //NG20240625 INFO DEL DISPOSITIVO Y NAV.
        var nav_y_disp = navigator.userAgent;

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

        const id_licencia_gsb_pref = GetVariableLocalStorage("id_licencia_gsb_pref");
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

        const modo_dios = GetVariableLocalStorage("modo_dios");


        let cdtrabajador_login = "";
        let nombre_trab_login = "";
        let tipo_trab_login = "";
        let nom_tipo_trab = "";

        var acciones_info = "Ventana: " + ventana_pref + "\nAcciones: a_devolver_avisos";
        GetVariablesStorageOrSesion();

        //Este metodo solo se ejecuta en el main, en las ventanas sucesivas, ya lo obtendremos siempre
        //del localStorage
        //Priorizamos la obtencion del localStorage y si no tenemos valor obtenemos de sesion
        function GetVariablesStorageOrSesion() {
            var locStor_cdtrabajador_login = "<?php echo $cdtrabajador; ?>";
            if (locStor_cdtrabajador_login == "" || locStor_cdtrabajador_login == '' || locStor_cdtrabajador_login === null) {
                locStor_cdtrabajador_login = GetVariableLocalStorage("cdtrabajador_login");
            }
            SetVariableLocalStorage("cdtrabajador_login", locStor_cdtrabajador_login);
            cdtrabajador_login = locStor_cdtrabajador_login;

            var locStor_nombre_trab_login = "<?php echo $nombre_trab_login; ?>";
            //alert(locStor_nombre_trab_login);
            if ((locStor_nombre_trab_login == "") || (locStor_nombre_trab_login == '') || (locStor_nombre_trab_login === null)) {
                locStor_nombre_trab_login = GetVariableLocalStorage("nombre_trab_login");
            }
            SetVariableLocalStorage("nombre_trab_login", locStor_nombre_trab_login);
            //alert(GetVariableLocalStorage("nombre_trab_login"));
            nombre_trab_login = locStor_nombre_trab_login;

            var locStor_tipo_trab_login = "<?php echo $tipo_trab_login; ?>";
            //alert(locStor_tipo_trab_login);
            if ((locStor_tipo_trab_login == "") || (locStor_tipo_trab_login == '') || (locStor_tipo_trab_login === null)) {
                locStor_tipo_trab_login = GetVariableLocalStorage("tipo_trab_login");
            }
            SetVariableLocalStorage("tipo_trab_login", locStor_tipo_trab_login);
            tipo_trab_login = locStor_tipo_trab_login;

            var locStor_nom_tipo_trab = "<?php echo $nom_tipo_trab; ?>";
            //alert(locStor_nom_tipo_trab);
            if ((locStor_nom_tipo_trab == "") || (locStor_nom_tipo_trab == '') || (locStor_nom_tipo_trab === null)) {
                locStor_nom_tipo_trab = GetVariableLocalStorage("nom_tipo_trab");
            }
            SetVariableLocalStorage("nom_tipo_trab", locStor_nom_tipo_trab);
            nom_tipo_trab = locStor_nom_tipo_trab;

        }

        function GetVariableLocalStorage(nombre_variable) {
            //alert("llegaget");
            return localStorage.getItem(nombre_variable + "_" + cdappfijo);
        }

        function SetVariableLocalStorage(nombre_variable, valor_variable) {
            //alert("llegaset");
            localStorage.setItem(nombre_variable + "_" + cdappfijo, valor_variable);
        }

        function Mostrar_acciones_info() {
            alert(acciones_info);
        }

        function Salir() {
            //habra que cambiar para cerrar sesion y esas cosas
            if (confirm("¿Seguro que desea cerrar la aplicación?")) {
                if (historico_activo == "S") {
                    EscribirHistorico();
                }
                //alert("main llama a Salir()");
                //BorrarConfiguracion(); //Esto se hace por las cookies por lo que no tiene sentido borrarlas
                //por la localstorage.
                location.href = 'exit.php?id_' + cdappfijo + '=3';
            }
        }


        function Salir2() {
            //habra que cambiar para cerrar sesion y esas cosas
            alert("Se dispone a cerrar la sesión");
            salido = true;
            if (historico_activo == "S") {
                EscribirHistorico();
            }
            //alert("main llama a Salir()");
            //BorrarConfiguracion(); //Esto se hace por las cookies por lo que no tiene sentido borrarlas
            //por la localstorage.
            location.href = 'exit.php?id_' + cdappfijo + '=3';
        }

        function EscribirHistorico() {
            var arg = "codigo_trab: " + cdtrabajador_login;
            var accion = "escribir_historico";
            var accion_gsb = "Cierre sesion web";
            var respuesta = nav_y_disp;

            //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + respuesta + ", " + trab_logueado + ". ");

            $.ajax({
                url: url_conexion,
                data: {
                    "servidor_ip_publica": servidor_ip_publica,
                    "puerto": puerto,
                    "empresa_gestora": empresa_gestora,
                    "aplicacion": aplicacion,
                    "ejercicio": ejercicio,
                    "empresa_id": empresa_id,
                    "ventana_pref": ventana_pref,
                    "arg": arg,
                    "accion": accion,
                    "accion_gsb": accion_gsb,
                    "cd_pref_autogen": cd_pref_autogen,
                    "respuesta": respuesta
                },
                type: "POST",
                //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                timeout: 2000,
                success: function(response) {
                    //alert(response);
                },
                error: function(xmlhttprequest, textstatus, message) {
                    //No mostramos mensaje alguno
                    /*if(textstatus==="timeout") {
                        alert("No se ha podido conectar con el servidor, revise las preferencias.");
                    } else {
                        alert(textstatus);
                    }*/
                }
            });
        }

        function BorrarConfiguracion() {
            localStorage.setItem("cdtrabajador_login", "");
            localStorage.setItem("nombre_trab_login", "");
            localStorage.setItem("tipo_trab_login", "");
        }

        function playSound(url) {
            const audio = new Audio(url);
            audio.play();
        }

        //NG20250408 DAMOS VISIBILIDAD A UN FA-SHAKE PARA QUE PAREZCA QUE 
        //VIBRA DURANTE UNOS SEGUNDOS.
        function VibrarCampana() {
            //alert("vibra");
            document.getElementById('campana_not').style.display = 'none';
            document.getElementById('campana_not_shake').style.display = '';
            //var campana_not_shake = document.getElementById('campana_not_shake');
            //campana_not.classList.toggle("fa-shake");
            setTimeout(function() {
                //No funciona
                //campana_not.classList.remove("fa-shake");                    
                document.getElementById('campana_not').style.display = '';
                document.getElementById('campana_not_shake').style.display = 'none';
                //alert("deja de vibrar");
            }, 1500);
        }

        if (!("Notification" in window)) {
            // Check if the browser supports notifications
            alert("Este navegador no soporta notificaciones");
        } else {
            let permission = Notification.permission;

            if (permission === "granted") {
                showNotification();
            } else if (permission === "default") {
                requestAndShowPermission();
            } else {
                alert("Hay nuevos avisos que revisar");
            }
        }


        function requestAndShowPermission() {
            Notification.requestPermission(function(permission) {
                if (permission === "granted") {
                    showNotification();
                }
            });
        }

        function showNotification() {
            //  if(document.visibilityState === "visible") {
            //      return;
            //  }

            let title = "Avisos nuevos";
            let icon = 'assets/icons/icon-96x96.png'; //this is a large image may take more time to show notifiction, replace with small size icon
            let body = "Hay nuevos avisos que revisar";

            let notification = new Notification(title, {
                body,
                icon
            });

            notification.onclick = () => {
                notification.close();
                document.getElementById("btn_avisos").click();
            }

        }

        //https://examples.bootstrap-table.com/#view-source
        //NG20240709 LLAMADA A GSBASE PARA OBTENER TRABAJADORES
        function a_devolver_avisos() {
            var cd_trabajador_bus = cdtrabajador_login;
            //alert("Trabajador bus" + cd_trabajador_bus);
            //Tiramos de utilidades.js para no engrosar el codigo aqui.
            var fecha_ini = Fecha_aNum(format_fecha_local(document.getElementById('fecha_ini_bus').value));
            var fecha_fin = Fecha_aNum(format_fecha_local(document.getElementById('fecha_fin_bus').value));

            //alert(convertirTiempo(0));

            if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                alert("Faltan valores de conexión, revise los parametros");
            } else {

                var arg = '{' +
                    '"cdtrabajador" : "' + cd_trabajador_bus + '",' +
                    '"cdresponsable" : "' + cdtrabajador_login + '",' +
                    '"fechaDesde" : ' + fecha_ini + ',' +
                    '"fechaHasta" : ' + fecha_fin //Que no se nos olvide quitar la coma solo en el ult.element
                    +
                    '}';

                //alert(arg);

                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                var accion = "ejecutar_accion_gsb";
                var accion_gsb = "a_devolver_avisos";

                //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                //$("#overlay").fadeIn(300);

                $.ajax({
                    url: url_conexion,
                    //con esta url si llega a conectar con el php pero no llega a gsbase                    
                    //url: 'https://www.globalsystem.es/AppWeb/PortalEmpleado/funciones.php',
                    data: {
                        "servidor_ip_publica": servidor_ip_publica,
                        "puerto": puerto,
                        "empresa_gestora": empresa_gestora,
                        "aplicacion": aplicacion,
                        "ejercicio": ejercicio,
                        "empresa_id": empresa_id,
                        "ventana_pref": ventana_pref,
                        "arg": arg,
                        "accion": accion,
                        "accion_gsb": accion_gsb,
                        "cd_pref_autogen": cd_pref_autogen,
                        "historico_activo": historico_activo
                    },
                    type: "POST",
                    //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                    timeout: 2000,
                    success: function(response) {

                        //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                        $("#overlay").fadeOut(300);
                        //alert(response);

                        //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                        //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                        //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                        try {
                            var respuesta = $.parseJSON(response);

                            if (respuesta['resultado'] == "ok") {
                                //alert(respuesta['datos'].length);

                                if (respuesta['datos'].length > 0) {
                                    VibrarCampana();
                                    //<i class="fa fa-bell fa-shake my-float" id="campana_not"></i>
                                    document.getElementById('btn_sonido_avisos').click();


                                    document.getElementById('float_avisos_span').innerText = respuesta['datos'].length;
                                    document.getElementById('float_avisos_enlace').style.backgroundColor = "#ff0000";
                                } else {
                                    document.getElementById('float_avisos_enlace').style.backgroundColor = "#003061";
                                    document.getElementById('float_avisos_span').innerText = '0';
                                }

                            } else {
                                alert(respuesta['datos']);
                                document.getElementById('float_avisos_enlace').style.backgroundColor = "#003061";
                                document.getElementById('float_avisos_span').innerText = '0';


                            }
                        } catch (error) {
                            alert(error + "Resp. gsBase: " + response);
                            document.getElementById('float_avisos_enlace').style.backgroundColor = "#003061";
                            document.getElementById('float_avisos_span').innerText = '0';
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

                        //document.getElementById('tableHistorico').style.display='none';
                    }
                });
            }
        }

        //NG20240702 comprobamos los datos guardados en el localstorage en el lado del cliente
        //ingresa los datos en localstorage si no lo están ya, para siguientes ventanas.
        //Este metodo hay que implementarlo en las ventanas en las que llamemos a gsBase
        function ComprobarConfiguracion() {

            if (nombre_trab_login != "") {
                document.getElementById('nombre_trab_html').innerText = nombre_trab_login;
            }

            var vis_solo_jefegrupo = 'none';
            if ((nom_tipo_trab == "") || (nom_tipo_trab == '') || (nom_tipo_trab === null)) {
                nom_tipo_trab = "Tipo de trab. desconocido";
            }


            document.getElementById('tipo_trab_html').innerText = nom_tipo_trab;


            //alert(new Date(Date.now()).toLocaleString());
            document.getElementById('fecha_html').innerText = new Date(Date.now()).toLocaleDateString();

            document.getElementById('fecha_ini_bus').value = formatDate(new Date(Date.now() - 30 * 86400000));
            document.getElementById('fecha_fin_bus').value = formatDate(new Date(Date.now() + 7 * 86400000));

            a_devolver_avisos();

            if (modo_dios == 1) {
                document.getElementById('img_modo_dios').style.display = '';
            }
        }
    </script>
    <!-- Firebase App (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <!-- Firebase Messaging (compat) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    <!-- NG20250506 Timer para las notificaciones -->
    <script src="js/timer_notificaciones.js"></script>
</head>

<body id="page-top" onload="ComprobarConfiguracion()">
    <div class="g-signin2" data-onsuccess="onSignIn"></div>
    <!-- APP BAR-->
    <?php
    $titulo = 'MAIN'; // Cambia el título para cada vista
    $acciones = 'a_devolver_avisos, a_escribir_historico';
    include 'header.php';
    ?>
    <!-- Portfolio Section-->
    <section class="page-section portfolio" id="portfolio">
        <div class="container d-flex align-items-center flex-column">
            <img src="assets/img/logo_gss.png" class="img-fluid" alt="Logo global system">
            <br>
            <div style="border-radius: 10px; border-color: #003061; border-style: solid; padding: 10px; margin-bottom: 10px; align-content: center;">

                <div class="container d-flex align-items-center flex-column">
                    <h4 id="nombre_trab_html" class="portfolio-modal-title text-secondary text-uppercase mb-0">Nombre trabajador</h4>
                    <p id="tipo_trab_html" style="color: #404040; font-weight: bold; font-size: 20px;margin-bottom: 0px;">Tipo de trabajador</p>
                    <hr style="color: #003061;border-top-width: 5px;width: 200px;opacity: 100;">
                    <p id="fecha_html" style="color: #3F51B5; font-weight: bold; font-size: 24px;margin-bottom: 0px;">Fecha</p>
                </div>
            </div>
        </div>

        <div class="container" style="margin-top:0px; display:none;">
            <div class="input-group mb-3">
                <div class="form-floating" style="margin-right: 10px;">
                    <input class="form-control" id="fecha_ini_bus" name="fecha_ini_bus" type="date" placeholder="Fch. Inicio" style="padding-left:8px" onchange="a_devolver_avisos();">
                    <label for="fecha_ini_bus">Fch. Inicio</label>
                </div>
                <div class="form-floating" style="margin-left: 10px;">
                    <input class="form-control" id="fecha_fin_bus" name="fecha_fin_bus" type="date" placeholder="Fch. Fin" style="padding-left:8px" onchange="a_devolver_avisos();">
                    <label for="fecha_fin_bus">Fch. Fin</label>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="max-width: 600px;">
            <!-- Center -->
            <div class="row text-center" style="margin-top:10px;">
                <div class="col">
                    <!-- NG20240809 BOTON PARA INSTALAR COMO APP, HAY QUE PROBAR EN IOS, SE ESCONDE SI NOS SALE AVISO
                        DE QUE LA APLICACION SE PUEDE INSTALAR EN EL DISPOSITIVO MOVIL, AHORA MISMO EN IOS EN SAFARI NO FUNCIONA -->
                    <button class="btn btn-global2" type="button" id="buttonInstallApp" onclick="Instalar()" style="margin: 10px; min-height: 70px; min-width: 270px; display:none">Instalar App</button>
                    <button class="btn btn-global" type="button" id="btn_avisos" onclick="location.href='avisos_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Avisos</button>
                    <button class="btn btn-global" type="button" onclick="location.href='documentacion_bus.php'" id="btn_documentacion" style="margin: 10px; min-height: 70px; min-width: 170px; display: none;">Documentación</button>
                    <button class="btn btn-global" type="button" onclick="location.href='articulos_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Artículos</button>
                    <button class="btn btn-global" type="button" onclick="location.href='clientes_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Clientes</button>
                    <button class="btn btn-global" type="button" onclick="location.href='maquinas_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Maquinas</button>
                    <button class="btn btn-global" type="button" onclick="location.href='partes_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Partes</button>

                    <!-- <button class="btn btn-global" type="button" onclick="location.href='gestion_bus2.php'" style="margin: 10px; min-height: 70px; min-width: 170px;">Gestión Pedidos 2</button>  -->
                    <button class="btn btn-global" type="button" onclick="location.href='ausencias_bus.php'" style="margin: 10px; min-height: 70px; min-width: 170px; display: none;">Ausencias</button>

                    <!-- <button class="btn btn-global2" onclick="notifyMe()" style="margin: 10px; min-height: 70px; min-width: 170px; display: ;">Notify me!</button> -->

                    <button class="btn btn-global2" id="btn_sonido_avisos" onclick="playSound('assets/audio/notificacion.mp3');" style="margin: 10px; min-height: 70px; min-width: 170px; display: none;">Play</button>
                </div>
            </div>
        </div>
        <!-- Botón oculto para lanzar el popover señalando el bóton compartir del safari en ios-->
        <a class="float2" id="botonPopOver" data-bs-toggle="popover" data-bs-title="Instalar App" data-bs-placement="top" data-bs-trigger="focus" title="Dismissible popover" data-bs-html="true" data-bs-content="Por favor pulse sobre el botón compartir <img src='assets/images/sharesafariios.png'>y después añadir al inicio." data-bs-img="//placehold.it/50x50"></a>


    </section>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <script>
        //NG20240811 PARA LANZAR EL POPOVER DE INFO INSTALACION EN IOS, TENDREMOS QUE INICIALIZAR
        //LAS DOS SIGUIENTES LINEAS.
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });

        //NG20240809 GESTION DE INSTALACION, EN ANDROID FUNCIONABA BIEN SIN HACER NADA
        //PERO EN IOS NO, POR LO QUE HE DECIDIDO PONERLO CON BOTÓN.-------------------------------
        //NG20240809 Detectamos si se está ejecutando en ios


        var exampleTriggerEl = document.getElementById('botonPopOver');
        var popover = bootstrap.Popover.getInstance(exampleTriggerEl);



        const isIos = () => {
            const userAgent = window.navigator.userAgent.toLowerCase();
            return /iphone|ipad|ipod/.test(userAgent);
        }

        //NG20240809 Detectamos si se esta ejecutando instalada o no.
        const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

        //Si no esta instalada, si es ios mostramos mensaje, de lo contrario mostramos boton de instalacion
        if (!isInStandaloneMode()) {
            if (isIos()) {
                ForzarMostradoPopover();
            }
        }

        // Inicializa deferredPrompt para su uso más tarde.
        let deferredPrompt;
        const buttonInstallApp = document.getElementById('buttonInstallApp');

        window.addEventListener('beforeinstallprompt', (e) => {
            console.log("Entra");
            // Previene a la mini barra de información que aparezca en smartphones
            e.preventDefault();
            // Guarda el evento para que se dispare más tarde
            deferredPrompt = e;
            // Solo le damos visibilidad al botón si no estamos en ios.
            if (!isIos()) {
                document.getElementById('buttonInstallApp').style.display = '';
            }
            // De manera opcional, envía el evento de analíticos para saber si se mostró la promoción a a instalación del PWA
            console.log("'beforeinstallprompt' event was fired.");
        });

        async function Instalar() {
            if (!deferredPrompt) {
                return;
            }
            const result = await deferredPrompt.prompt();
            //console.log(`Install prompt was: ${result.outcome}`);
            disableInAppInstallPrompt();

        }

        //NG20240811 PARA PRUEBA MANUAL
        function ForzarMostradoPopover() {
            popover.show();
            setTimeout(() => {
                popover.hide()
            }, 5000);
        }

        function showInstallPromotion() {
            result = confirm("¿Desea instalar la aplicación?");
            // Como ya usamos el mensaje, no lo podemos usar de nuevo, este es descartado
            deferredPrompt = null;
        }

        function disableInAppInstallPrompt() {
            deferredPrompt = null;
            document.getElementById('buttonInstallApp').style.display = 'none';
        }

        function AltaDieta() {
            var cd_trabajador_bus = cdtrabajador_login;
            var deno_trabajador_bus = nombre_trab_login;
            var fecha_ini_bus = formatDate(new Date(Date.now() - 30 * 86400000));
            var fecha_fin_bus = formatDate(new Date(Date.now() + 7 * 86400000));

            location.href = 'dietas_detalle.php?cd_trabajador_bus=' + cd_trabajador_bus + '&deno_trabajador_bus=' + deno_trabajador_bus + '&fecha_ini_bus=' + fecha_ini_bus + '&fecha_fin_bus=' + fecha_fin_bus + '&ventana_alta=' + 1 + '&vent_origen=' + 'main.php';

        }

        //ForzarMostradoPopover();

        //NG20240809 GESTION DE INSTALACION, EN ANDROID FUNCIONABA BIEN SIN HACER NADA
        //PERO EN IOS NO, POR LO QUE HE DECIDIDO PONERLO CON BOTÓN.-------------------------------
    </script>

    <a href="#" class="float_avisos" onclick="location.href='avisos_bus.php'" id="float_avisos_enlace">
        <span id="float_avisos_span"></span>
        <i class="fa fa-bell my-float" id="campana_not"></i>
        <i class="fa fa-bell fa-shake my-float" id="campana_not_shake" style="display:none"></i>
    </a>

</body>


</html>