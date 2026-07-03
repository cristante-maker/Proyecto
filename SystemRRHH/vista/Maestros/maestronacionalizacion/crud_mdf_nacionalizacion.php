<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener cédula de la URL
$ced = isset($_GET['ced']) ? trim($_GET['ced']) : '';

// Obtener datos de la nacionalización
$sql = $con->query("SELECT n.*, e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido
                    FROM nacionalizacion n
                    INNER JOIN empleado e ON n.Ced_Empleado = e.Cedula
                    WHERE n.Ced_Empleado = '$ced'");
$datos = $sql->fetch_object();

// Si no existe, redirigir
if (!$datos) {
    header("Location: crud_nacionalizacion.php");
    exit();
}

// Obtener lista de empleados para el combo box (excluyendo el actual si tiene nacionalización)
$empleados = $con->query("SELECT Cedula, Primer_Nombre, Segundo_Nombre, Primer_apellido, Segundo_apellido 
                          FROM empleado 
                          WHERE Cedula NOT IN (SELECT Ced_Empleado FROM nacionalizacion WHERE Ced_Empleado != '$ced')
                          ORDER BY Primer_apellido, Primer_Nombre");

// Obtener lista de nacionalizaciones para la tabla
$query = $con->query("SELECT n.Ced_Empleado, n.Numero_gaceta, n.Fecha_gaceta, n.Pais_procedente,
                      e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido
                      FROM nacionalizacion n
                      INNER JOIN empleado e ON n.Ced_Empleado = e.Cedula
                      ORDER BY n.Ced_Empleado");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Nacionalización</title>
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
                <h1>Modificar Nacionalización</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- Formulario de modificación -->
                <div class="section-card">
                    <h3>Modificar Nacionalización</h3>
                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_nacionalizacion.php"); ?>
                    
                    <form method="POST" action="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Seleccionar Empleado *</label>
                                <select class="form-input" name="ced_empleado" required>
                                    <option value="">-- Seleccione un empleado --</option>
                                    <?php 
                                    // Primero mostrar el empleado actual
                                    $nombre_actual = $datos->Primer_apellido;
                                    if ($datos->Segundo_apellido) $nombre_actual .= ' ' . $datos->Segundo_apellido;
                                    $nombre_actual .= ', ' . $datos->Primer_Nombre;
                                    if ($datos->Segundo_Nombre) $nombre_actual .= ' ' . $datos->Segundo_Nombre;
                                    ?>
                                    <option value="<?= $datos->Ced_Empleado ?>" selected>
                                        <?= htmlspecialchars($nombre_actual) ?> (<?= $datos->Ced_Empleado ?>)
                                    </option>
                                    
                                    <?php while($emp = $empleados->fetch_object()): ?>
                                        <?php 
                                            $nombre_completo = $emp->Primer_apellido;
                                            if ($emp->Segundo_apellido) $nombre_completo .= ' ' . $emp->Segundo_apellido;
                                            $nombre_completo .= ', ' . $emp->Primer_Nombre;
                                            if ($emp->Segundo_Nombre) $nombre_completo .= ' ' . $emp->Segundo_Nombre;
                                        ?>
                                        <option value="<?= $emp->Cedula ?>">
                                            <?= htmlspecialchars($nombre_completo) ?> (<?= $emp->Cedula ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Número de Gaceta *</label>
                                <input type="text" class="form-input" name="numero_gaceta" value="<?= htmlspecialchars($datos->Numero_gaceta) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Gaceta *</label>
                                <input type="date" class="form-input" name="fecha_gaceta" value="<?= $datos->Fecha_gaceta ?>" required>
                            </div>
                            <div class="form-group">
                                <label>País de Procedencia *</label>
                                <input type="text" class="form-input" name="pais_procedente" value="<?= htmlspecialchars($datos->Pais_procedente) ?>" required>
                            </div>
                        </div>
                        <div style="text-align: right; margin-top: 15px;">
                            <input type="hidden" name="ced_original" value="<?= $ced ?>">
                            <button type="submit" class="btn btn-warning" name="accion" value="modificar">
                                Modificar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de nacionalizaciones -->
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Nacionalizaciones</h2>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Empleado</th>
                                    <th>N° Gaceta</th>
                                    <th>Fecha Gaceta</th>
                                    <th>País Procedencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($query->num_rows > 0): ?>
                                    <?php while($row = $query->fetch_object()): ?>
                                    <?php 
                                        $nombre_emp = $row->Primer_apellido;
                                        if ($row->Segundo_apellido) $nombre_emp .= ' ' . $row->Segundo_apellido;
                                        $nombre_emp .= ', ' . $row->Primer_Nombre;
                                        if ($row->Segundo_Nombre) $nombre_emp .= ' ' . $row->Segundo_Nombre;
                                    ?>
                                    <tr <?= ($row->Ced_Empleado == $ced) ? 'style="background: #fef3c7;"' : '' ?>>
                                        <td><strong><?= $row->Ced_Empleado ?></strong></td>
                                        <td><?= htmlspecialchars($nombre_emp) ?></td>
                                        <td><?= htmlspecialchars($row->Numero_gaceta) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row->Fecha_gaceta)) ?></td>
                                        <td><?= htmlspecialchars($row->Pais_procedente) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="crud_mdf_nacionalizacion.php?ced=<?= $row->Ced_Empleado ?>" class="action-btn action-btn-edit">Editar</a>
                                                <a href="crud_elm_nacionalizacion.php?ced=<?= $row->Ced_Empleado ?>" class="action-btn action-btn-delete" onclick="return confirm('¿Estás seguro de eliminar esta nacionalización?')">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px;">No hay registros de nacionalización</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="crud_nacionalizacion.php" class="btn btn-secondary">Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>