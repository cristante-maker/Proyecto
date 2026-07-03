<?php
include("../../../modelo/conexion.php");
$sql = $conex->prepare("SELECT f.*, e.primer_nombre, e.primer_apellido FROM familia f LEFT JOIN empleado e ON f.Cedula_Emp = e.cedula ORDER BY f.Nombre_y_Ap ASC;");
$sql->execute();
$num_reg = $sql->rowCount();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Familia - Maestros RRHH</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
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
                <h1>Maestro: Familia</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>
            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_familia.php"> Registrar</a>
                    <a href="maestro_familia.php" class="active"> Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Familiares</h2>
                        <div>
                            <a href="insert_familia.php" class="btn btn-success">+ Nuevo Familiar</a>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Nombre y Apellido</th>
                                    <th>Parentesco</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($num_reg > 0) {
                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                        $id_familiar = $row['id_familiar'];
                                        $id_enc = base64_encode($id_familiar);
                                        $empleado_nombre = ($row['primer_nombre'] ?? '') . ' ' . ($row['primer_apellido'] ?? '');
                                ?>
                                <tr>
                                    <td><?php echo str_pad($id_familiar, 2, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars(trim($empleado_nombre) ?: $row['Cedula_Emp']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['Nombre_y_Ap']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['Parentesco']); ?></td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="../../../controlador/ctl_familia.php?C=con&I=<?php echo $id_enc; ?>" class="action-btn action-btn-view"> Ver</a>
                                            <a href="../../../controlador/ctl_familia.php?M=mos&I=<?php echo $id_enc; ?>" class="action-btn action-btn-edit"> Editar</a>
                                            <a onclick="if (confirm('¿Desea eliminar este familiar?')){ window.location.href='../../../controlador/ctl_familia.php?E=eli&I=<?php echo $id_enc; ?>'; }" href="" class="action-btn action-btn-delete"> Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" style="text-align: center; padding: 40px; color: #6c757d;">No hay familiares registrados. <a href="insert_familia.php" style="color: #007bff;">Registrar primero</a></td></tr>';
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
