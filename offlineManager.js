import Dexie from 'https://cdn.jsdelivr.net/npm/dexie@3.2.4/dist/dexie.mjs';
export const db = new Dexie("AppPartesAsistenciaDB");


// Definición de las tablas
db.version(6).stores({
  trabajadores: 'cdtb, tb_deno, tb_pass, tb_app',
  clientes: 'cdcl, cdcif, cl_deno, cl_pob, cl_prov, cl_tel, cl_ema, cl_fpag, cl_denofp',
  articulos: 'cdart, ar_deno, ar_ref, ar_bar, ar_pvp, ar_prv, ar_dprv',
  maquinas: 'cdmq, mq_desc,mq_ref, mq_cdp, mq_prv, mq_ccl, mq_clien, mq_gar, mq_fv, mq_obs, mq_man, mq_vdo, mq_es',
  partes: 'cdpt, pt_cdcl, pt_denocl, pt_del, pt_fec, pt_hav, pt_tna, pt_nmtn, pt_dsi, pt_fep, pt_hop, pt_est, pt_obs',
  acciones_pendientes: '++id, accion, fecha, hora, usuario'
});

export default db;

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


export async function guardarAccionPendiente(accion) {
  await db.acciones_pendientes.add({
    accion: accion.accion,
    argumentos: accion.argumentos,
    fecha: accion.fecha || new Date().toISOString().slice(0, 10),
    hora: accion.hora || new Date().toLocaleTimeString('es-ES'),
    usuario: accion.usuario
  });
}

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
    const res = await fetch(url_conexion, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams(params)
    });

    const text = await res.text();
    //console.log("🔍 Respuesta cruda del servidor:", text); // 🔴 Aquí verás "Error al recuperar..."

    const data = JSON.parse(text);
    //console.log("🔄 Respuesta de la API:", data);

    return data
  } catch (err) {
    throw `Error en acción ${accion_gsb}: ${err}`
  }
}

// Función para cargar las tablas en local tras traerlas de GSBase
export async function guardarTabla(nombreTabla, datos) {
  if (!db[nombreTabla]) {
    throw new Error(`❌ La tabla "${nombreTabla}" no existe en IndexedDB`);
  }

  await db[nombreTabla].clear();
  await db[nombreTabla].bulkAdd(datos);

  //console.log(`✅ Datos guardados en la tabla ${nombreTabla}:`, datos.length);
}

// Funciones para mostrar y ocultar el modal de sincronización informativo para la interfaz usuario
export function mostrarModalSincronizacion() {
  document.getElementById('modalSync').style.display = 'flex';
  actualizarMensajeModal('Comprobando preferencias...');
}

export function actualizarMensajeModal(mensaje) {
  const p = document.getElementById('mensajeSync');
  if (p) p.textContent = mensaje;
}

export function ocultarModalSincronizacion() {
  document.getElementById('modalSync').style.display = 'none';
}

function mostrarModalErrorSync() {
  const modalElement = document.getElementById('modalErrorSync');
  const modalInstance = new bootstrap.Modal(modalElement, {
    backdrop: 'static', // impide cerrar haciendo clic fuera
    keyboard: false      // impide cerrar con ESC
  });
  modalInstance.show();

  // Asegura foco tras mostrarlo (previene el warning y mejora accesibilidad)
  setTimeout(() => {
    document.getElementById('btnReintentarSync').focus();
  }, 300);
}


// Funciones para cargar trabajadores desde GSBase y guardarlos en IndexedDB
export async function cargarTrabajadoresDesdeGSBase() {
  try {
    const data = await ejecutarAccionGSB('a_leer_trabajadores');
    if (data.resultado === "ok") {
      const trabajadores = data.datos.filter(a => a.cdtb && a.cdtb.trim() !== '');

      await guardarTabla('trabajadores', trabajadores);
    } else {
      console.warn("⚠ Respuesta incorrecta:", data);
    }
  } catch (err) {
    console.error("❌ Error al cargar trabajadores:", err);
  }
}

