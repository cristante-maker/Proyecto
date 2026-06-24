<?php
// update_estado.php
$nombre  = base64_decode($_GET['b'] ?? '');
$pais_nombre = base64_decode($_GET['c'] ?? ''); // nombre del país (para mostrar)
$capital = base64_decode($_GET['d'] ?? '');

// Obtener lista de países para el select
include("SystemRRHH/modelo/conexion.php");
$paises = $conex->query("SELECT id, nombre FROM pais ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
// También necesitamos el ID del país actual para seleccionarlo en el <select>
$pais_actual_id = 0;
if ($pais_nombre) {
    $stmt = $conex->prepare("SELECT pais FROM pais WHERE nombre = ?");
    $stmt->execute([$pais_nombre]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) $pais_actual_id = $row['id'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estado</title>
    <link rel="stylesheet" href="../../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .crud-panel { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .crud-panel .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .crud-panel .form-group { margin-bottom: 15px; }
        .crud-panel label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; }
        .crud-panel .form-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .crud-buttons { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px; padding-top: 15px; border-top: 2px solid #dee2e6; }
        .crud-buttons .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; }
        .crud-buttons .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .btn-actualizar { background: #ffc107; color: #212529; }
        .btn-secondary { background: #6c757d; color: white; }
        .nav-panel { float: left; width: 15%; height: auto; padding: 20px 0; }
        .nav-panel a { display: block; padding: 10px 15px; margin: 5px 0; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
        .nav-panel a:hover { background: #007bff; color: white; }
        .nav-panel a.active { background: #007bff; color: white; }
        .content-right { float: right; width: 83%; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar (igual) -->
        <nav class="sidebar">...</nav>

        <main class="main-content clearfix">
            <header class="header">
                <h1>Editar Estado</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>

            <div class="nav-panel">...</div>

            <div class="content-right">
                <div class="section-card crud-panel">
                    <h3>Editar Estado</h3>
                    <?php if (!empty($nombre)): ?>
                    <form method="POST" action="../../../controlador/ctl_estado.php">
                        <input type="hidden" name="modificar" value="modificar">
                        <input type="hidden" name="nombre_actual" value="<?= htmlspecialchars($nombre) ?>">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre del Estado *</label>
                                <input type="text" class="form-input" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>País *</label>
                                <select class="form-input" name="pais_id" required>
                                    <option value="">Seleccione un país...</option>
                                    <?php foreach ($paises as $p): ?>
                                        <option value="<?= $p['id'] ?>" <?= ($p['id'] == $pais_actual_id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Capital</label>
                                <input type="text" class="form-input" name="capital" value="<?= htmlspecialchars($capital) ?>">
                            </div>
                        </div>
                        <div class="crud-buttons">
                            <button type="submit" class="btn btn-actualizar">💾 Actualizar Estado</button>
                            <a href="maestro_estado.php" class="btn btn-secondary">❌ Cancelar</a>
                        </div>
                    </form>
                    <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #dc3545;">
                        <h3>⚠️ No se encontró el estado</h3>
                        <p>Verifica que el nombre ingresado sea correcto.</p>
                        <a href="update_estado.html" class="btn btn-secondary">← Volver a buscar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>