<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener lista de países para el primer select
$paises = $con->query("SELECT id_pais, Nombre FROM pais ORDER BY Nombre");

// Obtener lista de ciudades con sus relaciones
$query = $con->query("SELECT c.id_ciudad, c.Nombre as ciudad_nombre, 
                      e.Nombre as estado_nombre, p.Nombre as pais_nombre 
                      FROM ciudad c 
                      INNER JOIN estado e ON c.id_estado = e.id_estado 
                      INNER JOIN pais p ON e.id_pais = p.id_pais 
                      ORDER BY p.Nombre, e.Nombre, c.Nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maestros Ciudad</title>
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
                <h1>Maestro Ciudad</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- Formulario de registro -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar Ciudad</h4>
                    </div>
                    <div class="card-body">
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_ciudad.php"); ?>
                        
                        <form method="POST" action="" id="formCiudad">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">País *</label>
                                    <select class="form-select" id="pais" name="pais" required>
                                        <option value="">Seleccione un país</option>
                                        <?php while($pais = $paises->fetch_object()): ?>
                                            <option value="<?= $pais->id_pais ?>">
                                                <?= htmlspecialchars($pais->Nombre) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estado *</label>
                                    <select class="form-select" id="estado" name="id_estado" required>
                                        <option value="">Primero seleccione un país</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nombre de la Ciudad *</label>
                                    <input type="text" class="form-control" name="nombre" placeholder="Ej: Valencia" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100" name="accion" value="registrar">
                                        <i class="bi bi-plus-circle"></i> Registrar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de ciudades -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">Listado de Ciudades</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ciudad</th>
                                    <th>Estado</th>
                                    <th>País</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query->num_rows > 0): ?>
                                    <?php while($row = $query->fetch_object()): ?>
                                    <tr>
                                        <td><?= $row->id_ciudad ?></td>
                                        <td><?= htmlspecialchars($row->ciudad_nombre) ?></td>
                                        <td><?= htmlspecialchars($row->estado_nombre) ?></td>
                                        <td><?= htmlspecialchars($row->pais_nombre) ?></td>
                                        <td>
                                            <a href="crud_mdf_ciudad.php?id=<?= $row->id_ciudad ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Editar
                                            </a>
                                            <a href="crud_elm_ciudad.php?id=<?= $row->id_ciudad ?>" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay ciudades registradas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript para cargar estados dinámicamente -->
    <script>
        document.getElementById('pais').addEventListener('change', function() {
            var paisId = this.value;
            var estadoSelect = document.getElementById('estado');
            
            // Limpiar select de estados
            estadoSelect.innerHTML = '<option value="">Cargando estados...</option>';
            
            if (paisId) {
                // Hacer petición AJAX para obtener los estados
                fetch('/SystemRRHH/controlador/get_estados.php?pais_id=' + paisId)
                    .then(response => response.json())
                    .then(data => {
                        estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';
                        data.forEach(function(estado) {
                            var option = document.createElement('option');
                            option.value = estado.id_estado;
                            option.textContent = estado.Nombre;
                            estadoSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        estadoSelect.innerHTML = '<option value="">Error al cargar estados</option>';
                    });
            } else {
                estadoSelect.innerHTML = '<option value="">Primero seleccione un país</option>';
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>
</html>