// Funciones para cargar artículos desde GSBase y guardarlos en IndexedDB
export async function cargarArticulosDesdeGSBase() {
  try {
    const data = await ejecutarAccionGSB('a_leer_articulos');
    if (data.resultado === "ok") {
      const articulos = data.datos.filter(a => a.cdart && a.cdart.trim() !== '');


      guardarTabla('articulos', articulos);
      // Mostrar el número de artículos sincronizados
    } else {
      console.warn("⚠ Respuesta incorrecta:", data);
    }
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
    console.error("❌ Error al cargar partes:");
    if (err instanceof SyntaxError) {
      console.error("⚠️ La respuesta no es JSON válido.");
    }

    // Mostrar el mensaje completo (stacktrace)
    console.error(err.stack || err.message || err);
  }
}


const conexionlogin = false;
export function ejecutarSiHayConexion(fnOnline, fnOffline) {
  if (navigator.onLine) {
    console.log("🟢 Conexión disponible");
    if (typeof fnOnline === 'function') fnOnline();
  } else {
    console.log("🔴 Sin conexión");
    if (typeof fnOffline === 'function') fnOffline();
  }
}

// Función para sincronizar datos offline al cargar la aplicación
export async function sincronizarDatosOffline() {
  mostrarModalSincronizacion();
  actualizarMensajeModal('Paso 1: Comprobando preferencia offline_manual...');

  // Paso 1: comprobar preferencia offline_manual
  if (offline_manual !== 'S' && offline_manual !== 'A') {
    console.log('⛔ Modo offline no habilitado. Sincronización cancelada.');
    actualizarMensajeModal('Modo offline no activado. Abortando...');
    setTimeout(ocultarModalSincronizacion, 2000);
    return;
  }

  // Paso 2: comprobar conexión
  if (!navigator.onLine) {
    actualizarMensajeModal('Sin conexión. Abortando...');
    setTimeout(ocultarModalSincronizacion, 2000);
    console.log('❌ Sin conexión. No se puede sincronizar.');
    return;
  }

  // Paso 3: comprobar licencia GSBase
  try {
    actualizarMensajeModal('Paso 3: Verificando licencia con GSBase...');

    const res = await ejecutarAccionGSB('a_comprobar_conexion');
    const licenciaRemota = res['licencia'];
    if (licenciaRemota !== id_licencia_gsb) {
      actualizarMensajeModal('Licencia incorrecta. Abortando...');
      setTimeout(ocultarModalSincronizacion, 5000);
      console.log('🚫 Licencia no coincide. Sincronización denegada.');
      return;
    }

    // Paso 5: descargar datos y almacenarlos en IndexedDB

    actualizarMensajeModal('Paso 4: Descargando datos...');
    await cargarTrabajadoresDesdeGSBase();
    await cargarArticulosDesdeGSBase();
    await cargarClientesDesdeGSBase();
    await cargarMaquinasDesdeGSBase();
    await cargarPartesDesdeGSBase();

    //console.log('✅ Licencia validada. Procediendo a descargar datos...');
    const ahora = new Date();
    localStorage.setItem('ultima_sincronizacion', ahora.toISOString());
    console.log("📦 Última sincronización guardada:", ahora.toLocaleString());

    actualizarMensajeModal('Sincronización completa ✅');
    setTimeout(ocultarModalSincronizacion, 5000);

  } catch (error) {
    console.error("❌ Error en sincronización:", err);
    mostrarModalErrorSync();
    setTimeout(ocultarModalSincronizacion, 3000);
  }
}



// Función para hashear texto usando SHA-256
/* async function hashTexto(texto) {
  const encoder = new TextEncoder();
  const data = encoder.encode(texto);
  const hashBuffer = await crypto.subtle.digest('SHA-256', data);
  const hashArray = Array.from(new Uint8Array(hashBuffer));
  return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
} */

// Función auxiliar para guardar el hash de la contraseña (a llamar tras login online)
/* export async function guardarCredencialesOffline(cdtrabajador, password, tipo) {
  const hash = await hashTexto(password);
  localStorage.setItem("password_hash_" + cdtrabajador, hash);
  localStorage.setItem("tipo_" + cdtrabajador, tipo || "empleado");
} */

