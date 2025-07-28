// timer_notificaciones.js
// Firebase SDK
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDXux48KU--RLU6IZ7i0iBUGLZb6-Wxih4",
    authDomain: "prueba-notificaciones-global.firebaseapp.com",
    projectId: "prueba-notificaciones-global",
    storageBucket: "prueba-notificaciones-global.firebasestorage.app",
    messagingSenderId: "38937493830",
    appId: "1:38937493830:web:eed2c5374d80ae53392547",
    measurementId: "G-55RRV5ND0P"
};

const app = firebase.initializeApp(firebaseConfig);
//const analytics = getAnalytics(app);
const messaging = firebase.messaging();
 
// Detectar tipo de dispositivo
let tipo_dispositivo = "PC";
const ua = navigator.userAgent;
if (/Android/i.test(ua)) tipo_dispositivo = "AND";
else if (/iPhone|iPad|iPod/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)) tipo_dispositivo = "IOS";

// Solicitar permisos y obtener token
function solicitarPermisoNotificaciones() {
    navigator.serviceWorker.register('./firebase-messaging-sw.js')
    .then(function(registration) {
        console.log("Service Worker registrado con éxito:", registration);

        // Inicializa Firebase Messaging (ya debes haber hecho initializeApp antes)
        const messaging = firebase.messaging();

        //BIeniroSdMLS1HW3gGmvjyAFLGKdD912g7SxBAaa-IurKN3kf1Gpa_mHFhdeM3v2Jc7bXHU1j5fVDcZ-uCSsBo8
        //BDMPpgnVAZU9og1oHMkCd6vasSKlYDFeO4wtE4P-KfgymWwEsSSkPrCbLwyoieKMPwaZ8KbNxR5lp-seGabrI3I
        messaging.getToken({
            vapidKey: "BDMPpgnVAZU9og1oHMkCd6vasSKlYDFeO4wtE4P-KfgymWwEsSSkPrCbLwyoieKMPwaZ8KbNxR5lp-seGabrI3I", 
            serviceWorkerRegistration: registration
        }).then((token) => {
            if (token) {
                console.log("Token FCM:", token);
                SetVariableLocalStorage2("token_fcm_usuario", token);
            } else {
                console.warn("No se pudo obtener un token.");
            }
        }).catch((err) => {
            console.error("Error al obtener token:", err);
        });

    })
    .catch(function(err) {
        console.error("Error registrando el Service Worker:", err);
    });
}

// Mostrar notificación en primer plano
messaging.onMessage((payload) => {
    console.log("Mensaje recibido:", payload);
    const { title, body, icon } = payload.notification;
    new Notification(title, { body, icon });
});

// Timer para llamar a GSBase y mostrar notificación
setInterval(() => {
    console.log("Inicio de timer para accion gsbase de avisos.");
    //alert("EJECUTA TIMER");
    a_devolver_avisos2();
}, 1 * 60 * 1000); // cada 5 minutos

function GetVariableLocalStorage2(nombre_variable){
    //alert("llegaget");
    return localStorage.getItem(nombre_variable + "_" + cdappfijo);
}

function SetVariableLocalStorage2(nombre_variable, valor_variable){
    localStorage.setItem(nombre_variable + "_" + cdappfijo, valor_variable);
}

function showNotification2() {
    if (Notification.permission === "granted") {
        DistribuyeNot(); // Ya registrado
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                DistribuyeNot();
            }
        });
    }
}

function DistribuyeNot() {
    //alert("ENTRA EN ENVIO");
    // Este token lo puedes tener cargado desde el backend si el usuario ya lo guardó
    const tokenUsuario = GetVariableLocalStorage2("token_fcm_usuario");

    if (!tokenUsuario) {
        //alert("NO hAY TOKEN");
        console.log("NO hAY TOKEN");
        solicitarPermisoNotificaciones();
        return;
    } else {
        //alert("hAY TOKEN");
        console.log("hAY TOKEN");
        console.log("Token FCM en localStorage:", GetVariableLocalStorage2("token_fcm_usuario"));

        fetch("./enviar_notificacion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                token: tokenUsuario,
                titulo: "Nuevos Pedidos",
                cuerpo: "Tienes un nuevo pedido pendiente",
                url: "https://globalsystem.es/AppWeb/PortalCemento/avisos_bus.php"
            })
        })
        .then(resp => {
            console.log("Respuesta recibida:", resp);
            return resp.text();  // Leer como texto primero
        })
        .then(text => {
            console.log("Respuesta como texto:", text);
            try {
                const data = JSON.parse(text);  // Intentar parsear JSON
                console.log("Respuesta FCM:", data);
            } catch (error) {
                console.error("Error al procesar el JSON:", error);
                console.log("Respuesta del servidor:", text);  // Ver qué datos están llegando
            }
        })
        .catch(err => console.error("Error al enviar notificación:", err));


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
        //alert("Faltan valores de conexión, revise los parametros");
        console.log("Faltan valores de conexión, revise los parametros");
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
                console.log(response);

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
