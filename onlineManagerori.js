function GetVariableLocalStorage(nombre_variable) {
    //alert("llegaget");
    return localStorage.getItem(nombre_variable + "_" + '00212');
}


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
const id_licencia_gsb = GetVariableLocalStorage("id_licencia_gsb_pref");
const offline_manual = GetVariableLocalStorage("offline_manual");


// Función para ejecutar acciones en GSBase 
export async function ejecutarAccionGSB(accion_gsb, arg = '{}') {
    if (
        !servidor_ip_publica || !puerto || !empresa_gestora ||
        !aplicacion || !ejercicio || !empresa_id || !ventana_pref
    ) {
        alert("Faltan valores de conexión");
        return;
    }

    const url = `http://localhost/AppWeb/AppPartesAsistencia/${url_conexion}`;

    const params = {
        servidor_ip_publica: servidor_ip_publica,
        puerto: puerto,
        empresa_gestora: empresa_gestora,
        aplicacion: aplicacion,
        ejercicio: ejercicio,
        empresa_id: empresa_id,
        ventana_pref: ventana_pref,
        cd_pref_autogen: cd_pref_autogen,
        historico_activo: historico_activo,
        cdaplicacion: cdaplicacion,
        accion: "ejecutar_accion_gsb",
        accion_gsb: accion_gsb,
        arg: arg || '{}'
    };

    try {
        const res = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(params)
        });

        const json = await res.json();
        return json;        //console.log("🔍 Respuesta cruda del servidor:", text); // 🔴 Aquí verás "Error al recuperar..."

        //console.log("🔄 Respuesta de la API:", data);

        return data
    } catch (err) {
        throw `Error en acción ${accion_gsb}: ${err}`
    }
}



// Función para cargar trabajadores desde GSBase y guardarlos en IndexedDB
export async function cargarTrabajadoresDesdeGSBase() {
    try {
        const data = await ejecutarAccionGSB('a_leer_trabajadores');

        if (data.resultado === "ok") {
            const trabajadoresCrudos = data.datos.filter(a => a.cdtb && a.cdtb.trim() !== '');

            // Hasheamos solo si hay contraseña
            const trabajadores = await Promise.all(trabajadoresCrudos.map(async t => {
                const password = t.tb_pass?.trim();
                const hashedPass = password ? await hashPassword(password) : '';

                return {
                    ...t,
                    tb_pass: hashedPass
                };
            }));

            await guardarTabla('trabajadores', trabajadores);
            //console.log("✅ Trabajadores guardados en IndexedDB:", trabajadores.length);
        } else {
            console.warn("⚠ Respuesta incorrecta:", data);
        }
    } catch (err) {
        ocultarModalSincronizacion();
        mostrarModalErrorSync();
        console.error("❌ Error al cargar trabajadores:", err);
    }
}

// Funciones para cargar artículos desde GSBase y guardarlos en IndexedDB
export async function cargarArticulosDesdeGSBase() {
    try {
        const data = await ejecutarAccionGSB('a_leer_articulos');
        return data;
    } catch (err) {
        console.error("❌ Error al cargar artículos:");
        if (err instanceof SyntaxError) {
            console.error("⚠️ La respuesta no es JSON válido.");
        }

        // Mostrar el mensaje completo (stacktrace)
        console.error(err.stack || err.message || err);
    }
}

// Funciones para cargar clientes desde GSBase y guardarlos en IndexedDB
export async function cargarClientesDesdeGSBase() {
    try {
        const data = await ejecutarAccionGSB('a_leer_clientes');
        if (data.resultado === "ok") {
            const clientes = data.datos.filter(c => c.cdcl && c.cdcl.trim() !== '');

            await guardarTabla('clientes', clientes);
        } else {
            console.warn("⚠ Respuesta incorrecta:", data);
        }
    } catch (err) {
        ocultarModalSincronizacion();
        mostrarModalErrorSync();
        console.error("❌ Error al cargar clientes:");
        if (err instanceof SyntaxError) {
            console.error("⚠️ La respuesta no es JSON válido.");
        }

        // Mostrar el mensaje completo (stacktrace)
        console.error(err.stack || err.message || err);
    }
}

// Funciones para cargar maquinas desde GSBase y guardarlos en IndexedDB
export async function cargarMaquinasDesdeGSBase() {
    try {
        const data = await ejecutarAccionGSB('a_leer_maquinas');
        if (data.resultado === "ok") {
            const maquinas = data.datos.filter(m => m.cdmq && m.cdmq.trim() !== '');

            //console.log("🔄 Maquinas obtenidas:", maquinas);
            await guardarTabla('maquinas', maquinas);
        } else {
            console.warn("⚠ Respuesta incorrecta:", data);
        }
    } catch (err) {
        console.error("❌ Error en sincronización:", err);
        ocultarModalSincronizacion();
        mostrarModalErrorSync();
        console.error("❌ Error al cargar maquinas:");
        if (err instanceof SyntaxError) {
            console.error("⚠️ La respuesta no es JSON válido.");
        }

        // Mostrar el mensaje completo (stacktrace)
        console.error(err.stack || err.message || err);
    }
}

//Calcular estados maquinas
/* function traducirEstado(maq_es) {
  switch (maq_es) {
    case "0": return "Normal en funcionamiento";
    case "1": return "Averiada en cliente";
    case "2": return "Enviada a nuestro SAT";
    case "3": return "Enviada a SAT del fabricante";
    default: return "Desconocido";
  }
}

function traducirVenta(maq_vdo) {
  switch (maq_vdo) {
    case "S": return "Vendido";
    case "N": return "No vendido";
    case "A": return "Alquilado";
    default: return "Desconocido";
  }
} */

// Funciones para cargar partes de asistencia desde GSBase y guardarlos en IndexedDB
export async function cargarPartesDesdeGSBase() {
    try {
        const data = await ejecutarAccionGSB('a_leer_partesasis');

        if (data.resultado === "ok") {
            const partes = data.datos.filter(p => p.cdpt && p.cdpt.trim() !== '');

            //console.log("📦 Partes obtenidos:", partes);

            await guardarTabla('partes', partes);
        } else {
            console.warn("⚠ Respuesta incorrecta al cargar partes:", data);
        }

    } catch (err) {
        ocultarModalSincronizacion();
        mostrarModalErrorSync();
        console.error("❌ Error al cargar partes:");
        if (err instanceof SyntaxError) {
            console.error("⚠️ La respuesta no es JSON válido.");
        }

        // Mostrar el mensaje completo (stacktrace)
        console.error(err.stack || err.message || err);
    }
}
