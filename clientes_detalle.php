<?php
$cdcli = $_GET['cdcli'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $cdcli ? "Editar Cliente $cdcli" : "Nuevo Cliente" ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>
<body class="p-4">
      <?php
    $titulo = 'DETALLE CLIENTE'; // Cambia el título para cada vista
    include 'header.php';
    ?>
  <h2><?= $cdcli ? "Editar Cliente" : "Nuevo Cliente" ?></h2>

  <form id="formCliente">
    <input type="hidden" name="cdcli" id="cdcli" value="<?= htmlspecialchars($cdcli) ?>">

    <div class="mb-3">
      <label for="cl_cif" class="form-label">CIF</label>
      <input type="text" class="form-control" id="cl_cif" name="cl_cif" required>
    </div>

    <div class="mb-3">
      <label for="cl_deno" class="form-label">Nombre / Razón Social</label>
      <input type="text" class="form-control" id="cl_deno" name="cl_deno" required>
    </div>

    <div class="mb-3">
      <label for="cl_fpag" class="form-label">Código Forma de Pago</label>
      <input type="text" class="form-control" id="cl_fpag" name="cl_fpag">
    </div>

    <div class="mb-3">
      <label for="cl_denofp" class="form-label">Forma de Pago</label>
      <input type="text" class="form-control" id="cl_denofp" name="cl_denofp">
    </div>

    <div class="mb-3">
      <label for="cl_obs" class="form-label">Observaciones</label>
      <textarea class="form-control" id="cl_obs" name="cl_obs" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="clientes_bus.php" class="btn btn-secondary">Volver</a>
  </form>
</body>
</html>
<script type="module">
    document.addEventListener('DOMContentLoaded', async () => {
  const cdcli = new URLSearchParams(window.location.search).get('cdcli');

  if (cdcli) {
    const cliente = await obtenerCliente(cdcli);
    if (cliente) {
      document.getElementById('cdcli').value = cdcli;
      document.getElementById('cl_cif').value = cliente.cl_cif || '';
      document.getElementById('cl_deno').value = cliente.cl_deno || '';
      document.getElementById('cl_fpag').value = cliente.cl_fpag || '';
      document.getElementById('cl_denofp').value = cliente.cl_denofp || '';
      document.getElementById('cl_obs').value = cliente.cl_obs || '';
    }
  }

  document.getElementById('formCliente').addEventListener('submit', async (e) => {
    e.preventDefault();

    const datos = {
      cdcli: document.getElementById('cdcli').value,
      cl_cif: document.getElementById('cl_cif').value,
      cl_deno: document.getElementById('cl_deno').value,
      cl_fpag: document.getElementById('cl_fpag').value,
      cl_denofp: document.getElementById('cl_denofp').value,
      cl_obs: document.getElementById('cl_obs').value
    };

    const res = await guardarCliente(datos);
    if (res.ok) {
      alert('✅ Cliente guardado correctamente');
      window.location.href = 'clientes_bus.php';
    } else {
      alert('❌ Error al guardar el cliente');
    }
  });
});

// Simulados (reemplaza con fetch real después)
async function obtenerCliente(cdcli) {
  // Simulación para prueba
  return {
    cl_cif: "B12345678",
    cl_deno: "Cliente Ejemplo S.L.",
    cl_fpag: "00",
    cl_denofp: "Contado",
    cl_obs: "Este cliente paga siempre al contado."
  };
}

async function guardarCliente(datos) {
  console.log("📝 Guardando cliente:", datos);
  return { ok: true };
}

</script>