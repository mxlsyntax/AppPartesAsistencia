<?php
// Puedes pasar el título desde la página donde incluyas esta cabecera:
$titulo = $titulo ?? 'Sin título';
?>

<!-- Cabecera reutilizable -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="./css/styles.css">
<header class="cabecera-azul text-white px-3 py-3 d-flex align-items-center justify-content-between">
  <button class="btn btn-link text-white p-0 me-2" onclick="window.history.back()">
    <i class="bi bi-arrow-left fs-4"></i>
  </button>

  <h4 class="m-0 flex-grow-1 text-center" style="font-weight: bold;"><?= htmlspecialchars($titulo) ?></h5>

  <button class="btn btn-link text-white p-0 ms-2" onclick="mostrarInfo()">
    <i class="bi bi-info-circle fs-4"></i>
  </button>
</header>

<script>
function mostrarInfo() {
  alert("ℹ Aquí puedes mostrar información de ayuda o instrucciones.");
}
</script>
