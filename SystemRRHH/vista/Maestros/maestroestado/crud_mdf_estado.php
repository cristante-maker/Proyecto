<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener ID de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener datos del estado a modificar
$sql = $con->query("SELECT * FROM estado WHERE id_estado = $id");
$datos = $sql->fetch_object();

// Si no existe el estado, redirigir
if (!$datos) {
    header("Location: crud_estado.php");
    exit();
}

// Obtener lista de países para el select
$paises = $con->query("SELECT id_pais, Nombre FROM pais ORDER BY Nombre");

// Obtener lista de estados para la tabla
$query = $con->query("SELECT e.id_estado, e.Nombre as estado_nombre, p.Nombre as pais_nombre 
                      FROM estado e 
                      INNER JOIN pais p ON e.id_pais = p.id_pais 
                      ORDER BY p.Nombre, e.Nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Estado</title>
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
                <h1>Modificar Estado</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- Formulario de modificación -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Modificar Estado</h4>
                    </div>
                    <div class="card-body">
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_estado.php"); ?>
                        
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">País *</label>
                                    <select class="form-select" name="id_pais" required>
                                        <option value="">Seleccione un país</option>
                                        <?php while($pais = $paises->fetch_object()): ?>
                                            <option value="<?= $pais->id_pais ?>" <?= ($pais->id_pais == $datos->id_pais) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($pais->Nombre) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nombre del Estado *</label>
                                    <input type="text" class="form-control" name="nombre" placeholder="Ej: Carabobo" required value="<?= htmlspecialchars($datos->Nombre) ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-warning w-100" name="accion" value="modificar">
                                        <i class="bi bi-pencil"></i> Modificar
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?= $id ?>">
                        </form>
                    </div>
                </div>

                <!-- Tabla de estados -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Listado de Estados</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Estado</th>
                                    <th>País</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query->num_rows > 0): ?>
                                    <?php while($row = $query->fetch_object()): ?>
                                    <tr <?= ($row->id_estado == $id) ? 'class="table-warning"' : '' ?>>
                                        <td><?= $row->id_estado ?></td>
                                        <td><?= htmlspecialchars($row->estado_nombre) ?></td>
                                        <td><?= htmlspecialchars($row->pais_nombre) ?></td>
                                        <td>
                                            <a href="crud_mdf_estado.php?id=<?= $row->id_estado ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <a href="crud_elm_estado.php?id=<?= $row->id_estado ?>" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay estados registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <a href="crud_estado.php" class="btn btn-primary">
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