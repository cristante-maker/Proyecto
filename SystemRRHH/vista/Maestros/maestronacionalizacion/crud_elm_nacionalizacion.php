<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener cédula de la URL
$ced = isset($_GET['ced']) ? trim($_GET['ced']) : '';

// Verificar si el registro existe
$check = $con->query("SELECT n.*, e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido 
                      FROM nacionalizacion n
                      INNER JOIN empleado e ON n.Ced_Empleado = e.Cedula
                      WHERE n.Ced_Empleado = '$ced'");
if ($check->num_rows === 0) {
    header("Location: crud_nacionalizacion.php");
    exit();
}

$nacionalizacion = $check->fetch_object();

// Obtener lista para la tabla
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
    <title>Eliminar Nacionalización</title>
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
                <h1>Eliminar Nacionalización</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- INCLUIR EL CONTROLADOR -->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_nacionalizacion.php"); ?>

                <!-- Tarjeta de confirmación de eliminación -->
                <div class="section-card" style="border: 2px solid #f5c6cb;">
                    <h3 style="color: #721c24;">⚠️ Confirmar Eliminación</h3>
                    <div style="background: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px; padding: 15px; margin: 15px 0;">
                        <h4>¿Estás seguro de eliminar esta nacionalización?</h4>
                        <?php 
                            $nombre_emp = $nacionalizacion->Primer_apellido;
                            if ($nacionalizacion->Segundo_apellido) $nombre_emp .= ' ' . $nacionalizacion->Segundo_apellido;
                            $nombre_emp .= ', ' . $nacionalizacion->Primer_Nombre;
                            if ($nacionalizacion->Segundo_Nombre) $nombre_emp .= ' ' . $nacionalizacion->Segundo_Nombre;
                        ?>
                        <p><strong>Empleado:</strong> <?= htmlspecialchars($nombre_emp) ?> (<?= $nacionalizacion->Ced_Empleado ?>)</p>
                        <p><strong>Número de Gaceta:</strong> <?= htmlspecialchars($nacionalizacion->Numero_gaceta) ?></p>
                        <p><strong>País de Procedencia:</strong> <?= htmlspecialchars($nacionalizacion->Pais_procedente) ?></p>
                        <p style="color: #721c24;">⚠️ Esta acción no se puede deshacer</p>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="ced_empleado" value="<?= $ced ?>">
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                            <a href="crud_nacionalizacion.php" class="btn btn-secondary">Cancelar</a>
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
                                    <tr <?= ($row->Ced_Empleado == $ced) ? 'style="background: #f8d7da;"' : '' ?>>
                                        <td><strong><?= $row->Ced_Empleado ?></strong></td>
                                        <td><?= htmlspecialchars($nombre_emp) ?></td>
                                        <td><?= htmlspecialchars($row->Numero_gaceta) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row->Fecha_gaceta)) ?></td>
                                        <td><?= htmlspecialchars($row->Pais_procedente) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="crud_mdf_nacionalizacion.php?ced=<?= $row->Ced_Empleado ?>" class="action-btn action-btn-edit">Editar</a>
                                                <a href="crud_elm_nacionalizacion.php?ced=<?= $row->Ced_Empleado ?>" class="action-btn action-btn-delete">Eliminar</a>
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