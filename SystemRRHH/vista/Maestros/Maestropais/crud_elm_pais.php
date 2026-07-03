<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener ID de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar si el país existe
$check = $con->query("SELECT id_pais, Nombre FROM pais WHERE id_pais = $id");
if ($check->num_rows === 0) {
    header("Location: crud_pais.php");
    exit();
}

$pais = $check->fetch_object();

// Obtener lista de países para la tabla
$query = $con->query("SELECT id_pais, Nombre FROM pais");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar País</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
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
                <h1>Eliminar País</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- INCLUIR EL CONTROLADOR (solo procesa si viene del formulario) -->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_pais.php"); ?>

                <!-- Tarjeta de confirmación de eliminación -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">⚠️ Confirmar Eliminación</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h5>¿Estás seguro de eliminar este país?</h5>
                            <p><strong>ID:</strong> <?= $pais->id_pais ?></p>
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($pais->Nombre) ?></p>
                            <p class="text-danger">⚠️ Esta acción no se puede deshacer</p>
                        </div>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Sí, eliminar
                                </button>
                                <a href="crud_pais.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de países -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Listado de Países</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del País</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query->num_rows > 0): ?>
                                    <?php while($row = $query->fetch_assoc()): ?>
                                    <tr <?= ($row['id_pais'] == $id) ? 'class="table-danger"' : '' ?>>
                                        <td><?= $row['id_pais'] ?></td>
                                        <td><?= htmlspecialchars($row['Nombre']) ?></td>
                                        <td>
                                            <a href="crud_mdf_pais.php?id=<?= $row['id_pais'] ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <a href="crud_elm_pais.php?id=<?= $row['id_pais'] ?>" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No hay países registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="crud_pais.php" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>