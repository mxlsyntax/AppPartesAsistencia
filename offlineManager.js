import Dexie from 'https://cdn.jsdelivr.net/npm/dexie@3.2.4/dist/dexie.mjs';

export const db = new Dexie("ViajesDietasDB");

db.version(3).stores({
  trabajadores: 'cdtrabajador, nombre, password, tipo',
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


export async function guardarTrabajadores(trabajadores) {
  await db.trabajadores.clear();
  await db.trabajadores.bulkAdd(trabajadores);
}

export async function obtenerTodosLosTrabajadores() {
  return await db.trabajadores.toArray();
}

export async function obtenerTrabajadorPorCodigo(cdtrabajador) {
  return await db.trabajadores.get(cdtrabajador);
}

export async function cargarTrabajadoresDesdeGSBase() {
  if (
    !servidor_ip_publica || !puerto || !empresa_gestora ||
    !aplicacion || !ejercicio || !empresa_id || !ventana_pref
  ) {
    alert("Faltan valores de conexión");
    return;
  }


  const url_conexion = `http://localhost/AppWeb/AppPartesAsistencia/funciones.php`

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
    accion_gsb: "a_leer_trabajadores"
  };

  try {
    const res = await fetch(url_conexion, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams(params)
    });

    const text = await res.text();
    const data = JSON.parse(text);
    console.log("🔄 Respuesta de la API:", data);
    if (data.resultado === "ok") {
      const trabajadores = data.datos.map(row => ({
        cdtrabajador: row[0],
        nombre: row[1],
        password: row[2],
        tipo: row[3]
      }));

      await guardarTrabajadores(trabajadores);
    } else {
      console.warn("⚠ Respuesta incorrecta:", data);
    }
  } catch (err) {
    console.error("❌ Error al cargar trabajadores:", err);
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

export async function sincronizacionCompleta() {
  cargarTrabajadoresDesdeGSBase();
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

