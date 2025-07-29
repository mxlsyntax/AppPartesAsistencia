export async function ejecutarAccionGSB(accion_gsb, arg = '{}') {
  const params = {
    ...conexionBase,
    arg,
    accion: 'ejecutar_accion_gsb',
    accion_gsb
  }

  try {
    const res = await fetch(urlGSBase, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams(params)
    })

    const text = await res.text()
    const data = JSON.parse(text)
    
    return data
  } catch (err) {
    throw `Error en acción ${accion_gsb}: ${err}`
  }
}

export async function obtenerTrabajadores() {
  try {
    const arg = JSON.stringify({
      cdaplicacion: conexionBase.aplicacion
    })

    const response = await ejecutarAccionGSB('a_leer_trabajadores', arg)

    if (response.resultado === 'ok') {
      return response.datos.map(p => ({
        cdtrabajador: p[0],
        nombre: p[1]
      }))
    } else {
      throw response.datos
    }
  } catch (err) {
    throw `Error al obtener trabajadores: ${err}`
  }
}
