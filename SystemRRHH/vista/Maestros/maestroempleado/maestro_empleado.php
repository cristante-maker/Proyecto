<?php
include("../../../modelo/conexion.php");
$sql = $conex->prepare("SELECT cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, num_telefonico FROM empleado ORDER BY primer_nombre, primer_apellido ASC;");
$sql->execute();
$num_reg = $sql->rowCount();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado - Maestros RRHH</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .nav-panel { float: left; width: 15%; height: auto; padding: 20px 0; }
        .nav-panel a { display: block; padding: 10px 15px; margin: 5px 0; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
        .nav-panel a:hover { background: #007bff; color: white; }
        .nav-panel a.active { background: #007bff; color: white; }
        .content-right { float: right; width: 83%; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .action-btn { cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
    </style>
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

        <main class="main-content clearfix">
            <header class="header">
                <h1>Maestro: Empleado</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>
            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_empleado.php"> Registrar</a>
                    <a href="maestro_empleado.php" class="active"> Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Empleados</h2>
                        <div>
                            <a href="insert_empleado.php" class="btn btn-success">+ Nuevo Empleado</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Primer Nombre</th>
                                    <th>Segundo Nombre</th>
                                    <th>Primer Apellido</th>
                                    <th>Segundo Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($num_reg > 0) {
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        $cedula = $row['cedula'];
                                        $ced = base64_encode($cedula);
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($cedula); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['primer_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['segundo_nombre'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['primer_apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($row['segundo_apellido'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['num_telefonico'] ?? '-'); ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="../../../controlador/ctl_empleado.php?C=con&I=<?php echo $ced; ?>" class="action-btn action-btn-view"> Ver</a>
                                            <a href="../../../controlador/ctl_empleado.php?M=mos&I=<?php echo $ced; ?>" class="action-btn action-btn-edit"> Editar</a>
                                            <a onclick="if (confirm('¿Desea eliminar este empleado?')){ window.location.href='../../../controlador/ctl_empleado.php?E=eli&I=<?php echo $ced; ?>'; }" href="" class="action-btn action-btn-delete"> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">No hay empleados registrados. <a href="insert_empleado.php" style="color: #007bff;">Registrar primero</a></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <?php if (isset($_GET['msg'])): ?>
    <script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            alert(decodeURIComponent("<?php echo rawurlencode($_GET['msg']); ?>"));
        }, 500);
    });
    </script>
    <?php endif; ?>
</body>
</html>
