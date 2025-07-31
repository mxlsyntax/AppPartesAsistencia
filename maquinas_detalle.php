<?php
$cdmq = $_GET['cdmq'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $cdmq ? "Editar Máquina $cdmq" : "Nueva Máquina" ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="p-4">
      <?php
    $titulo = 'DETALLE MÁQUINA'; // Cambia el título para cada vista
    include 'header.php';
    ?>
  <h2><?= $cdmq ? "Editar Máquina" : "Nueva Máquina" ?></h2>

  <form id="formMaquina">
    <input type="hidden" id="cdmq" name="cdmq" value="<?= htmlspecialchars($cdmq) ?>">

    <div class="mb-3">
      <label for="mqcdp" class="form-label">Código Producto Base</label>
      <input type="text" class="form-control" id="mqcdp" name="mqcdp">
    </div>

    <div class="mb-3">
      <label for="maq_es" class="form-label">Especificaciones</label>
      <input type="text" class="form-control" id="maq_es" name="maq_es">
    </div>

    <div class="mb-3">
      <label for="mq_gar" class="form-label">Garantía</label>
      <input type="text" class="form-control" id="mq_gar" name="mq_gar">
    </div>

    <div class="mb-3">
      <label for="mq_prv" class="form-label">Proveedor</label>
      <input type="text" class="form-control" id="mq_prv" name="mq_prv">
    </div>

    <div class="mb-3">
      <label for="maq_vdo" class="form-label">Última Venta</label>
      <input type="text" class="form-control" id="maq_vdo" name="maq_vdo">
    </div>

    <div class="mb-3">
      <label for="maq_man" class="form-label">Mantenimiento</label>
      <input type="text" class="form-control" id="maq_man" name="maq_man">
    </div>

    <div class="mb-3">
      <label for="mq_ccl" class="form-label">Cliente Asociado</label>
      <input type="text" class="form-control" id="mq_ccl" name="mq_ccl">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="maquinas_bus.php" class="btn btn-secondary">Volver</a>
  </form>

</body>
</html>
<script type="module">

document.addEventListener('DOMContentLoaded', async () => {
  const cdmaq = new URLSearchParams(window.location.search).get('cdmq');

  if (cdmaq) {
    const maq = await obtenerMaquina(cdmaq);
    if (maq) {
      document.getElementById('mqcdp').value = maq.mqcdp || '';
      document.getElementById('maq_es').value = maq.maq_es || '';
      document.getElementById('mq_gar').value = maq.mq_gar || '';
      document.getElementById('mq_prv').value = maq.mq_prv || '';
      document.getElementById('maq_vdo').value = maq.maq_vdo || '';
      document.getElementById('maq_man').value = maq.maq_man || '';
      document.getElementById('mq_ccl').value = maq.mq_ccl || '';
    }
  }

  document.getElementById('formMaquina').addEventListener('submit', async (e) => {
    e.preventDefault();

    const datos = {
      cdmq: document.getElementById('cdmq').value,
      mqcdp: document.getElementById('mqcdp').value,
      maq_es: document.getElementById('maq_es').value,
      mq_gar: document.getElementById('mq_gar').value,
      mq_prv: document.getElementById('mq_prv').value,
      maq_vdo: document.getElementById('maq_vdo').value,
      maq_man: document.getElementById('maq_man').value,
      mq_ccl: document.getElementById('mq_ccl').value
    };

    const res = await guardarMaquina(datos);
    if (res.ok) {
      alert('✅ Máquina guardada');
      window.location.href = 'maquinas_bus.php';
    } else {
      alert('❌ Error al guardar');
    }
  });
});

// Simulados
async function obtenerMaquina(cdmq) {
  // Reemplaza con fetch real
  return {
    mqcdp: 'PROD001',
    maq_es: 'Modelo X100',
    mq_gar: '2 años',
    mq_prv: 'Proveedor S.A.',
    maq_vdo: '2024-03-15',
    maq_man: '2025-01-01',
    mq_ccl: '000123'
  };
}

async function guardarMaquina(datos) {
  console.log("📝 Guardando máquina:", datos);
  return { ok: true };
}

</script>