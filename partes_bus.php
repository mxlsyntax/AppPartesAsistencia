<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplicación Web para registrar dietas en viajes" />
    <meta name="author" content="NG" />

    <!-- Para acceso directo-->
    <meta name="apple-mobile-web-app-title" content="Viajes y Dietas">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="shortcut icon" sizes="16x16" href="/assets/favicon-16x16.png">
    <link rel="shortcut icon" sizes="192x192" href="/assets/icons/icon-192x192.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/mstile-150x150.png">

    <title>Tabla Partes</title>


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
    <!-- jQuery (solo uno) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <!-- Bootstrap Table CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.css">

    <!-- Bootstrap Table core -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>

    <!-- Idioma español -->
    <script src="https://unpkg.com/bootstrap-table@1.23.2/dist/locale/bootstrap-table-es-ES.min.js"></script>

    <!-- Extensión mobile (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>

    <!-- Extensión auto-refresh (si la usas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>

    <!-- Exportación -->
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <!-- Utilidades diversas, como gestion de fechas gsBase -->
    <script src="./js/utilidades.js"></script>

<body>
    <?php
    $titulo = 'PARTES DE ASISTENCIA'; // Cambia el título para cada vista
    $acciones = 'a_leer_partes';
    include 'header.php';
    ?>

    <!-- Filtros -->
    <div class="container mb-3">
        <select id="filtroCliente" class="form-select"></select>
        <select id="filtroMaquina" class="form-select"></select>
        <div class="col-md-3">
            <select id="filtroEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Finalizado">Finalizado</option>
            </select>
        </div>
    </div>
    <div class="container mt-4">
        <table id="tablaPartes"
            data-toggle="table"
            data-toolbar="#toolbar"
            data-locale="es-ES"
            data-search="true"
            data-show-refresh="true"
            data-show-toggle="false"
            data-show-fullscreen="true"
            data-mobile-responsive="true"
            data-auto-refresh="false"
            data-show-export="true"
            data-visible-search="true"
            data-click-to-select="true"
            data-sticky-header="true"
            data-show-footer="false"
            data-id-field="codigo"
            data-fixed-scroll="true"
            data-show-columns="true"
            data-show-columns-toggle-all="true">
            <thead>
                <tr>
                    <th data-field="cdpt" data-sortable="true">Código Parte</th>
                    <th data-field="pt_cdcl" data-sortable="true">Cod. Cliente</th>
                    <th data-field="pt_denocl" data-sortable="true">Denom. Cliente</th>
                    <th data-field="pt_del" data-sortable="true">Delegación Parte</th>
                    <th data-field="cl_del" data-sortable="true">Delegación Cliente</th>
                    <th data-field="pt_fec" data-sortable="true">Fecha</th>
                    <th data-field="pt_hav" data-sortable="true">Hora Av.</th>
                    <th data-field="pt_tna" data-sortable="true">Técnico</th>
                    <th data-field="pt_nmtn" data-sortable="true">Nombre Técnico</th>
                    <th data-field="pt_dsi" data-sortable="true">Descripción</th>
                    <th data-field="pt_fep" data-sortable="true">Fecha Fin</th>
                    <th data-field="pt_hop" data-sortable="true">Hora Fin</th>
                    <th data-field="pt_est" data-sortable="true">Estado</th>
                    <th data-field="pt_obs" data-sortable="true">Observaciones</th>
                    <th data-field="pt_lna" data-sortable="true">Línea</th>
                    <th data-field="pt_maq" data-sortable="true">Máquina</th>
                </tr>
            </thead>
        </table>
    </div>

    </div>
    <a href="#" class="floatGuardar" onclick="AltaParte()" style="display:;">Alta parte
        <i class="fa-regular fa-floppy-disk fa-2xl" title="Alta/Modificación de pedido"></i>
    </a>
</body>

<script type="module">
    import {
        cargarPartesDesdeGSBase,
        cargarMaquinasDesdeGSBase,
        cargarClientesDesdeGSBase
    } from './onlineManager.js';

    import {
        db
    } from './offlineManager.js';
    let datosOriginales = null;
    async function inicializarTablaPartes() {
        let respuesta;
        try {
            if (navigator.onLine) {
                let res = await cargarPartesDesdeGSBase();
                respuesta = res.datos;

            } else {
                respuesta = await db.partes.toArray();
                console.warn("🌐 Sin conexión a Internet, cargando datos locales...");
            }
            datosOriginales = respuesta;
            console.log("🌐 Datos de partes cargados:", datosOriginales);
            const partesFormateados = datosOriginales.map(p => ({
                ...p,
                pt_fec: (Num_aFecha(p.pt_fec))
            }));
            $('#tablaPartes').bootstrapTable('destroy');
            $('#tablaPartes').bootstrapTable({
                data: partesFormateados
            });
            // ✅ MUY IMPORTANTE: recalcular vista después de cargar
            setTimeout(() => {
                $('#tablaPartes').bootstrapTable('resetView');
            }, 10);
        } catch (err) {
            console.error("❌ Error al cargar partes:", err);
        }
    }

    // Esperamos a que el DOM esté listo
    $(function() {
        inicializarTablaPartes();
    });
    // Lógica del filtrado
    function aplicarFiltros() {
        const cliente = ($('#filtroCliente').val() || '').toLowerCase().trim();
        const maquina = ($('#filtroSerie').val() || '').toLowerCase().trim();
        const estado = ($('#filtroEstado').val() || '').toLowerCase().trim();
        console.log("🧪 Filtros:", {
            cliente,
            maquina,
            estado
        });
        const filtrados = datosOriginales.filter(p => {
            const pCliente = String(p.pt_denocl || '').toLowerCase().trim();
            const pMaquina = String(p.pt_maq || '').toLowerCase().trim();
            const pEstado = String(p.pt_est || '').toLowerCase().trim();

            return (!cliente || pCliente === cliente) &&
                (!maquina || pMaquina === maquina) &&
                (!estado || pEstado === estado);
        });

        console.log("🔎 Datos filtrados:", filtrados);

        console.log("📌 Valor seleccionado - cliente:", cliente);
        console.log("📌 Valor de pt_cdcl:", datosOriginales.map(p => p.pt_denocl));
        const partesFormateados = filtrados.map(p => ({
            ...p,
            pt_fec: (Num_aFecha(p.pt_fec))
        }));
        $('#tablaPartes').bootstrapTable('load', partesFormateados);
    }

    $('#tablaPartes').on('click-row.bs.table', function(e, row) {
        // Redirige al formulario con el código del parte como parámetro
        sessionStorage.setItem('parteSeleccionado', JSON.stringify(row));
        window.location.href = `partes_detalle.php?cdpt=${row.cdpt}`;
    });

    $(async function() {
        try {
            const clientes = await cargarClientesDesdeGSBase();
            const maquinas = await cargarMaquinasDesdeGSBase();
            console.log("🌐 Clientes cargados desde GSBase:", clientes);
            console.log("🌐 Máquinas cargadas desde GSBase:", maquinas);
            // Llenar select de clientes
            const $selectClientes = $('#filtroCliente');
            $selectClientes.empty().append(`<option value="">Todos los clientes</option>`);
            clientes.datos.forEach(c => {
                $selectClientes.append(`<option value="${c.cl_deno}">${c.cl_deno}</option>`);
            });

            // Llenar select de máquinas
            const $selectMaquinas = $('#filtroMaquina');
            $selectMaquinas.empty().append(`<option value="">Todas las máquinas</option>`);
            maquinas.datos.forEach(m => {
                const etiqueta = `${m.cdmq} - ${m.mq_desc || ""}`;
                $selectMaquinas.append(`<option value="${m.cdmq}">${etiqueta}</option>`);
            });
            $('#filtroCliente, #filtroSerie, #filtroEstado').on('change', aplicarFiltros);

        } catch (err) {
            console.error("❌ Error cargando datos desde GSBase:", err);
        }
    });

    // Función para crear un nuevo parte
    function AltaParte() {
        // Limpiar cualquier parte previamente seleccionado
        sessionStorage.removeItem('parteSeleccionado');
        // Redirigir a la página de detalle sin parámetros para crear uno nuevo
        window.location.href = 'partes_detalle.php';
    }

    // Hacer la función global para que pueda ser llamada desde el onclick
    window.AltaParte = AltaParte;
</script>