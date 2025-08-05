<?php isset($titulo) ? $titulo : 'Sin título'; ?>
<?php isset($acciones) ? $acciones : 'No hay acciones definidas'; ?>


<!-- Cabecera reutilizable -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="./css/styles.css">
<header class="cabecera-azul text-white px-3 py-3 d-flex align-items-center justify-content-between">
  <button class="btn btn-link text-white p-0 me-2" onclick="window.history.back()">
    <i class="bi bi-arrow-left fs-4"></i>
  </button>

  <h4 class="m-0 flex-grow-1 text-center" style="font-weight: bold;"><?= htmlspecialchars($titulo) ?></h4>

  <button class="btn btn-link text-white p-0 ms-2" onclick="mostrarInfo()">
    <i class="bi bi-info-circle fs-4"></i>
  </button>
</header>

<script>
  function mostrarInfo() {
    <?php if (isset($acciones) && $acciones !== ''): ?>
      alert(<?= json_encode($acciones) ?>);
    <?php else: ?>
      alert("No se ha definido ninguna acción.");
    <?php endif; ?>
  }
</script>