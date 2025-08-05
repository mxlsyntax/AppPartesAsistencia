<?php
$cdcli = isset($_GET['cdcli']) ? $_GET['cdcli'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title><?= $cdcli ? "Editar Cliente $cdcli" : "Nuevo Cliente" ?></title>
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
  // Si existe una variable de sesión para el código del cliente seleccionado, la usamos
  if (isset($_GET['cdcli'])) {
    $titulo = 'CLIENTE ' . $_GET['cdcli'];
  }
  $acciones = 'a_leer_partesasis, a_leer_maquinas';
  include 'header.php';
  ?>
  <div class="container mt-2">
    <form id="formCliente">
      <input type="hidden" name="cdcli" id="cdcli" value="<?= htmlspecialchars($cdcli) ?>" disabled>

      <div class="row flex-wrap container-fluid">
        <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
          <div class="input-group mb-3">
            <div class="form-floating">
              <input class="form-control" id="cl_deno" name="cl_deno" type="text" style="padding-left:8px">
              <label for="cl_deno">Nombre / Razón Social</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row flex-wrap container-fluid">
        <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
          <div class="input-group mb-3">
            <div class="form-floating">
              <input class="form-control" id="cl_cif" name="cl_cif" type="text" style="padding-left:8px" disabled>
              <label for="cl_cif">CIF</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row flex-wrap container-fluid">
        <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
          <div class="input-group mb-3">
            <div class="form-floating">
              <input class="form-control" id="cl_fpag" name="cl_fpag" type="text" style="padding-left:8px" disabled>
              <label for="cl_fpag">Código Forma de Pago</label>
            </div>
          </div>
        </div>
      </div>
      <div class="row flex-wrap container-fluid">
        <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
          <div class="input-group mb-3">
            <div class="form-floating">
              <input class="form-control" id="cl_denofp" name="cl_denofp" type="text" style="padding-left:8px" disabled>
              <label for="cl_denofp">Forma de Pago</label>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid" id="div_obs_ant" ">
        <div class=" form-floating">
        <div class="row mb-6">
          <div class="col-xl-9" align="center" style="padding-left:8px">
            <div class="input-group mb-3">
              <div class="form-floating">
                <textarea class="form-control" id="cl_obs" name="cl_obs" type="text" placeholder="Observaciones" style="height: 168px;" readonly></textarea>
                <label for="cl_obs" class="form-label">Observaciones Ant.</label>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>

  <div>
    <!-- Sección con contador y botones -->
    <div class="row flex-wrap container-fluid">
      <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3 d-flex align-items-end">
          <div class="form-floating me-3">
            <div class="d-flex flex-wrap">
              <h4 class="text-start mb-1" style="color: #003061;">Partes:</h4>
              <h4 class="text-start mb-1" id="txt_num_partes" style="color: #2edc00; margin-left: 10px;">0</h4>
            </div>
            <hr class="m-0" style="color: #003061;border-top-width: 5px;opacity: 100;">
            <div class="form-floating">
            </div>
          </div>

          <button class="btn btn-outline-secondary" type="button" id="botonMostrarGridPartes" style="width: 80px; height: 80px; background: #003061" onclick="verGridPartes()" title="Mostrar partes"><i class="fa-solid fa-eye" style="font-size: 2em; color: #FFFFFF;"></i></button>

          <button class="btn btn-outline-secondary" type="button" id="botonOcultarGridPartes" style="width: 80px; height: 80px; background: #f97e66; display:none;" onclick="ocuGridPartes()" title="Ocultar partes"><i class="fa-solid fa-eye-slash" style="font-size: 2em; color: #FFFFFF;"></i></button>

          <button class="btn btn-outline-secondary" type="button" id="botonAnaPartes" style="width: 80px; height: 80px; background: #09de50" onclick="abrirModalSeleccionPartes()" title="Añadir parte"><i class="fa-solid fa-plus" style="font-size: 2em; color: #FFFFFF;"></i></button>
        </div>
      </div>
    </div>

    <!-- Tabla de partes con el mismo formato -->
    <div class="row flex-wrap container-fluid" id="div_gridPartes" style="display:none; border-radius: 10px; border-color: #003061; border-style: solid; padding: 5px; margin-bottom: 10px; align-content: center;">
      <section class="container-xxxl" id="demo-content-partes">
        <table id="tablaPartes" class="table table-striped table-sm"
          data-toggle="table"
          data-locale="es-ES"
          data-search="false"
          data-show-toggle="false"
          data-show-fullscreen="false"
          data-show-refresh="false"
          data-mobile-responsive="true"
          data-show-export="false"
          data-unique-id="cdpt"
          data-id-field="cdpt"
          data-click-to-select="true"
          data-checkbox-header="false"
          data-single-select="true"
          data-show-columns="false"
          data-show-columns-toggle-all="false"
          data-visible-search="true"
          data-group-by="true"
          data-sort-order="asc"
          data-fixed-scroll="true">
          <thead>
            <tr>
              <th data-field="state" data-checkbox="true"></th>
              <th data-field="cdpt" data-sortable="true">Código</th>
              <th data-field="pt_fec" data-sortable="true">Fecha</th>
              <th data-field="pt_hav">Hora</th>
              <th data-field="pt_maq">Máquina</th>
              <th data-field="pt_est">Estado</th>
            </tr>
          </thead>
        </table>
      </section>
    </div>
  </div>
  <div class="mb-3">
    <h4>Máquinas</h4>
    <table id="tablaMaquinas"
      data-toggle="table"
      data-mobile-responsive="true"
      data-card-view="true"
      data-locale="es-ES"
      class="table table-bordered">
      <thead>
        <tr>
          <th data-field="cdmq" data-sortable="true">Código</th>
          <th data-field="mq_desc" data-sortable="true">Descripción</th>
          <th data-field="mq_prv" data-sortable="true">Proveedor</th>
          <th data-field="mq_gar" data-sortable="true">Garantía</th>
        </tr>
      </thead>
    </table>
  </div>

  </form>
  </div>
</body>

</html>
<script type="module">
  import {
    cargarPartesDesdeGSBase,
    cargarMaquinasDesdeGSBase,
  } from './onlineManager.js';

  let cliente = null;

  let clienteJSON = sessionStorage.getItem('clienteSeleccionado');
  cliente = JSON.parse(clienteJSON);
  console.log('Cliente cargado:', cliente);
  document.addEventListener('DOMContentLoaded', async () => {

    if (cliente) {
      document.getElementById('cl_cif').value = cliente.cl_cif || '';
      document.getElementById('cl_deno').value = cliente.cl_deno || '';
      document.getElementById('cl_fpag').value = cliente.cl_fpag || '';
      document.getElementById('cl_denofp').value = cliente.cl_denofp || '';
      document.getElementById('cl_obs').value = cliente.cl_obs || '';
    }


    document.getElementById('formCliente').addEventListener('submit', async (e) => {
      e.preventDefault();

      const datos = {
        cdcli: document.getElementById('cdcl').value,
        cl_cif: document.getElementById('cl_cif').value,
        cl_deno: document.getElementById('cl_deno').value,
        cl_fpag: document.getElementById('cl_fpag').value,
        cl_denofp: document.getElementById('cl_denofp').value,
        cl_obs: document.getElementById('cl_obs').value
      };
    });
    cargarPartesDelCliente();
    cargarMaquinasDelCliente();
  });

  async function cargarPartesDelCliente() {
    if (!cliente) {
      console.warn("⚠ No hay cliente cargado.");
      return;
    }
    console.log('Cargando partes para el cliente:', cliente.cdcl);
    const partes = await cargarPartesDesdeGSBase(cliente.cdcl);
    console.log('Partes cargados:', partes);
    const partesFormateados = partes.datos.map(p => ({
      ...p,
      pt_fec: (Num_aFecha(p.pt_fec))
    }));

    $('#tablaPartes').bootstrapTable('load', partesFormateados);
    $('#tablaPartes').bootstrapTable('hideLoading');

    // Actualizar contador de partes
    document.getElementById('txt_num_partes').textContent = partesFormateados.length;
  }

  // Funciones para mostrar/ocultar grid de partes
  function verGridPartes() {
    document.getElementById('div_gridPartes').style.display = '';
    document.getElementById('botonMostrarGridPartes').style.display = 'none';
    document.getElementById('botonOcultarGridPartes').style.display = '';
  }

  function ocuGridPartes() {
    document.getElementById('div_gridPartes').style.display = 'none';
    document.getElementById('botonMostrarGridPartes').style.display = '';
    document.getElementById('botonOcultarGridPartes').style.display = 'none';
  }

  function abrirModalSeleccionPartes() {
    // TODO: Implementar modal para añadir nuevo parte
    console.log('Abrir modal para añadir parte');
  }

  // Hacer funciones globales
  window.verGridPartes = verGridPartes;
  window.ocuGridPartes = ocuGridPartes;
  window.abrirModalSeleccionPartes = abrirModalSeleccionPartes;
  async function cargarMaquinasDelCliente() {
    if (!cliente) {
      console.warn("⚠ No hay cliente cargado.");
      return;
    }

    const maquinas = await cargarMaquinasDesdeGSBase(cliente.cdcl);
    const maquinasFormateados = maquinas.datos.map(m => ({
      ...m,
      mq_gar: (Num_aFecha(m.mq_gar))
    }));

    $('#tablaMaquinas').bootstrapTable('load', maquinasFormateados);
    $('#tablaMaquinas').bootstrapTable('hideLoading');

  }
</script>