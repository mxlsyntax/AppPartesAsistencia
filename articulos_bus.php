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


    <title>Tabla Artículos</title>

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
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Tablas bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
    <!-- <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
            Icons bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Autorefresco bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>

    <!-- Export bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/libs/jsPDF/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/export/bootstrap-table-export.min.js"></script>

    <!-- El siguiente script es para que alterne automaticamente el tipo de vista de la tabla, si nota que no cogen
         todas las columnas ademas hay que establecer en la def html de la tabla lo siguiente: data-mobile-responsive="true" establece la vista que establecemos con toggle... si no coge la tabla completa -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table-locale-all.min.js"></script>
    <!-- Tu script con imports (debe ir como módulo) -->

<body>
    <?php
    $titulo = 'ARTÍCULOS'; // Cambia el título para cada vista
    include 'header.php';
    ?>
    <div class="table-responsive mt-4">
        <table id="tablaArticulos" class="table table-striped"
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
            data-show-footer="false"
            data-height="760"
            data-id-field="codigo"
            data-fixed-scroll="true"
            data-show-columns="true"
            data-show-columns-toggle-all="true">
            <thead>
                <tr>
                    <th data-field="cdart" data-sortable="true">Código</th>
                    <th data-field="ar_deno" data-sortable="true">Denominación</th>
                    <th data-field="ar_bar" data-sortable="true">Comercial</th>
                    <th data-field="ar_dprv" data-sortable="true">Proveedor</th>
                    <th data-field="ar_ref" data-sortable="true">Referencia</th>
                </tr>
            </thead>
        </table>
    </div>
</body>
<script type="module">
    import {
        cargarArticulosDesdeGSBase
    } from './onlineManager.js';
    import {
        db
    } from './offlineManager.js';

    async function inicializarTablaArticulos() {
        let respuesta;
        try {
            if (navigator.onLine) {
                console.log("🌐 Conectado a Internet, cargando datos desde GSBase...");
                let res = await cargarArticulosDesdeGSBase();
                respuesta = res.datos;

            } else {
                respuesta = await db.articulos.toArray();
                console.warn("🌐 Sin conexión a Internet, cargando datos locales...");
            }
            $('#tablaArticulos').bootstrapTable('destroy');
            $('#tablaArticulos').bootstrapTable({
                data: respuesta
            });

        } catch (err) {
            console.error("❌ Error al cargar artículos:", err);
        }
    }

    // Esperamos a que el DOM esté listo
    $(function() {
        inicializarTablaArticulos();
    });
</script>