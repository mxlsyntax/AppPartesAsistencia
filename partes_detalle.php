<?php
$cdpar = isset($_GET['cdpar']) ? $_GET['cdpar'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Partes Asistencia</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />

    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Tablas bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
    <!-- Icons bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Autorefresco bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>

    <!-- Export bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>

    <!-- El siguiente script es para que alterne automaticamente el tipo de vista de la tabla -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table-locale-all.min.js"></script>

    <!-- Utilidades diversas, como gestion de fechas gsBase -->
    <script src="./js/utilidades.js"></script>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="./css/styles.css" rel="stylesheet" />
</head>

<body>
    <?php
    // Por defecto, título genérico
    $acciones = 'a_leer_partes, a_guardar_parte';

    $titulo = 'NUEVO PARTE';
    // Si existe una variable de sesión para el código del parte seleccionado, la usamos
    if (isset($_GET['cdpt'])) {
        $titulo = 'PARTE ' . $_GET['cdpt'];
    }

    // Incluimos el header
    include 'header.php';
    ?>
    <script>
        // Si existe en sessionStorage, pasamos el valor a PHP por AJAX o lo mostramos en el título
        document.addEventListener('DOMContentLoaded', function() {
            let parteSeleccionado = sessionStorage.getItem('parteSeleccionado');
            if (parteSeleccionado) {
                try {
                    let parte = JSON.parse(parteSeleccionado);
                    if (parte.cdpar) {
                        // Puedes mostrar el código en el título si quieres
                        let tituloElem = document.querySelector('h1, .titulo, #titulo');
                        if (tituloElem) {
                            tituloElem.textContent += ' (' + parte.cdpar + ')';
                        }
                        // O enviar el valor a PHP por AJAX si lo necesitas en el backend
                        fetch('guardar_cdpar_sesion.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'cdpar=' + encodeURIComponent(parte.cdpar)
                        });
                    }
                } catch (e) {}
            }
        });
    </script>
    <div class="container mt-2">
        <form id="formParte">
            <input type="hidden" name="cdpar" id="cdpar" value="<?= htmlspecialchars($cdpar) ?>">

            <!-- Cliente -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_cdcl" name="pt_cdcl" type="text" style="padding-left:8px" readonly>
                            <label for="pt_cdcl">Código Cliente</label>
                        </div>
                        <button class="btn btn-outline-secondary" type="button" id="botonSelCliente" style="width: 80px; background: #003061" onclick="abrirModalSeleccionCliente()" title="Selección de cliente">
                            <i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i>
                        </button>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_denocl" name="pt_denocl" type="text" style="padding-left:8px" readonly>
                            <label for="pt_denocl">Nombre Cliente</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delegación -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_del" name="pt_del" type="text" style="padding-left:8px">
                            <label for="pt_del">Delegación Parte</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="cl_del" name="cl_del" type="text" style="padding-left:8px" readonly>
                            <label for="cl_del">Delegación Cliente</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora Aviso -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_fec" name="pt_fec" type="date" style="padding-left:8px">
                            <label for="pt_fec">Fecha</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_hav" name="pt_hav" type="time" style="padding-left:8px">
                            <label for="pt_hav">Hora Aviso</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Técnico -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_tna" name="pt_tna" type="text" style="padding-left:8px" readonly>
                            <label for="pt_tna">Código Técnico</label>
                        </div>
                        <button class="btn btn-outline-secondary" type="button" id="botonSelTecnico" style="width: 80px; background: #003061" onclick="abrirModalSeleccionTecnico()" title="Selección de técnico">
                            <i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i>
                        </button>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_nmtn" name="pt_nmtn" type="text" style="padding-left:8px" readonly>
                            <label for="pt_nmtn">Nombre Técnico</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="pt_dsi" name="pt_dsi" style="height: 100px; padding-left:8px"></textarea>
                            <label for="pt_dsi">Descripción</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora Fin -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_fep" name="pt_fep" type="date" style="padding-left:8px">
                            <label for="pt_fep">Fecha Fin</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_hop" name="pt_hop" type="time" style="padding-left:8px">
                            <label for="pt_hop">Hora Fin</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <select class="form-control" id="pt_est" name="pt_est" style="padding-left:8px">
                                <option value="">Seleccionar estado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                            <label for="pt_est">Estado</label>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_lna" name="pt_lna" type="text" style="padding-left:8px">
                            <label for="pt_lna">Línea</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Máquina -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <input class="form-control" id="pt_maq" name="pt_maq" type="text" style="padding-left:8px" readonly>
                            <label for="pt_maq">Código Máquina</label>
                        </div>
                        <button class="btn btn-outline-secondary" type="button" id="botonSelMaquina" style="width: 80px; background: #003061" onclick="abrirModalSeleccionMaquina()" title="Selección de máquina">
                            <i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="row flex-wrap container-fluid">
                <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="pt_obs" name="pt_obs" style="height: 100px; padding-left:8px"></textarea>
                            <label for="pt_obs">Observaciones</label>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <!-- Botón flotante para guardar -->
    <a href="#" class="floatGuardar" onclick="guardarParte()" style="display: block;">Guardar
        <i class="fa-regular fa-floppy-disk fa-2xl" title="Guardar parte"></i>
    </a>

