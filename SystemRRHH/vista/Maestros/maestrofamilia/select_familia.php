<?php
$v1 = base64_decode($_GET['a']);
$v2 = base64_decode($_GET['b']);
$v3 = base64_decode($_GET['c']);
$v4 = base64_decode($_GET['d']);
$v5 = base64_decode($_GET['e']);
$v6 = base64_decode($_GET['f']);
$v7 = base64_decode($_GET['g']);
$v8 = base64_decode($_GET['h']);
$v9 = base64_decode($_GET['i']);

include("../../../modelo/conexion.php");
$emp_sql = $conex->prepare("SELECT primer_nombre, primer_apellido FROM empleado WHERE cedula = ?;");
$emp_sql->execute([$v2]);
$emp_row = $emp_sql->fetch(PDO::FETCH_ASSOC);
$empleado_nombre = $emp_row ? $emp_row['primer_nombre'] . ' ' . $emp_row['primer_apellido'] : $v2;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Familiar</title>
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
                <h1>Detalle del Familiar</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>

            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_familia.php"> Registrar</a>
                    <a href="maestro_familia.php"> Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card">
                    <h3>Información del Familiar</h3>

                    <div class="detail-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="detail-item"><span class="detail-label">ID</span><span class="detail-value"><?php echo str_pad($v1, 2, '0', STR_PAD_LEFT); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Empleado</span><span class="detail-value"><?php echo htmlspecialchars($empleado_nombre); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Cédula</span><span class="detail-value"><?php echo htmlspecialchars($v3); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Nombre Completo</span><span class="detail-value"><?php echo htmlspecialchars($v4); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Parentesco</span><span class="detail-value"><?php echo htmlspecialchars($v5); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Sexo</span><span class="detail-value"><?php echo htmlspecialchars($v6 ?: '-'); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Fecha Nac.</span><span class="detail-value"><?php echo htmlspecialchars($v7 ?: '-'); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Nivel Educativo</span><span class="detail-value"><?php echo htmlspecialchars($v8 ?: '-'); ?></span></div>
                        <div class="detail-item"><span class="detail-label">Convive Colect.</span><span class="detail-value"><?php echo htmlspecialchars($v9 ?: '-'); ?></span></div>
                    </div>

                    <div style="margin-top: 20px;">
                        <a href="maestro_familia.php" class="btn btn-primary"> Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</body>
</html>
