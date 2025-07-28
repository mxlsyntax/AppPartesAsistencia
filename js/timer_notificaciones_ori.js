//NG20250506 JS QUE REALIZA LLAMADA A GSBASE REITERATIVA PARA 
//OBTENER EL NUMERO DE PEDIDOS DE CEMENTO NUEVO Y ENVIAR NOTIFICACION
//todas las constantes, variables e imports del php principal tambien funcionaran en este js.

//Codigo para timer.
const INTERVALO = 1 * 20 * 1000; // cada 5 minutos (ajusta a lo que necesites)

if (modo_dios){
    setInterval(() => {
        // Aquí pones tus condiciones
        const condicionesCumplidas = true; // Evalúa tu lógica

        if (condicionesCumplidas) {
            // Enviar notificación o hacer algo
            console.log("Inicio de timer para accion gsbase de avisos.");
            a_devolver_avisos2();
            // Puedes usar fetch() para notificar al servidor si hace falta
        }
    }, INTERVALO);
}




function GetVariableLocalStorage(nombre_variable){
    //alert("llegaget");
    return localStorage.getItem(nombre_variable + "_" + cdappfijo);
}


function showNotification2() {
  //  if(document.visibilityState === "visible") {
  //      return;
  //  }


    if (Notification.permission === "granted") {
        let title = "Pedidos nuevos";
        let icon = 'assets/icons/icon-96x96.png'; //this is a large image may take more time to show notifiction, replace with small size icon
        let body = "Hay nuevos pedidos que revisar";

        //let notification = new Notification(title, { body, icon });

        let notification = new Notification("Hola", { body: "Esto es una notificación" });

        notification.onclick = () => {
            notification.close();
            location.href ="avisos_bus.php";
        }   
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                let title = "Pedidos nuevos";
                let icon = 'assets/icons/icon-96x96.png'; //this is a large image may take more time to show notifiction, replace with small size icon
                let body = "Hay nuevos pedidos que revisar";

                //let notification = new Notification(title, { body, icon });

                let notification = new Notification("Hola", { body: "Esto es una notificación" });

                notification.onclick = () => {
                    notification.close();
                    location.href ="avisos_bus.php";
                }
            } else {
                alert("Hay nuevos pedidos que revisar");
            }
        });
    }   
}


//https://examples.bootstrap-table.com/#view-source
//NG20240709 LLAMADA A GSBASE PARA OBTENER TRABAJADORES
function a_devolver_avisos2() {
    var cd_trabajador_bus = cdtrabajador_login;
    //alert("Trabajador bus" + cd_trabajador_bus);
    //Tiramos de utilidades.js para no engrosar el codigo aqui.
    var fecha_ini = Fecha_aNum(format_fecha_local(formatDate(new Date(Date.now() - 30 * 86400000))));
    var fecha_fin = Fecha_aNum(format_fecha_local(formatDate(new Date(Date.now() + 7 * 86400000))));

    //alert(convertirTiempo(0));

    if ((servidor_ip_publica == "") | (puerto == "") | (empresa_gestora == "") | (aplicacion == "") | (ejercicio == "") | (empresa_id == "") | (ventana_pref == "")) {
        alert("Faltan valores de conexión, revise los parametros");
    } else {

        var arg = '{'
           +'"cdtrabajador" : "' + cd_trabajador_bus + '",'
           +'"cdresponsable" : "' + cdtrabajador_login + '",'
           +'"fechaDesde" : ' + fecha_ini + ','
           +'"fechaHasta" : ' + fecha_fin //Que no se nos olvide quitar la coma solo en el ult.element
           +'}';

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
            data: {"servidor_ip_publica": servidor_ip_publica, "puerto": puerto, "empresa_gestora": empresa_gestora, "aplicacion": aplicacion, "ejercicio": ejercicio, "empresa_id": empresa_id, "ventana_pref": ventana_pref, "arg": arg, "accion": accion, "accion_gsb": accion_gsb, "cd_pref_autogen": cd_pref_autogen, "historico_activo": historico_activo},
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
                        //alert(respuesta['datos']);

                        if (respuesta['datos'].length > 0) {
                            //VibrarCampana();
                            showNotification2();
                        }

                    } else {
                        //alert(respuesta['datos']);
                        console.log(respuesta['datos']);


                    }                                                
                } catch (error) {
                    //alert(error + "Resp. gsBase: " + response);
                    console.log(error + "Resp. gsBase: " + response);
                }
                
            },
            error: function(xmlhttprequest, textstatus, message) {

                //NG20240709 HAY QUE OCULTAR SPINNER DE CARGA
                //$("#overlay").fadeOut(300);

                if (textstatus === "timeout") {
                    //alert("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                    console.log("No se ha podido conectar con el servidor de gsbase, revise las preferencias.");
                } else {
                    //alert(textstatus);
                    console.log(textstatus);
                }

                //document.getElementById('tableHistorico').style.display='none';
            }
        });
    }
}