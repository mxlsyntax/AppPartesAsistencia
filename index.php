<?php
include("config.php");

/*echo "<script>";
    echo "alert('Entra en salir');";
    echo "alert('El usuario es: " . $id_app_android . "');";
    echo "</script>";*/

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplicación Web para registrar partes de asistencia" />
    <meta name="author" content="NG" />

    <!-- Para que no se guarde en la cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">


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
    <!-- Lector QR-->
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <!-- Generador QR-->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- NG20240626 AHORA CARGAMOS EL SIGUIENTE SCRIPT JUSTO CUANDO ABRIMOS MODAL, PARA QUE COJA LA URL DE LAS PREF-->
    <!-- <script defer src="app.js"></script>-->
    <!-- Generador identificador unico-->
    <script src="libs/device-uuid.min.js"></script>
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- GUARDAMOS NUESTRO PROPIO HISTORIAL PARA GESTIONAR EL BOTON NEXT Y PREV DEL NAVEGADOR -->
    <script src="js/historial.js"></script>

    <script type="text/javascript">
        const cdappfijo = "<?php echo $id_app_android; ?>";

        //NG20241024 CONTROL DE HISTORIAL ETC, PARA CONTROLAR EL ATRAS DEL NAV.        
        const miHistorial = new Historial(cdappfijo);
        miHistorial.push('login');
        //alert(miHistorial.mostrarHistorial());

        var acciones_info = "a_login, a_cambia_pin, a_comprobar_conexion (en preferencias), a_leer_trabajadores (en iframe)";

        var uuid = new DeviceUUID().get();
        //NG20240618 ID UNICO POR DISPOSITIVO Y NAVEGADOR.
        //alert(uuid);

        //NG20240625 INFO DEL DISPOSITIVO Y NAV.
        var nav_y_disp = navigator.userAgent;

        var hostname = window.location.hostname;
        //NG20240628 PARA LAS CONEXIONES CON LA BD, PARA CUANDO HACEMOS PRUEBAS
        //EN LOCAL, NECESITAMOS LLAMAR A LO QUE HAY EN LA WEB, PORQUE SI NO NO TENEMOS ACCESO 
        //A LA BD DE PHPMYADMIN. El php para acceder a la base de datos del phpmyadmin tienen que estar
        //en el mismo lugar que la bd.
        //y para acceder a gsbase tiene que estar publico o tambien en el mismo sitio.
        var url_conexion = "funciones.php";
        var url_conexion_bd = "https://www.globalsystem.es/AppWeb/AppViajesDietas/" + url_conexion;


        //https://stackoverflow.com/questions/1301540/set-variable-in-parent-window-from-iframe
        //NG20240620 LA PREPARAMOS PARA ESCRIBIRLA DESDE EL IFRAME.
        var codigo_escaneado = "";

        var clic_pref1 = 0;
        var clic_pref2 = 0;

        //NG20240617 1o comprobamos si el par cdaplicacion y uuid esta registrado y tiene autogen asociado.
        function comprobar_uuid() {
            //NG20250203 DESACTIVAMOS SIEMPRE AL INICIO EL MODO DIOS
            document.getElementById("cbxModoDios").checked = false;
            SetVariableLocalStorage("modo_dios", 0);

            //alert("Entra");
            //https://es.stackoverflow.com/questions/395486/como-se-har%C3%ADan-estas-funciones-de-forma-as%C3%ADncrona-para-que-se-ejecute-la-sentenc
            $.ajax({
                url: url_conexion_bd,
                data: {
                    "uuid": uuid,
                    "accion": "comprobar_uid"
                },
                type: "POST",
                //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                timeout: 2000,
                success: function(response) {
                    //alert("obtenemos uuid: " + uuid + ".");
                    //alert(response);
                    if (response != '') {
                        //NG20240623 OBTENEMOS LAS PREF.
                        obtener_pref_autogen(response);
                    } else {
                        AbrirModalCodigoPref(true);
                    }
                },
                error: function(xmlhttprequest, textstatus, message) {
                    if (textstatus === "timeout") {
                        alert("No se ha podido conectar con el servidor, revise las preferencias.");
                    } else {
                        alert(textstatus);
                    }
                }
            });
        }

        function AbrirModalCodigoPref(obligatorio) {
            //alert(obligatorio);
            if (obligatorio) {
                //NG20240618 ABRIMOS EL MODAL PARA SOLICITAR EL CODIGO DE LICENCIA DESDE JAVASCRIPT.
                //no permitimos tampoco que puedan cerrarlo con escape data-keyboard="false" and data-backdrop="static"
                $('#modalCodigoPref').modal({
                    backdrop: 'static'
                });
                //no funciona lo de escape
                $('#modalCodigoPref').modal({
                    keyboard: 'true'
                });
            } else {
                $('#modalCodigoPref').modal({
                    backdrop: true
                });
            }

            //NG20240730 CON LA INTENCION DE QUE SI SE DESCONFIGURA, APROVECHARLO SI LO
            //TENEMOS EN LA LOCALSTORAGE
            var cd_pref_autogen = GetVariableLocalStorage("cd_pref_autogen");

            if (cd_pref_autogen != "") {
                document.getElementById("cd_pref_autogen").value = cd_pref_autogen;
            }

            $('#modalCodigoPref').modal('show');
            //NG20240627 REVISAR A VER SI PODEMOS PONER EL FOCO. ESTO SIGUIENTE NO FUNCIONA.
            $('#cd_pref_autogen').focus();
            $('#cd_pref_autogen').select();
        }

        //NG20240617 2o si la comprobacion anterior no tiene autogen asociado, pedimos uno, comprobamos que exista y lo
        //escribimos junto al uuid para no volver a pedirlo.
        function escribir_autogen_uuid() {
            var cd_pref_autogen = document.getElementById("cd_pref_autogen").value;
            //alert("El cd_autogen es: " + cd_pref_autogen);
            //alert("escribimos uuid junto cd_pref_autogen junto uuid: " + uuid + ".");
            $.ajax({
                url: url_conexion_bd,
                data: {
                    "uuid": uuid,
                    "accion": "comprobar_cdautogen",
                    "cd_pref_autogen": cd_pref_autogen,
                    "nav_y_disp": nav_y_disp
                },
                type: "POST",
                //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                timeout: 2000,
                success: function(response) {
                    //alert(response);
                    if (response == 'Ok') {
                        alert("Código validado correctamente!!!");
                        $('#modalCodigoPref').modal('hide');

                        //NG20240623 OBTENEMOS LAS PREF.
                        obtener_pref_autogen("");
                    } else {
                        alert("El código de licencia es incorrecto!!!");
                    }
                },
                error: function(xmlhttprequest, textstatus, message) {
                    if (textstatus === "timeout") {
                        alert("No se ha podido conectar con el servidor, revise las preferencias.");
                    } else {
                        alert(textstatus);
                    }
                }
            });
        }

        //NG20240623 UNA VEZ ESCRITO LA DUPLA UUID Y CODIGO AUTOPREF, OBTENEMOS LAS PREFERENCES
        function obtener_pref_autogen(cd_pref_devuelto) {
            //alert(cd_pref_devuelto);
            var cd_pref_autogen = "";
            if (cd_pref_devuelto == "") {
                cd_pref_autogen = document.getElementById("cd_pref_autogen").value;
            } else {
                cd_pref_autogen = cd_pref_devuelto;
            }

            //alert("Obtenemos las pref asociadas al cd_pref_autogen: " + cd_pref_autogen + ".");
            $.ajax({
                url: url_conexion_bd,
                data: {
                    "accion": "pref_autogen",
                    "cd_pref_autogen": cd_pref_autogen
                },
                type: "POST",
                //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                timeout: 2000,
                success: function(response) {
                    //alert(response);

                    //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                    //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                    //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                    try {
                        if (response.indexOf("Error") !== -1) {
                            alert("Un error ha ocurrido obteniendo los valores de conexión. Pongase en contacto con soporte");
                        } else {
                            var array_json = JSON.parse(response);

                            document.getElementById("cd_pref_autogen_pref").value = cd_pref_autogen;

                            if ((!ComprobarConfiguracion()) || (!ComprobarPrefRellenas()) || (array_json[0].forzar_cambio == "S")) {
                                //var array_json = $.parseJSON(response);
                                //NG20240627 LO PONEMOS TAMBIEN EN EL MODAL DEL CODIGO DE LAS PREFERENCIAS POR SI
                                //VAMOS A CAMBIARLO.
                                document.getElementById("cd_pref_autogen").value = cd_pref_autogen;
                                document.getElementById("id_licencia_gsb_pref").value = array_json[0].id_licencia_gsb;
                                document.getElementById("forzar_cambio_pref").value = array_json[0].forzar_cambio;
                                document.getElementById("historico_activo").value = array_json[0].historico_activo;

                                document.getElementById("servidor_ip_publica").value = array_json[0].servidor_ip_publica;

                                document.getElementById("servidor_ip_privada").value = array_json[0].servidor_ip_privada;
                                document.getElementById("puerto").value = array_json[0].puerto;
                                document.getElementById("empresa_gestora").value = array_json[0].empresa_gestora;
                                document.getElementById("ventana_pref").value = array_json[0].ventana;
                                document.getElementById("aplicacion").value = array_json[0].aplicacion;
                                document.getElementById("ejercicio").value = array_json[0].ejercicio;
                                //alert(array_json[0].empresa_id);
                                document.getElementById("empresa_id").value = array_json[0].empresa_id;

                                //NG20240623 A VER SI PODEMOS METER EL URL EN SU SITIO EN EL LOGIN, PERO HABRIA QUE RECARGARLO.
                                //alert(array_json[0].url_qr_compartir_app);
                                document.getElementById("qrbutton_pref").value = array_json[0].url_qr_compartir_app;


                                document.getElementById("cdaplicacion").value = array_json[0].ids_app_android.replaceAll("-", "");

                                document.getElementById("mostrar_obras").value = array_json[0].mostrar_obras;

                                //alert("Carga de preferencias correcta!!!" + array_json[0].mostrar_obras); 
                                //NG20240702 GUARDAMOS ESTOS DATOS EN LOCALSTORAGE EN EL LADO DEL CLIENTE
                                GuardarLocalStorage();
                            }
                        }


                    } catch (error) {
                        alert(error + "Resp. gsBase: " + response);
                    }


                },
                error: function(xmlhttprequest, textstatus, message) {
                    if (textstatus === "timeout") {
                        alert("No se ha podido conectar con el servidor, revise las preferencias.");
                    } else {
                        alert(textstatus);
                    }
                }
            });
        }

        function ComprobarConfiguracion() {
            result = true;
            var servidor_ip_publica = GetVariableLocalStorage("servidor_ip_publica");
            var puerto = GetVariableLocalStorage("puerto");
            var empresa_gestora = GetVariableLocalStorage("empresa_gestora");
            var ventana_pref = GetVariableLocalStorage("ventana_pref");
            var aplicacion = GetVariableLocalStorage("aplicacion");
            var ejercicio = GetVariableLocalStorage("ejercicio");

            if (servidor_ip_publica == "" || puerto == "" || empresa_gestora == "" || ventana_pref == "" || aplicacion == "" || ejercicio == "") result = false;

            return result;
        }

        function ComprobarPrefRellenas() {
            result = true;
            var servidor_ip_publica = document.getElementById("servidor_ip_publica").value;
            var puerto = document.getElementById("puerto").value;
            var empresa_gestora = document.getElementById("empresa_gestora").value;
            var ventana_pref = document.getElementById("ventana_pref").value;
            var aplicacion = document.getElementById("aplicacion").value;
            var ejercicio = document.getElementById("ejercicio").value;

            if (servidor_ip_publica == "" || puerto == "" || empresa_gestora == "" || ventana_pref == "" || aplicacion == "" || ejercicio == "") result = false;

            return result;
        }

        function SetVariableLocalStorage(nombre_variable, valor_variable) {
            localStorage.setItem(nombre_variable + "_" + cdappfijo, valor_variable);
        }

        function GetVariableLocalStorage(nombre_variable) {
            return localStorage.getItem(nombre_variable + "_" + cdappfijo);
        }

        //NG20240702 ESTE METODO GUARDA EN EL CLIENTE EN localStorage() los datos de conexion
        //si borran la cache de la pagina o navegador se borraran estos datos.
        function GuardarLocalStorage() {
            SetVariableLocalStorage("cdaplicacion", document.getElementById("cdaplicacion").value);
            SetVariableLocalStorage("cd_pref_autogen", document.getElementById("cd_pref_autogen").value);
            SetVariableLocalStorage("id_licencia_gsb_pref", document.getElementById("id_licencia_gsb_pref").value);
            SetVariableLocalStorage("historico_activo", document.getElementById("historico_activo").value);
            SetVariableLocalStorage("servidor_ip_publica", document.getElementById("servidor_ip_publica").value);
            SetVariableLocalStorage("puerto", document.getElementById("puerto").value);
            SetVariableLocalStorage("empresa_gestora", document.getElementById("empresa_gestora").value);
            SetVariableLocalStorage("ventana_pref", document.getElementById("ventana_pref").value);
            SetVariableLocalStorage("aplicacion", document.getElementById("aplicacion").value);
            SetVariableLocalStorage("ejercicio", document.getElementById("ejercicio").value);
            SetVariableLocalStorage("empresa_id", document.getElementById("empresa_id").value);
            SetVariableLocalStorage("trabajador_pref", document.getElementById("trabajador_pref").value);
            SetVariableLocalStorage("url_conexion", url_conexion);

            var modo_dios = document.getElementById('cbxModoDios').checked ? 1 : 0;
            SetVariableLocalStorage("modo_dios", modo_dios);
        }

        //NG20240621 ESTA FUNCION ES LLAMADA DESDE EL MISMO MODAL
        function CerrarModalEscaneo() {
            $('#modalEscaneoCodigo').modal('hide');
        }

        //ng20240623 gestion de la camara, llamando a escaneo2.html.
        //importar estos metodos cuando queramos escanear.
        //el modal y tener en cuenta como se llama el input de aqui,
        //para llamarlo en html igual, en todos los sitios donde 
        //queramos llamar al escaneo.
        function abrirEscaneo2() {
            //NG20240623 NO PONEMOS EL SRC EN EL IFRAME EN HTML, PORQUE 
            //MARCA LA CAMARA COMO SI ESTUVIERA LEYENDO YA, ANTES INCLUSO DE ABRIRLO
            //POR LA CARGA DEL HTML.
            document.getElementById('escaneo2').src = "escaneo2.html";
            //NG20240627 ABRIMOS MODAL SIN ABRIR EL TECLADO DE PRIMERAS.
            //$('#modalEscaneoCodigo2').modal({backdrop: 'static',keyboard: false})  
            //NG20240618 ABRIMOS EL MODAL DESDE JAVASCRIPT.
            $('#modalEscaneoCodigo2').modal('show');
        }

        //NG20240621 ESTA FUNCION ES LLAMADA DESDE EL MISMO MODAL
        function CerrarModalEscaneo2() {
            document.getElementById('escaneo2').src = "";
            $('#modalEscaneoCodigo2').modal('hide');
        }

        //NG20240626 FUNCION QUE ABRE EL MODAL DESPUES DE HABER CARGADO EL JS QUE RELLENA EL QR
        //LO HACEMOS AL ABRIR EL MODAL PORQUE LAS PREF HAN SIDO CARGADAS YA.
        function AbrirModalCompartirQR() {
            //Con el siguiente codigo lanzamos un .js desde el javascript.
            $.getScript('app.js', function() {
                $('#modalcompartirqr').modal('show');
            });
        }

        //NG20240618 PEDIMOS CONTRASEÑA PARA ABRIR LAS PREF.
        function passPref() {
            //Ingresamos un mensaje a mostrar
            var opcion = prompt("Introduzca contraseña", "");
            //Detectamos si el usuario ingreso un valor
            if (opcion != null) {
                if (opcion == 'global') {
                    //NG20240618 ABRIMOS EL MODAL DESDE JAVASCRIPT.
                    $('#modalPreferences').modal({
                        backdrop: 'static'
                    })
                    $('#modalPreferences').modal('show');
                } else {
                    alert("Contraseña incorrecta.");
                }
            } else {
                alert("Contraseña incorrecta");
            }
        }

        function VisOpcionesDesarrollador1() {
            clic_pref1 = clic_pref1 + 1;
            //alert("Hemos pulsado " + clic_pref);



            if (clic_pref1 > 5 && clic_pref2 > 5) {

                clic_pref1 = 0;
                clic_pref2 = 0;

                var opcion = prompt("Introduzca pass", "");

                if (opcion != null) {

                    if (opcion == 'global') {
                        ActModoDes();
                    } else {
                        alert("Contraseña incorrecta.");
                    }
                } else {
                    alert("Contraseña incorrecta");
                }
            } else if (clic_pref1 > 5) {
                alert("Te falta algo mas.");
            }
        }

        function VisOpcionesDesarrollador2() {
            clic_pref2 = clic_pref2 + 1;
            //alert("Hemos pulsado " + clic_pref);



            if (clic_pref1 > 5 && clic_pref2 > 5) {

                clic_pref1 = 0;
                clic_pref2 = 0;

                var opcion = prompt("Introduzca pass", "");

                if (opcion != null) {

                    if (opcion == 'global') {
                        ActModoDes();
                    } else {
                        alert("Contraseña incorrecta.");
                    }
                } else {
                    alert("Contraseña incorrecta");
                }

            }
        }

        function ActModoDes() {
            alert("Modo desarrollador activo!!");
            document.getElementById("pref_opciones_desarrollador").style.display = 'block';
        }

        //NG20240627 LO UTILIZAMOS PARA VOLVER A OCULTAR EL MODO DESARROLLADOR
        function CerrarModalPref() {
            clic_pref = 0;
            //NG20240711 VOLVEMOS A GUARDAR LOS ELEMENTOS POR SI HAN CAMBIADO.
            GuardarLocalStorage();
            document.getElementById("pref_opciones_desarrollador").style.display = 'none';
            $('#modalPreferences').modal('hide');
        }

        //NG20240623 PEDIMOS CONTRASEÑA PARA ABRIR LAS PREF.
        function abrirModalSeleccionTrab() {
            var src = "seleccion_trabajador.php?vent=login";
            src += "&time=" + new Date().getTime();
            //NG20240717 TENEMOS UN PROBLEMA EN LOS IFRAME, AUNQUE HAGAMOS CAMBIOS ESTOS NO SE REFRESCAN
            //PORQUE LA PAGINA SIGUE ALMACENADA EN CACHE, PARA RESOLVERLO ANADIMOS UN ARGUMENTO CON MARCA DE TIEMPO
            //PARA QUE SIEMPRE ESTE CAMBIANDO.
            document.getElementById('iframeSelTrab').src = src;
            //NG20240627 ABRIMOS MODAL SIN ABRIR EL TECLADO DE PRIMERAS.
            //$('#modalEscaneoCodigo2').modal({backdrop: 'static',keyboard: false})  
            //NG20240618 ABRIMOS EL MODAL DESDE JAVASCRIPT.
            $('#modalSeleccionTrabajador').modal('show');
        }

        function abrirModalSeleccionTrabPref() {
            var src = "seleccion_trabajador.php?vent=pref";
            src += "&time=" + new Date().getTime();
            document.getElementById('iframeSelTrab').src = src;
            //NG20240627 ABRIMOS MODAL SIN ABRIR EL TECLADO DE PRIMERAS.
            //$('#modalEscaneoCodigo2').modal({backdrop: 'static',keyboard: false})  
            //NG20240618 ABRIMOS EL MODAL DESDE JAVASCRIPT.
            $('#modalSeleccionTrabajador').modal('show');
        }

        //NG20240711 FUNCION UTILIZADA DESDE LOS MODALES/IFRAME PARA CERRAR EL MODAL Y RESETEAR LOS
        //VALORES DEL IFRAME
        function cerrarModalSeleccionTrabajador() {
            document.getElementById('iframeSelTrab').src = "";
            $('#modalSeleccionTrabajador').modal('hide');
        }

        //NG20240627 LLAMADA A GSBASE PARA COMPROBAR CONEXION
        function TestConexion() {
            //alert("(Conectaremos a: " + url_conexion + ")");
            var servidor_ip_publica = document.getElementById("servidor_ip_publica").value;
            var puerto = document.getElementById("puerto").value;
            var empresa_gestora = document.getElementById("empresa_gestora").value;
            var aplicacion = document.getElementById("aplicacion").value;
            var ejercicio = document.getElementById("ejercicio").value;
            var empresa_id = document.getElementById("empresa_id").value;
            var ventana_pref = document.getElementById("ventana_pref").value;
            var cd_pref_autogen = document.getElementById("cd_pref_autogen").value;
            var historico_activo = document.getElementById("historico_activo").value;

            if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                alert("Faltan valores de conexión, revise los parametros");

            } else {

                var arg = '';

                //alert(url_conexion);

                //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                var accion = "ejecutar_accion_gsb";
                var accion_gsb = "a_comprobar_conexion";

                //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + respuesta + ". ");

                //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                $("#overlay").fadeIn(300);

                $.ajax({
                    url: url_conexion,
                    //con esta url si llega a conectar con el php pero no llega a gsbase                    
                    //url: 'https://www.globalsystem.es/AppWeb/AppViajesDietas/funciones.php',
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
                            //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                            respuesta = $.parseJSON(response);
                            if (respuesta['resultado'] == "ok") {
                                alert("Conexión establecida correctamente!!!");
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
                        if (textstatus === "timeout") {
                            alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                        } else {
                            alert(textstatus);
                        }
                    }
                });
            }
        }

        function Mostrar_acciones_info() {
            alert(acciones_info);
        }

        function mostrar_pass() {
            var x = document.getElementById("password_login");
            var style = window.getComputedStyle(x);
            if (style.webkitTextSecurity == "square") {
                document.getElementById("password_login").style.webkitTextSecurity = 'none';
            } else {
                //x.setAttribute("webkitTextSecurity","square");
                document.getElementById("password_login").style.webkitTextSecurity = 'square';
            }
        }

        function mostrar_pass2() {

            var x = document.getElementById("password_crea_pass1");
            var style = window.getComputedStyle(x);
            if (style.webkitTextSecurity == "square") {
                document.getElementById("password_crea_pass1").style.webkitTextSecurity = 'none';
            } else {
                //x.setAttribute("webkitTextSecurity","square");
                document.getElementById("password_crea_pass1").style.webkitTextSecurity = 'square';
            }

            var x = document.getElementById("password_crea_pass2");
            var style = window.getComputedStyle(x);
            if (style.webkitTextSecurity == "square") {
                document.getElementById("password_crea_pass2").style.webkitTextSecurity = 'none';
            } else {
                //x.setAttribute("webkitTextSecurity","square");
                document.getElementById("password_crea_pass2").style.webkitTextSecurity = 'square';
            }
        }


        function Login() {
            trabajador_login = document.getElementById("trabajador_login").value;
            password_login = document.getElementById("password_login").value;
            var respuesta = "";

            if (trabajador_login == "") {
                document.getElementById("trabajador_login_invalid").style.display = 'block';
            } else {
                document.getElementById("trabajador_login_invalid").style.display = 'none';
                //alert("(Conectaremos a: " + url_conexion + ")");
                var servidor_ip_publica = document.getElementById("servidor_ip_publica").value;
                var puerto = document.getElementById("puerto").value;
                var empresa_gestora = document.getElementById("empresa_gestora").value;
                var aplicacion = document.getElementById("aplicacion").value;
                var ejercicio = document.getElementById("ejercicio").value;
                var empresa_id = document.getElementById("empresa_id").value;
                var ventana_pref = document.getElementById("ventana_pref").value;
                var cd_pref_autogen = document.getElementById("cd_pref_autogen").value;
                var historico_activo = document.getElementById("historico_activo").value;

                if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                    alert("Faltan valores de conexión, revise los parametros");

                } else {

                    var arg = '{' +
                        '"cod_trabajador" : "' + trabajador_login + '",' +
                        '"passw" : "' + password_login + '",' +
                        '"mac" : "",' +
                        '"version_pda" : "",' +
                        '"cdaplicacion" : ""' //Que no se nos olvide quitar la coma solo en el ult.element
                        +
                        '}';


                    //alert(url_conexion);

                    //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                    //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                    var accion = "ejecutar_accion_gsb";
                    var accion_gsb = "a_login";
                    //alert("Datos: " + servidor_ip_publica + ", " + puerto + ", " + empresa_gestora + ", " + aplicacion + ", " + ejercicio + ", " + empresa_id + ", " + ventana_pref + ", " + arg + ", " + accion + ", " + accion_gsb + ", " + cd_pref_autogen + ", " + respuesta + ". ");

                    //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                    $("#overlay").fadeIn(300);

                    $.ajax({
                        url: url_conexion,
                        //con esta url si llega a conectar con el php pero no llega a gsbase                    
                        //url: 'https://www.globalsystem.es/AppWeb/AppViajesDietas/funciones.php',
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
                            //alert(response);

                            //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                            $("#overlay").fadeOut(300);

                            //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                            //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                            //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                            try {
                                //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                                respuesta = $.parseJSON(response);
                                //alert(respuesta['resultado']);

                                if (respuesta['resultado'] == "ok") {
                                    //alert("Licencia de php: " + GetVariableLocalStorage("id_licencia_gsb_pref") + ", Licencia de gsBase: " + respuesta['licencia']);

                                    if (respuesta['licencia'] == GetVariableLocalStorage("id_licencia_gsb_pref")) {
                                        var trab_pref = GetVariableLocalStorage("trabajador_pref");
                                        if (trab_pref == "") {
                                            let text = "¿Quieres establecer el trabajador por defecto?";
                                            if (confirm(text) == true) {
                                                document.getElementById("trabajador_pref").value = trabajador_login;

                                                SetVariableLocalStorage("trabajador_pref", document.getElementById("trabajador_pref").value);

                                            }
                                        }

                                        //NG20240701 GUARDAMOS ANTES LOS VALORES QUE QUEREMOS PASAR EN EL SUBMIT, PARA ELLO
                                        //LOS METEMOS EN EL FORMULARIO.
                                        //alert(respuesta['datos'][0]);
                                        document.getElementById('nombre_trab_login').value = respuesta['datos'][0];
                                        document.getElementById('tipo_trab_login').value = respuesta['datos'][4];
                                        var crear_pass = respuesta['datos'][1];

                                        if (crear_pass == 1) {
                                            //alert("Deberemos ir a modal crear_pass");
                                            //No permitimos que el modal se cancele por clicar fuera
                                            $('#modalCrearPass').modal({
                                                backdrop: 'static'
                                            });
                                            //Abrimos modal de crear pass.
                                            $('#modalCrearPass').modal('show');
                                        } else {
                                            //NG20240630 IREMOS AL MAIN, DEBERIAMOS GUARDAR LOS DATOS DE CONEXION, HISTORICO, ETC.
                                            //alert("Accedemos a la aplicación");
                                            AccederAplicacion();
                                        }
                                    } else {
                                        alert("Licencia de app" + ids_app_android + "incorrecta. Contacte con soporte");
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
                            $("#overlay").fadeOut(300);

                            if (textstatus === "timeout") {
                                alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                            } else {
                                alert(textstatus);
                            }
                        }

                    });
                }
            }
        }

        function Crear_Pass() {

            trabajador_login = document.getElementById("trabajador_login").value;
            password_crea_pass1 = document.getElementById("password_crea_pass1").value;
            password_crea_pass2 = document.getElementById("password_crea_pass2").value;


            const reg = new RegExp(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/);

            if (!reg.test(password_crea_pass1)) {
                alert('La contraseña debe de mínimo ocho caracteres, al menos una letra mayúscula, una letra minúscula y un número.');
            } else if ((password_crea_pass1 == '') || (password_crea_pass1 != password_crea_pass2)) {
                document.getElementById("password_crea_pass_invalid2").style.display = 'block';
            } else {
                document.getElementById("password_crea_pass_invalid2").style.display = 'none';
                //alert("(Conectaremos a: " + url_conexion + ")");
                var servidor_ip_publica = document.getElementById("servidor_ip_publica").value;
                var puerto = document.getElementById("puerto").value;
                var empresa_gestora = document.getElementById("empresa_gestora").value;
                var aplicacion = document.getElementById("aplicacion").value;
                var ejercicio = document.getElementById("ejercicio").value;
                var empresa_id = document.getElementById("empresa_id").value;
                var ventana_pref = document.getElementById("ventana_pref").value;

                if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                    alert("Faltan valores de conexión, revise los parametros");

                } else {

                    var arg = '{' +
                        '"cod_trabajador" : "' + trabajador_login + '",' +
                        '"passw" : "' + password_crea_pass1 + '",' +
                        '"mac" : "",' +
                        '"version_pda" : "",' //Que no se nos olvide quitar la coma solo en el ult.element
                        +
                        '"cdaplicacion" : ""' //Que no se nos olvide quitar la coma solo en el ult.element
                        +
                        '}';

                    //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                    //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                    var accion = "ejecutar_accion_gsb";
                    var accion_gsb = "a_cambia_pin";


                    $.ajax({
                        url: url_conexion,
                        //con esta url si llega a conectar con el php pero no llega a gsbase                    
                        //url: 'https://www.globalsystem.es/AppWeb/AppViajesDietas/funciones.php',
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
                            "accion_gsb": accion_gsb
                        },
                        type: "POST",
                        //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                        timeout: 2000,
                        success: function(response) {
                            //alert(response);

                            //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                            //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                            //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                            try {
                                var respuesta = $.parseJSON(response);
                                //alert(respuesta['resultado']);

                                if (respuesta['resultado'] == "ok") {
                                    //Borramos los valores, cerramos el modal y accedemos a la app.
                                    document.getElementById("password_crea_pass1").value = "";
                                    document.getElementById("password_crea_pass2").value = "";

                                    $('#modalCrearPass').modal('hide');

                                    AccederAplicacion();

                                } else {
                                    alert(respuesta['datos']);
                                }


                            } catch (error) {
                                alert(error + "Resp. gsBase: " + response);
                            }

                        },
                        error: function(xmlhttprequest, textstatus, message) {
                            if (textstatus === "timeout") {
                                alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                            } else {
                                alert(textstatus);
                            }
                        }
                    });
                }
            }
        }

        function AccederAplicacion() {
            document.getElementById('login_form').submit();
        }
    </script>
    <!-- Firebase App (core) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase.js"></script>
    <!-- Firebase App (core) -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <!-- Firebase Messaging -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>
    <script src="./js/timer_notificaciones.js"></script>