</body>

</html>
<script type="module">
    import {
        cargarPartesDesdeGSBase,
        cargarClientesDesdeGSBase,
        cargarMaquinasDesdeGSBase,
        //guardarParteEnGSBase
    } from './onlineManager.js';

    import {
        db
    } from './offlineManager.js';

    let parte = null;
    let esNuevo = false;

    document.addEventListener('DOMContentLoaded', async () => {
        // Verificar si hay datos en sessionStorage primero
        let parteJSON = sessionStorage.getItem('parteSeleccionado');

        if (parteJSON) {
            // Hay datos en sessionStorage, usarlos directamente
            parte = JSON.parse(parteJSON);
            await cargarDatosParte();
            esNuevo = false; // Es edición
        } else {
            // No hay datos en sessionStorage, es un parte nuevo
            esNuevo = true;
            inicializarNuevoParte();
            // El título ya está establecido como "Nuevo Parte"
        }

        document.getElementById('formParte').addEventListener('submit', async (e) => {
            e.preventDefault();
            await guardarParte();
        });
    });
    async function cargarParte(cdpar) {
        try {
            if (navigator.onLine) {
                const respuesta = await cargarPartesDesdeGSBase();
                const parteEncontrado = respuesta.datos.find(p => p.cdpar === cdpar);

                if (parteEncontrado) {
                    parte = parteEncontrado;
                    await cargarDatosParte();
                } else {
                    alert('Parte no encontrado');
                    window.location.href = 'partes_bus.php';
                }
            } else {
                // Buscar en IndexedDB
                const parteLocal = await db.partes.where('cdpar').equals(cdpar).first();
                if (parteLocal) {
                    parte = parteLocal;
                    await cargarDatosParte();
                } else {
                    alert('Parte no encontrado en datos locales');
                    window.location.href = 'partes_bus.php';
                }
            }
        } catch (error) {
            console.error('Error cargando parte:', error);
            alert('Error al cargar el parte');
        }
    }

    async function cargarDatosParte() {
        if (!parte) return;

        document.getElementById('cdpar').value = parte.cdpar || '';
        document.getElementById('pt_cdcl').value = parte.pt_cdcl || '';
        document.getElementById('pt_denocl').value = parte.pt_denocl || '';
        document.getElementById('pt_del').value = parte.pt_del || '';
        document.getElementById('cl_del').value = parte.cl_del || '';

        // Formatear fecha si viene como número
        if (parte.pt_fec) {
            const fecha = typeof parte.pt_fec === 'number' ? Num_aFecha(parte.pt_fec) : parte.pt_fec;
            document.getElementById('pt_fec').value = formatearFechaParaInput(fecha);
        }

        document.getElementById('pt_hav').value = parte.pt_hav || '';
        document.getElementById('pt_tna').value = parte.pt_tna || '';
        document.getElementById('pt_nmtn').value = parte.pt_nmtn || '';
        document.getElementById('pt_dsi').value = parte.pt_dsi || '';

        // Formatear fecha fin si viene como número
        if (parte.pt_fep) {
            const fechaFin = typeof parte.pt_fep === 'number' ? Num_aFecha(parte.pt_fep) : parte.pt_fep;
            document.getElementById('pt_fep').value = formatearFechaParaInput(fechaFin);
        }

        document.getElementById('pt_hop').value = parte.pt_hop || '';
        document.getElementById('pt_est').value = parte.pt_est || '';
        document.getElementById('pt_obs').value = parte.pt_obs || '';
        document.getElementById('pt_lna').value = parte.pt_lna || '';
        document.getElementById('pt_maq').value = parte.pt_maq || '';
    }

    function inicializarNuevoParte() {
        // Establecer fecha actual
        const hoy = new Date();
        document.getElementById('pt_fec').value = hoy.toISOString().split('T')[0];
        document.getElementById('pt_hav').value = hoy.toTimeString().slice(0, 5);
        document.getElementById('pt_est').value = 'Pendiente';
    }

    function formatearFechaParaInput(fecha) {
        if (!fecha) return '';
        // Si viene en formato dd/mm/yyyy, convertir a yyyy-mm-dd
        if (fecha.includes('/')) {
            const partes = fecha.split('/');
            return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
        }
        return fecha;
    }

    async function guardarParte() {
        try {
            const datos = {
                cdpar: document.getElementById('cdpar').value,
                pt_cdcl: document.getElementById('pt_cdcl').value,
                pt_denocl: document.getElementById('pt_denocl').value,
                pt_del: document.getElementById('pt_del').value,
                cl_del: document.getElementById('cl_del').value,
                pt_fec: Fecha_aNum(document.getElementById('pt_fec').value.split('-').reverse().join('/')),
                pt_hav: document.getElementById('pt_hav').value,
                pt_tna: document.getElementById('pt_tna').value,
                pt_nmtn: document.getElementById('pt_nmtn').value,
                pt_dsi: document.getElementById('pt_dsi').value,
                pt_fep: document.getElementById('pt_fep').value ? Fecha_aNum(document.getElementById('pt_fep').value.split('-').reverse().join('/')) : '',
                pt_hop: document.getElementById('pt_hop').value,
                pt_est: document.getElementById('pt_est').value,
                pt_obs: document.getElementById('pt_obs').value,
                pt_lna: document.getElementById('pt_lna').value,
                pt_maq: document.getElementById('pt_maq').value
            };

            // Validaciones básicas
            if (!datos.pt_cdcl) {
                alert('Debe seleccionar un cliente');
                return;
            }
            if (!datos.pt_fec) {
                alert('La fecha es obligatoria');
                return;
            }

            if (navigator.onLine) {
                // Guardar en GSBase
                const respuesta = await guardarParteEnGSBase(datos, esNuevo);
                if (respuesta.resultado === 'ok') {
                    alert('Parte guardado correctamente');
                    // Limpiar sessionStorage y volver a la lista
                    sessionStorage.removeItem('parteSeleccionado');
                    window.location.href = 'partes_bus.php';
                } else {
                    alert('Error al guardar: ' + respuesta.datos);
                }
            } else {
                // Guardar localmente
                if (esNuevo) {
                    datos.cdpar = 'LOCAL_' + Date.now();
                    await db.partes.add(datos);
                } else {
                    await db.partes.where('cdpar').equals(datos.cdpar).modify(datos);
                }
                alert('Parte guardado localmente (se sincronizará cuando haya conexión)');
                sessionStorage.removeItem('parteSeleccionado');
                window.location.href = 'partes_bus.php';
            }
        } catch (error) {
            console.error('Error guardando parte:', error);
            alert('Error al guardar el parte');
        }
    }

    // Funciones para modales de selección (implementar según necesidad)
    function abrirModalSeleccionCliente() {
        // TODO: Implementar modal de selección de cliente
        console.log('Abrir modal selección cliente');
    }

    function abrirModalSeleccionTecnico() {
        // TODO: Implementar modal de selección de técnico
        console.log('Abrir modal selección técnico');
    }

    function abrirModalSeleccionMaquina() {
        // TODO: Implementar modal de selección de máquina
        console.log('Abrir modal selección máquina');
    }

    // Hacer funciones globales
    window.guardarParte = guardarParte;
    window.abrirModalSeleccionCliente = abrirModalSeleccionCliente;
    window.abrirModalSeleccionTecnico = abrirModalSeleccionTecnico;
    window.abrirModalSeleccionMaquina = abrirModalSeleccionMaquina;
</script>