<?php
$cdmq = $_GET['cdmq'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title><?= $cdmq ? "Editar Máquina $cdmq" : "Nueva Máquina" ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
  <!-- <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
            Icons bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <!-- Autorefresco bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>


  <!-- Export bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>

  <!-- El siguiente script es para que alterne automaticamente el tipo de vista de la tabla, si nota que no cogen
         todas las columnas ademas hay que establecer en la def html de la tabla lo siguiente: data-mobile-responsive="true" establece la vista que establecemos con toggle... si no coge la tabla completa -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/mobile/bootstrap-table-mobile.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.2/dist/bootstrap-table-locale-all.min.js"></script>


  <!-- Utilidades diversas, como gestion de fechas gsBase -->
  <script src="./js/utilidades.js"></script>
  <!-- Core theme CSS (includes Bootstrap)-->
  <link href="./css/styles.css" rel="stylesheet" />

</head>

<body>
  <?php
  if (isset($_GET['cdmq'])) {
    $titulo = 'MÁQUINA ' . $_GET['cdmq'];
  }
  include 'header.php';
  ?>
  <div class="container mt-2">
    <form id="formMaquina">
      <input type="hidden" id="cdmq" name="cdmq" value="<?= htmlspecialchars($cdmq) ?>">

      <div class="mb-3">
        <label for="cdmq" class="form-label">Código Producto Base</label>
        <input type="text" class="form-control" id="cdmq" name="cdmq" disabled>
      </div>

      <div class="mb-3">
        <label for="mq_cdp" class="form-label">Especificaciones</label>
        <input type="text" class="form-control" id="mq_cdp" name="mq_cdp" disabled>
      </div>

      <div class="mb-3">
        <label for="mq_desc" class="form-label">Descripción</label>
        <input type="text" class="form-control" id="mq_desc" name="mq_desc" disabled>
      </div>

      <div class="mb-3">
        <label for="mq_gar" class="form-label">Garantía</label>
        <input type="text" class="form-control" id="mq_gar" name="mq_gar" disabled>
      </div>

      <div class="mb-3">
        <label for="mq_prv" class="form-label">Proveedor</label>
        <input type="text" class="form-control" id="mq_prv" name="mq_prv" disabled>
      </div>

      <div class="mb-3">
        <label for="maq_man" class="form-label">Mantenimiento</label>
        <input type="text" class="form-control" id="maq_man" name="maq_man" disabled>
      </div>
    </form>
  </div>

  <div class="container mb-3">
    <h4>Partes de asistencia</h4>
    <table id="tablaPartes"
      data-toggle="table"
      data-locale="es-ES"
      data-mobile-responsive="true"
      data-card-view="true"
      class="table table-bordered">
      <thead>
        <tr>
          <th data-field="cdpt" data-sortable="true">Código</th>
          <th data-field="pt_fec" data-sortable="true">Fecha</th>
          <th data-field="pt_hav">Hora</th>
          <th data-field="pt_maq">Máquina</th>
        </tr>
      </thead>
    </table>
  </div>

</body>

</html>
<script type="module">
  import {
    cargarPartesDesdeGSBase
  } from './onlineManager.js';
  import {
    db
  } from './offlineManager.js';
  let maquina = null;

  let maquinaJSON = sessionStorage.getItem('maquinaSeleccionado');
  maquina = JSON.parse(maquinaJSON);
  maquina.mq_gar = (Num_aFecha(maquina.mq_gar));

  document.addEventListener('DOMContentLoaded', async () => {

    if (maquina) {
      document.getElementById('cdmq').value = maquina.cdmq || '';
      document.getElementById('mq_cdp').value = maquina.mq_cdp || '';
      document.getElementById('mq_desc').value = maquina.mq_desc || '';
      document.getElementById('mq_gar').value = maquina.mq_gar || '';
      document.getElementById('mq_prv').value = maquina.mq_prv || '';
      document.getElementById('maq_man').value = maquina.maq_man || '';
    }


    document.getElementById('formMaquina').addEventListener('submit', async (e) => {
      e.preventDefault();

      const datos = {
        cdmq: document.getElementById('cdmq').value,
        mq_cdp: document.getElementById('mq_cdp').value,
        mq_desc: document.getElementById('mq_desc').value,
        mq_gar: document.getElementById('mq_gar').value,
        mq_prv: document.getElementById('mq_prv').value,
        maq_man: document.getElementById('maq_man').value,
      };
    });
    cargarPartesDelCliente();
  });
  async function cargarPartesDelCliente() {
    if (!maquina) {
      console.warn("⚠ No hay máquina cargada.");
      return;
    }

    const partes = await cargarPartesDesdeGSBase(maquina.cdmq);
    const partesFormateados = partes.datos.map(p => ({
      ...p,
      pt_fec: (Num_aFecha(p.pt_fec))
    }));

    $('#tablaPartes').bootstrapTable('load', partesFormateados);
    console.log("🌐 Partes cargados desde GSBase:", partesFormateados);
    $('#tablaPartes').bootstrapTable('hideLoading');

  }
</script>