</head>

<body id="page-top" onload="comprobar_uuid()">
    <!-- APP BAR-->
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" style="margin-bottom:10px;">Login</a>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="Mostrar_acciones_info()" href="#" title="Acciones gsBase que se usan">Info</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" onclick="passPref()" href="#" title="Preferencias de conexión">Preferencias</a></li>
                    <!--<li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contact</a></li>-->
                </ul>
            </div>
        </div>
    </nav>

    <!-- SPINNER DE CARGA -->
    <div id="overlay" style="z-index: 9000;">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>


    <!-- Portfolio Section-->
    <section class="page-section portfolio" id="portfolio">
        <div class="container d-flex align-items-center flex-column">
            <img src="assets/img/logo_gss.png" class="img-fluid" alt="Logo global system" style="margin-top: 5%;">
        </div>
        <div class="container" style="max-width: 600px;">
            <!-- Formulario Login -->
            <form action="main.php" id="login_form" name="login_form" style="margin-top: 40px;margin-left: 5%;margin-right: 5%;" method="post">
                <h2 style="text-align:center; color: #003061;">Partes Asistencia</h2>
                <!-- Name input-->
                <div class="container mt-3">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="trabajador_login" name="trabajador_login" type="text" placeholder="Usuario" autocomplete="username" required>
                            <label for="trabajador_login">Usuario</label>
                            <div class="invalid-feedback" id="trabajador_login_invalid">El usuario es obligatorio</div>
                        </div>

                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control contrasenia" id="password_login" name="password_login" type="text" placeholder="Contraseña" onkeypress="if (event.keyCode == 13) gestionarLogin()" autocomplete="current-password" maxlength="20" />
                            <label for="password_login">Contraseña</label>
                            <div class="invalid-feedback" id="password_login_invalid">La contraseña es obligatoria</div>
                        </div>
                        <!-- Selección de trabajador -->
                        <button class="btn btn-outline-secondary" type="button" style="margin-left: 20px; width: 80px; background: #003061" onclick="mostrar_pass()" title="Mostrar contraseña"><i class="fa-solid fa-eye" style="font-size: 2em; color: #FFFFFF;"></i></button>
                    </div>
                    <!--NG20240701 VALORES EXTRA QUE QUEREMOS PASAR CON EL SUBMIT DEL FORMULARIO-->
                    <!--DEPENDIENDO DE LA APP WEB, SE PASARAN UNOS U OTROS -->
                    <div class="input-group mb-3" style="display: none;">
                        <div class="form-floating">
                            <input class="form-control" id="nombre_trab_login" name="nombre_trab_login" type="text" placeholder="nombre_trab" />
                            <label for="nombre_trab_login">nombre_trab</label>
                        </div>
                    </div>
                    <div class="input-group mb-3" style="display: none;">
                        <div class="form-floating">
                            <input class="form-control" id="tipo_trab_login" name="tipo_trab_login" type="text" placeholder="tipo_trab" />
                            <label for="tipo_trab_login">tipo_trab</label>
                        </div>
                    </div>
                </div>
                <div class="container d-flex align-items-center flex-column">
                    <!-- Si no ponemos el type="button" hace submit siempre-->
                    <button type="button" class="btn btn-primary btn-xl" onclick="gestionarLogin()">Conectar</button>
                </div>
            </form>
        </div>
    </section>
    <!-- Footer-->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <!-- Footer Social Icons-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <a class="btn btn-outline-light btn-social mx-1" onclick="AbrirModalCompartirQR()" href="#" title="Compartir aplicación"><i class="fa fa-qrcode" aria-hidden="true" style="font-size: 1.5em;"></i></a>
                </div>
                <!-- Footer About Text-->
                <div class="col-lg-4">
                </div>
                <div class="col-lg-4">
                    <h4 class="text-uppercase mb-4">Versión 1.1.2</h4>
                </div>
            </div>
        </div>
    </footer>
    <!-- Modals-->
    <!-- Modal Codigo Preferences-->
    <div class="portfolio-modal modal fade" id="modalCodigoPref" tabindex="-1" aria-labelledby="modalCodigoPref" aria-hidden="true" style="z-index: 2000; top: 15%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCodigoPref">Código de licencia</h5>
                    <div class="modal-header border-0"></div>
                </div>
                <div class="modal-body text-center pb-5">
                    <div class="container">
                        <div class="row justify-content-center">

                            <div class="col-xl-12">
                                <!-- Masthead Avatar Image-->
                                <form id="form_autogen" style="margin-top: 40px;">
                                    <div class="container mt-3">
                                        <div class="input-group mb-3">
                                            <div class="form-floating">
                                                <!-- NG20240627 PONEMOS CODIGO ESPECIAL PORQUE LA PDA CON ESCANER FISICO HACE QUE SE DISPARE LA ACCION ANTES DE QUE EL CODIGO ESTÉ EN EL INPUT POR LO QUE TENDREMOS QUE VALIDAR PULSANDO EL BOTÓN -->
                                                <input class="form-control" id="cd_pref_autogen" name="cd_pref_autogen" type="text" placeholder="Código licencia" onkeypress="if (event.keyCode == 13) { return false; }" />
                                                <label for="cd_pref_autogen">Código de licencia</label>
                                            </div>

                                            <!-- NG20240623 FUNCIONA PERO LE TENEMOS QUE DAR PERMISO CADA VEZ QUE ENTRAMOS -->
                                            <!--<button class="btn btn-outline-secondary" type="button" style="margin-left: 20px; width: 60px; background: #CCEF68" data-bs-toggle="modal" data-bs-target="#modalEscaneoCodigo"><i class="fa-solid fa-barcode" style="font-size: 2em; color: #082883;"></i></button>-->

                                            <!-- NG20240623 probar en ios -->
                                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 20px; width: 60px; background: #CCEF68" onclick="abrirEscaneo2()"><i class="fa-solid fa-barcode" style="font-size: 2em; color: #082883;"></i></button>
                                        </div>
                                    </div>

                                </form>
                                <div class="container d-flex align-items-center flex-column">
                                    <!-- Submit Button-->
                                    <button class="btn btn-primary btn-xl" onclick="escribir_autogen_uuid()">Validar</button>
                                </div>
                                <br>
                                <p>* SI CAMBIA DE NAVEGADOR O DE DISPOSITIVO, TENDRÁ QUE VOLVER A INTRODUCIR EL CÓDIGO DE LICENCIA.</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal compartir QR-->
    <div class="portfolio-modal modal fade" id="modalcompartirqr" tabindex="-1" aria-labelledby="modalcompartirqr" aria-hidden="true" style="z-index: 1900;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body text-center pb-5">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <!-- Portfolio Modal - Title-->
                                <h4 class="portfolio-modal-title text-secondary text-uppercase mb-0">Compartir enlace</h4>
                                <!-- Icon Divider-->
                                <div class="divider-custom">
                                    <div class="divider-custom-line"></div>
                                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                    <div class="divider-custom-line"></div>
                                </div>
                                <!-- Portfolio Modal - Image-->

                                <div id="contenedorQR" class="container d-flex align-items-center flex-column"></div>
                                <br>
                                <a id="url_texto" href="" src=""></a>
                                <p>Escanee el código QR para compartir el enlace de la aplicación</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Preferencias-->
    <div class="modal fade" id="modalPreferences" tabindex="-1" role="dialog" aria-labelledby="modalPreferences" aria-hidden="true" style="z-index: 1600;">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle" onclick="VisOpcionesDesarrollador1()">Preferencias</h5>
                    <div class="modal-header border-0">
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" onclick="CerrarModalPref()"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- Formulario Preferencias-->
                    <form id="form_pref">
                        <p style="color: red;" onclick="VisOpcionesDesarrollador2()"><strong>Conexión con servidor</strong></p>

                        <div class="form-floating mb-3">
                            <input class="form-control" id="cd_pref_autogen_pref" type="text" placeholder="Código preferencias" disabled />
                            <label for="cd_pref_autogen_pref">Código preferencias</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="cd_pref_autogenButton" type="text" placeholder="Editar código preferencias" disabled />
                                <label for="cd_pref_autogenButton">Editar código preferencias...</label>
                            </div>

                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 5px; width: 90px; background: #003061; margin-bottom: 15px;" onclick="AbrirModalCodigoPref(false)"><i class="fa-solid fa-star" aria-hidden="true" style="font-size: 1.5em; color: #FFFFFF;"></i></button>

                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="servidor_ip_publica" type="text" placeholder="Servidor (dirección pública EXT. pref Datos)" />
                            <label for="servidor_ip_publica">Servidor (dirección pública)</label>
                        </div>
                        <div class="form-floating mb-3" style="display:none">
                            <input class="form-control" id="servidor_ip_privada" type="text" placeholder="Servidor (direccion privada INT. pref Wifi)" />
                            <label for="servidor_ip_privada">Servidor (dirección privada) NO UTILIZADA EN WEB</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="puerto" type="text" placeholder="Indicar puerto de conexión" />
                            <label for="puerto">Puerto</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="empresa_gestora" type="text" placeholder="Identificador de empresa gestora" />
                            <label for="empresa_gestora">Empresa gestora</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="aplicacion" type="text" placeholder="Identificador de aplicación" />
                            <label for="aplicacion">Aplicación</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="ejercicio" type="text" placeholder="Identificador de ejercicio" />
                            <label for="ejercicio">Ejercicio</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="trabajador_pref" type="text" placeholder="Usuario para login" />
                                <label for="trabajador_pref">Trabajador</label>
                            </div>

                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 5px; width: 90px; background: #003061; margin-bottom: 15px;" onclick="abrirModalSeleccionTrabPref()"><i class="fa-solid fa-user" aria-hidden="true" style="font-size: 1.5em; color: #FFFFFF;"></i></button>

                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="empresa_id" type="text" placeholder="Código de Empresa (phpmyadmin)" disabled />
                            <label for="empresa_id">Código de Empresa</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="cdaplicacion" type="text" placeholder="Código de aplicación" disabled />
                            <label for="cdaplicacion">Código de aplicación</label>
                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" type="text" id="input_test_con" placeholder="" disabled />
                                <label for="input_test_con">Test de conexión</label>
                            </div>

                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 5px; width: 90px; background: #003061; margin-bottom: 15px;" onclick="TestConexion()"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true" style="font-size: 1.5em; color: #FFFFFF;"></i></button>

                        </div>
                        <div class="input-group mb-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="qrbutton_pref" type="text" placeholder="Compartir App Mediante Código Qr" disabled />
                                <label for="qrbutton_pref">Compartir App</label>
                            </div>

                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 5px; width: 90px; background: #003061; margin-bottom: 15px;" onclick="AbrirModalCompartirQR()"><i class="fa fa-qrcode" aria-hidden="true" style="font-size: 1.5em; color: #FFFFFF;"></i></button>

                        </div>
                        <div id="pref_opciones_desarrollador" style="display: none;">
                            <!-- Preferencias de desarrollador-->
                            <p style="color: red;"><strong>Opciones de desarrollador</strong></p>

                            <div class="form-floating mb-3">
                                <div class="form-check">
                                    <input class="granCheckbox" type="checkbox" value="0" id="cbxModoDios" onchange="" style="margin-right: 5px;" />
                                    <label for="cbxModoDios"> Modo Dios</label>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="mostrar_obras" type="text" placeholder="Conexión con php" disabled />
                                <label for="mostrar_obras">Mostrar obras</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="id_licencia_gsb_pref" type="text" placeholder="Conexión con php" disabled />
                                <label for="id_licencia_gsb_pref">Código de licencia</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="forzar_cambio_pref" type="text" placeholder="Conexión con php" disabled />
                                <label for="forzar_cambio_pref">Forzar cambio</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="historico_activo" type="text" placeholder="Conexión con php" disabled />
                                <label for="historico_activo">Establece si conectaremos con php (historico, etc.)</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input class="form-control" id="ventana_pref" type="text" placeholder="Ventana de gsbase" disabled />
                                <label for="ventana_pref">Introduzca el nombre de la ventana en GSbase</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <!-- NG20240626 LOS OCULTAMOS PORQUE DE MOMENTO NO SABEMOS SI GUARDAREMOS LAS PREFERENCIAS
                            DESPUES DE EDITARLAS EL USUARIO -->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="display: none;">Close</button>
                    <button type="button" class="btn btn-primary" style="display: none;">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal escanear código (no se utiliza, la dejamos por si algún dia a alguien no le funciona la de escaneo2,
            pero esta tienes que seleccionar la camara y darle permisos siempre que vas a escanear-->
    <div class="modal fade" id="modalEscaneoCodigo" tabindex="-1" aria-labelledby="modalEscaneoCodigo" aria-hidden="true">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Escanee el código</h5>
                    <div class="modal-header border-0">
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <iframe src="escaneo.html" height="450px"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal escanear código2-->
    <div class="modal fade" id="modalEscaneoCodigo2" tabindex="-1" aria-labelledby="modalEscaneoCodigo2" aria-hidden="true" height="500px" style="z-index: 2200;">
        <div class="modal-dialog modal-l">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Escanee el código</h5>
                </div>
                <iframe id="escaneo2" src="" height="550px"></iframe>
            </div>
        </div>
    </div>


    <!-- Modal Crear Pass-->
    <div class="portfolio-modal modal fade" id="modalCrearPass" tabindex="-1" aria-labelledby="modalCrearPass" aria-hidden="true" style="z-index: 2000; top: 15%;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCodigoPref">Crear contraseña</h5>
                    <div class="modal-header border-0"></div>
                </div>
                <div class="modal-body text-center pb-5">
                    <div class="container">
                        <div class="row justify-content-center">

                            <div class="col-xl-12">
                                <!-- Masthead Avatar Image-->
                                <form id="crear_pass" style="margin-top: 40px;">
                                    <div class="container mt-3">

                                        <div class="input-group mb-3">
                                            <div class="form-floating">
                                                <input class="form-control contrasenia" id="password_crea_pass1" type="text" placeholder="Contraseña" autocomplete="new-password" maxlength="20" required />
                                                <label for="password_crea_pass1">Contraseña</label>
                                                <div class="invalid-feedback" id="password_crea_pass_invalid">La contraseña es obligatoria</div>
                                            </div>
                                            <button class="btn btn-outline-secondary" type="button" style="margin-left: 20px; width: 80px; background: #003061" onclick="mostrar_pass2()" title="Mostrar contraseñas"><i class="fa-solid fa-eye" style="font-size: 2em; color: #FFFFFF;"></i></button>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="form-floating">
                                                <input class="form-control contrasenia" id="password_crea_pass2" type="text" placeholder="Contraseña" onkeypress="if (event.keyCode == 13) Crear_Pass()" autocomplete="new-password" maxlength="20" required />
                                                <label for="password_crea_pass2">Repita la contraseña</label>
                                                <div class="invalid-feedback" id="password_crea_pass_invalid2">Las dos contraseñas no son iguales</div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                <div class="container d-flex align-items-center flex-column in-line">

                                    <!-- Si no ponemos el type="button" hace submit siempre-->
                                    <button type="button" class="btn btn-primary btn-xl" onclick="Crear_Pass()">Aceptar</button>
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
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <iframe id="iframeSelTrab" src="" height="550px"></iframe>
            </div>
        </div>
    </div>
    <!-- Modal de sincronización -->
    <div id="modalSync" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; display:flex; justify-content:center; align-items:center;">
        <div style="background:white; padding:20px; border-radius:10px; max-width:400px; text-align:center;">
            <h3>Sincronizando datos...</h3>
            <p id="mensajeSync">Iniciando...</p>
        </div>
    </div>
    <script type="text/javascript">
        cargaInicialLocalStorage();

        //NG20240711 SI TENEMOS VALORES YA GUARDADOS EN LA LOCALSTORAGE, LOS CARGAMOS.
        function cargaInicialLocalStorage() {
            var tt = GetVariableLocalStorage("trabajador_pref");
            document.getElementById("trabajador_pref").value = tt;
            document.getElementById("trabajador_login").value = tt;
            document.getElementById("id_licencia_gsb_pref").value = GetVariableLocalStorage("id_licencia_gsb_pref");
            document.getElementById("historico_activo").value = GetVariableLocalStorage("historico_activo");
            document.getElementById("servidor_ip_publica").value = GetVariableLocalStorage("servidor_ip_publica");
            document.getElementById("puerto").value = GetVariableLocalStorage("puerto");
            document.getElementById("empresa_gestora").value = GetVariableLocalStorage("empresa_gestora");
            document.getElementById("ventana_pref").value = GetVariableLocalStorage("ventana_pref");
            document.getElementById("aplicacion").value = GetVariableLocalStorage("aplicacion");
            document.getElementById("ejercicio").value = GetVariableLocalStorage("ejercicio");
            document.getElementById("empresa_id").value = GetVariableLocalStorage("empresa_id");
            document.getElementById("cdaplicacion").value = GetVariableLocalStorage("cdaplicacion");
        }
    </script>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
<script type="module">
    import {
        ejecutarSiHayConexion,
        db
    } from './offlineManager.js';

    function loginOnline() {
        window.Login(); // Llama a la función definida más arriba en el mismo index.php
    }

    // Esta función queda visible desde el botón onclick
    window.gestionarLogin = function() {
        ejecutarSiHayConexion(loginOnline, loginOffline);
    };


    // Función loginOffline mejorada y reutilizable
    async function loginOffline() {
        const inputUsuario = document.getElementById("trabajador_login")?.value.trim();
        const inputPassword = document.getElementById("password_login")?.value.trim();
        if (!inputUsuario || !inputPassword) {
            return {
                ok: false,
                mensaje: 'Debe introducir usuario y contraseña'
            };
        }

        try {


            const trabajador = await db.trabajadores.get(inputUsuario);
            if (!trabajador) {
                return {
                    ok: false,
                    mensaje: 'Trabajador no encontrado en modo offline'
                };
            }
            console.log(inputUsuario, inputPassword, trabajador);
            if (trabajador.password !== inputPassword) {
                return {
                    ok: false,
                    mensaje: 'Contraseña incorrecta'
                };
            } else {
                AccederAplicacion();
            }


        } catch (err) {
            return {
                ok: false,
                mensaje: 'Error en login offline: ' + err
            };
        }
    }
</script>
<script type="module">
    import {
        sincronizarDatosOffline
    } from './offlineManager.js';

    if (navigator.onLine) {
        sincronizarDatosOffline();
    }
</script>