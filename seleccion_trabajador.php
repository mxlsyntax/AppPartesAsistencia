<?php
    include("config.php"); //Para tener el id_app_fijo por ejemplo

    // Evitamos que tenga cache la pag
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    $vent = '';    
    if (isset($_REQUEST['vent'])) {
        $vent = $_REQUEST['vent'];
    }

    //echo "<script>";
    //echo "alert('La ventana es: " . $vent . "');";
    //echo "</script>";

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Para que no se guarde en la cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta name="author" content="NG">
    <meta name="description" content="Aplicación Web para registrar partes de asistencia" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- Con el manifest saldrá la pregunta, al menos en Chrome de si queremos instalarla-->
    <link rel="manifest" href="manifest.json">
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Tablas bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/bootstrap-table-locale-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
    <!-- <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
        Icons bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script type="text/javascript">
    </script>
</head>

<body>
    <main class="wrapper" style="padding-top:2em">
        <section class="container" id="demo-content">
            <div class="col-xl-7" align="center">
                <h4 id="no_hay_trab" style="display:;">No se han podido obtener los trabajadores.</h4>
                <table id="tableSelTrab" class="table table-striped" 
                    data-toggle="table" 
                    data-locale="es-ES" 
                    data-search="true" 
                    data-show-refresh="true"
                    data-show-toggle="true"
                    data-id-field="id"
                    data-ajax="a_leer_trabajadores">
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">Código</th>
                            <th data-field="denominacion" data-sortable="true">Denominación</th>
                        </tr>
                    </thead>
                </table>
                <script>
                const cdappfijo = "<?php echo $id_app_android; ?>";

                //ESTABLECEMOS ESTE CODIGO JAVASCRIPT DESPUES DE LA TABLA HTML PORQUE 
                //DE LO CONTRARIO NO SE EJECUTA BIEN.

                //NG20240711 TENEMOS QUE GESTIONAR LA SELECCION SEGUN DE DONDE VENGA.
                var vent = "<?php echo $vent; ?>";
                //alert("La ventana es: " + vent);

                //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                var $table = $('#tableSelTrab');

                var hostname = window.location.hostname;
                //NG20240628 PARA LAS CONEXIONES CON LA BD, PARA CUANDO HACEMOS PRUEBAS
                //EN LOCAL, NECESITAMOS LLAMAR A LO QUE HAY EN LA WEB, PORQUE SI NO NO TENEMOS ACCESO 
                //A LA BD DE PHPMYADMIN. El php para acceder a la base de datos del phpmyadmin tienen que estar
                //en el mismo lugar que la bd.
                //y para acceder a gsbase tiene que estar publico o tambien en el mismo sitio.

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

                function GetVariableLocalStorage(nombre_variable){
                    return localStorage.getItem(nombre_variable + "_" + cdappfijo);
                }

                //alert(cdaplicacion); 
                var tabla_datos = [];


                //https://examples.bootstrap-table.com/#view-source
                //NG20240709 LLAMADA A GSBASE PARA OBTENER TRABAJADORES
                function a_leer_trabajadores(params) {
                    tabla_datos = [];

                    if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
                        alert("Faltan valores de conexión, revise los parametros");

                    } else {

                        var arg = '{'
                                   +'"cdaplicacion" : "' + cdaplicacion + '"' //Que no se nos olvide quitar la coma solo en el ult.element
                                   +'}';

                        //NG20240701 DISTINGUIMOS ENTRE ACCION DE FUNCIONES.PHP Y ACCIONES_GSB PARA NO TENER QUE HACER
                        //UNA ACCION POR CADA UNA DE GSBASE QUE EXISTA.
                        var accion = "ejecutar_accion_gsb";
                        var accion_gsb = "a_leer_trabajadores";

                        //NG20240709 DAMOS VISIBILIDAD AL SPINNER DE CARGA
                        //$("#overlay").fadeIn(300);

                        $.ajax({
                            url: url_conexion,
                            //con esta url si llega a conectar con el php pero no llega a gsbase                    
                            //url: 'https://www.globalsystem.es/AppWeb/Base/funciones.php',
                            data: {"servidor_ip_publica": servidor_ip_publica, "puerto": puerto, "empresa_gestora": empresa_gestora, "aplicacion": aplicacion, "ejercicio": ejercicio, "empresa_id": empresa_id, "ventana_pref": ventana_pref, "arg": arg, "accion": accion, "accion_gsb": accion_gsb, "cd_pref_autogen": cd_pref_autogen, "historico_activo": historico_activo},
                            type: "POST",
                            //NG20240702 ESTABLECEMOS EL TIEMPO MAXIMO, PORQUE SI NO ES MUY LARGA Y PARECE QUE NO HA HECHO NADA
                            timeout: 2000,
                            success: function(response) {
                                //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                                //$("#overlay").fadeOut(300);

                                //NG20240716 CUIDADO SI LA RESPUESTA NO VIENE EN JSON, SE QUEDA PENSANDO
                                //SI NO ENVIAMOS CORRECTAMENTE EL JSON DE ARGUMENTOS U OTRO TAMBIEN
                                //DEVUELVE ERROR QUE NO SE VERIA DE NO SER POR EL TRY/CATCH
                                try {
                                    var respuesta = $.parseJSON(response);

                                    if (respuesta['resultado'] == "ok") {
                                        //NG20240701 GUARDAMOS ANTES LOS VALORES QUE QUEREMOS PASAR EN EL SUBMIT, PARA ELLO
                                        //LOS METEMOS EN EL FORMULARIO.
                                        //alert(respuesta['datos'].length);

                                        //https://stackoverflow.com/questions/37814493/how-to-load-json-data-into-bootstrap-table



                                        //Ahora tendriamos que cargar los elementos a la tabla.
                                        for (var i = 0; i < respuesta['datos'].length; i++) {
                                            //alert(respuesta['datos'][i][0]);                                    
                                            tabla_datos.push({
                                                id: respuesta['datos'][i][0],
                                                denominacion: respuesta['datos'][i][1]
                                            })
                                        }

                                        //alert('2'+JSON.stringify(tabla_datos));

                                        //NG20240710 CON ESTE COMANDO METEMOS LOS DATOS EN LA TABLA YA INICIALIZADA.
                                        //$table.bootstrapTable('append',tabla_datos);
                                        params.success(tabla_datos);


                                        document.getElementById('no_hay_trab').style.display = 'none';
                                        document.getElementById('tableSelTrab').style.display = '';
                                    } else {
                                        alert(respuesta['datos']);                                    
                                        document.getElementById('no_hay_trab').style.display = 'block';
                                        document.getElementById('tableSelTrab').style.display='none';
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

                                document.getElementById('no_hay_trab').style.display = 'block';
                                document.getElementById('tableSelTrab').style.display='none';
                            }
                        });
                    }
                }

                //Inicializado de la tabla
                $table.bootstrapTable({
                  data: [],
                  striped : true,
                  iconSize: "lg"
                });

                //DOCUMENTACION DE LAS SIGUIENTES TABLAS DE BOOTSTRAP EN EL SIGUIENTE ENLACE
                //https://examples.bootstrap-table.com/index.html#welcome.html#view-source
                $(function() {
                    //aqui irian los cammpos ocultos
                });

                //NG20240711 OBTENEMOS LA FILA QUE HAN SELECCIONADO
                $('#tableSelTrab').on('click-row.bs.table', function (row, $element, field) {
                  //alert(JSON.stringify($element));
                  var fila = $element;
                  var id_elemento = fila['id'];
                  var deno_elemento = fila['denominacion'];

                  //SI NO TENEMOS NINGÚN TRABAJADOR GUARDADO EN LAS PREFERENCIAS, PREGUNTAMOS SI QUEREMOS GUARDARLO.
                  if (vent == "login"){

                      //alert(id_elemento);
                      window.parent.document.getElementById("trabajador_login").value = id_elemento;
                    //alert("la ventana es login, preguntamos si está vacio, si queremos ponerlo en pref");

                    //NG20240711 SI EL TRAB DE PREFERENCIAS ESTA VACIO, PREGUNTAMOS SI LO QUEREMOS 
                    //ESTABLECER POR DEFECTO.
                    var trab_pref = localStorage.getItem("trabajador_pref");
                    if (trab_pref == ""){
                        let text = "¿Quieres establecer el trabajador por defecto?";
                        if (confirm(text) == true) {
                            window.parent.document.getElementById("trabajador_pref").value = id_elemento;
                            localStorage.setItem("trabajador_pref", id_elemento);
                        }
                    }

                  } else if (vent == "pref"){ 
                    //alert(id_elemento);
                    window.parent.document.getElementById("trabajador_login").value = id_elemento;                   
                    //alert("la ventana es pref, lo ponemos en pref.");
                    window.parent.document.getElementById("trabajador_pref").value = id_elemento;
                    //$('#modalSeleccionTrabajador').modal('hide');

                    localStorage.setItem("trabajador_pref", window.parent.document.getElementById("trabajador_pref").value);

                  } else if (vent == "viajes_detalle"){                  
                    //alert("la ventana es pref, lo ponemos en pref.");
                    window.parent.anadirTrab(id_elemento, deno_elemento);

                  } else { 
                    //alert(id_elemento);

                    window.parent.document.getElementById('cd_trabajador_bus').value = id_elemento;
                    window.parent.document.getElementById('deno_trabajador_bus').value = deno_elemento;
                    //alert("la ventana es pref, lo ponemos en pref.");

                  }

                  window.parent.cerrarModalSeleccionTrabajador();

                })

                </script>
            </div>
        </section>
    </main>
</body>

</html>