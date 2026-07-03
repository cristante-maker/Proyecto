<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener ID de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener datos del idioma a modificar
$sql = $con->query("SELECT * FROM idioma WHERE id_idioma = $id");
$datos = $sql->fetch_object();

// Si no existe el idioma, redirigir
if (!$datos) {
    header("Location: crud_idioma.php");
    exit();
}

// Obtener lista de idiomas para la tabla
$query = $con->query("SELECT id_idioma, Nombre FROM idioma ORDER BY Nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Idioma</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <nav class="sidebar">
            <h2>Sistema de Gestión</h2>
            <ul class="menu-list">
                <li class="menu-item"><a href="/SystemRRHH/vista/dashboard.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Dashboard.png" alt="Dashboard" class="menu-icon"></span> Administración</a></li>
                <li class="menu-item active"><a href="/SystemRRHH/vista/maestros.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Maestros.png" alt="Maestros" class="menu-icon"></span> Maestros</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/reclutamiento.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Reclutamiento.png" alt="Reclutamiento" class="menu-icon"></span> Reclutamiento</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/contratacion.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Contratacion.png" alt="Contratación" class="menu-icon"></span> Contratación</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/asistencia.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Asistencia.png" alt="Asistencia" class="menu-icon"></span> Asistencia</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/vacaciones.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Vacaciones.png" alt="Vacaciones" class="menu-icon"></span> Vacaciones</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/reposos.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Reposo.png" alt="Reposos" class="menu-icon"></span> Reposos</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/permisos.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Permiso.png" alt="Permisos" class="menu-icon"></span> Permisos</a></li>
                <li class="menu-item"><a href="/SystemRRHH/vista/configuracion.html"><span class="icon"><img src="/SystemRRHH/vista/Iconos/Configuracion.png" alt="Configuración" class="menu-icon"></span> Configuración</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="header">
                <h1>Modificar Idioma</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- Formulario de modificación -->
                <div class="section-card">
                    <h3>Modificar Idioma</h3>
                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_idioma.php"); ?>
                    
                    <form method="POST" action="">
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Nombre del Idioma *</label>
                                <input type="text" class="form-input" name="nombre" placeholder="Ej: Alemán" required value="<?= htmlspecialchars($datos->Nombre) ?>">
                            </div>
                        </div>
                        <div style="text-align: right; margin-top: 15px;">
                            <button type="submit" class="btn btn-warning" name="accion" value="modificar">
                                Modificar
                            </button>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                    </form>
                </div>

                <!-- Tabla de idiomas -->
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Idiomas</h2>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Idioma</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query->num_rows > 0): ?>
                                    <?php while($row = $query->fetch_object()): ?>
                                    <tr <?= ($row->id_idioma == $id) ? 'style="background: #fef3c7;"' : '' ?>>
                                        <td><?= $row->id_idioma ?></td>
                                        <td><?= htmlspecialchars($row->Nombre) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="crud_mdf_idioma.php?id=<?= $row->id_idioma ?>" class="action-btn action-btn-edit">Editar</a>
                                                <a href="crud_elm_idioma.php?id=<?= $row->id_idioma ?>" class="action-btn action-btn-delete" onclick="return confirm('¿Estás seguro de eliminar este idioma?')">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 30px;">No hay idiomas registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="crud_idioma.php" class="btn btn-secondary">Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>