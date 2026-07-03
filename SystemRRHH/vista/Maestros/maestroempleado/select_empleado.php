<?php
$ced = $_GET['a'] ?? '';
$ciu = $_GET['b'] ?? '';
$pa = $_GET['c'] ?? '';
$sa = $_GET['d'] ?? '';
$pn = $_GET['e'] ?? '';
$sn = $_GET['f'] ?? '';
$nac = $_GET['g'] ?? '';
$sex = $_GET['h'] ?? '';
$fn = $_GET['i'] ?? '';
$ec = $_GET['j'] ?? '';
$eda = $_GET['k'] ?? '';
$cp = $_GET['l'] ?? '';
$tel = $_GET['m'] ?? '';
$est = $_GET['n'] ?? '';
$pes = $_GET['o'] ?? '';
$gs = $_GET['p'] ?? '';
$hm = $_GET['q'] ?? '';
$nh = $_GET['r'] ?? '';

$cedula = base64_decode($ced);
$id_ciudad = base64_decode($ciu);
$primer_apellido = base64_decode($pa);
$segundo_apellido = base64_decode($sa);
$primer_nombre = base64_decode($pn);
$segundo_nombre = base64_decode($sn);
$nacionalidad = base64_decode($nac);
$sexo = base64_decode($sex);
$fecha_nac = base64_decode($fn);
$estado_civil = base64_decode($ec);
$edad = base64_decode($eda);
$codigo_postal = base64_decode($cp);
$num_telefonico = base64_decode($tel);
$estatura = base64_decode($est);
$peso = base64_decode($pes);
$grp_sanguineo = base64_decode($gs);
$hab_manual = base64_decode($hm);
$numeros_hijos = base64_decode($nh);

include("../../../modelo/conexion.php");
$ciu_sql = $conex->prepare("SELECT nombre FROM ciudad WHERE id_ciudad = ?;");
$ciu_sql->execute([$id_ciudad]);
$ciu_row = $ciu_sql->fetch(PDO::FETCH_ASSOC);
$nombre_ciudad = $ciu_row ? $ciu_row['nombre'] : $id_ciudad;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Empleado</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .nav-panel { float: left; width: 15%; height: auto; padding: 20px 0; }
        .nav-panel a { display: block; padding: 10px 15px; margin: 5px 0; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
        .nav-panel a:hover { background: #007bff; color: white; }
        .nav-panel a.active { background: #007bff; color: white; }
        .content-right { float: right; width: 83%; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .detail-card { background: #f8f9fa; padding: 30px; border-radius: 8px; margin-bottom: 20px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .detail-item { padding: 10px 0; border-bottom: 1px solid #dee2e6; }
        .detail-label { font-weight: 600; color: #495057; display: block; font-size: 0.85rem; }
        .detail-value { font-weight: 500; color: #212529; font-size: 1rem; }
        .btn-volver { background: #007bff; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-volver:hover { background: #0056b3; }
        @media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; } }
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
                <h1>Detalle del Empleado</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>

            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_empleado.php"> Registrar</a>
                    <a href="maestro_empleado.php"> Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card">
                    <h3>Información del Empleado</h3>

                    <?php if (!empty($cedula)): ?>
                    <div class="detail-card">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Cédula</span>
                                <span class="detail-value"><?php echo htmlspecialchars($cedula); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ciudad</span>
                                <span class="detail-value"><?php echo htmlspecialchars($nombre_ciudad); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Primer Nombre</span>
                                <span class="detail-value"><?php echo htmlspecialchars($primer_nombre); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Segundo Nombre</span>
                                <span class="detail-value"><?php echo htmlspecialchars($segundo_nombre ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Primer Apellido</span>
                                <span class="detail-value"><?php echo htmlspecialchars($primer_apellido); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Segundo Apellido</span>
                                <span class="detail-value"><?php echo htmlspecialchars($segundo_apellido ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Nacionalidad</span>
                                <span class="detail-value"><?php echo htmlspecialchars($nacionalidad ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Sexo</span>
                                <span class="detail-value"><?php echo htmlspecialchars($sexo ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Fecha Nacimiento</span>
                                <span class="detail-value"><?php echo htmlspecialchars($fecha_nac ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estado Civil</span>
                                <span class="detail-value"><?php echo htmlspecialchars($estado_civil ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Edad</span>
                                <span class="detail-value"><?php echo htmlspecialchars($edad ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Código Postal</span>
                                <span class="detail-value"><?php echo htmlspecialchars($codigo_postal ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Teléfono</span>
                                <span class="detail-value"><?php echo htmlspecialchars($num_telefonico ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estatura</span>
                                <span class="detail-value"><?php echo htmlspecialchars($estatura ?: '-'); ?> m</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Peso</span>
                                <span class="detail-value"><?php echo htmlspecialchars($peso ?: '-'); ?> kg</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Grupo Sanguíneo</span>
                                <span class="detail-value"><?php echo htmlspecialchars($grp_sanguineo ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Habilidad Manual</span>
                                <span class="detail-value"><?php echo htmlspecialchars($hab_manual ?: '-'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Número de Hijos</span>
                                <span class="detail-value"><?php echo htmlspecialchars($numeros_hijos ?: '0'); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #dc3545;">
                        <h3>No se encontró el empleado</h3>
                        <p>Verifica que la cédula ingresada sea correcta.</p>
                    </div>
                    <?php endif; ?>

                    <div style="margin-top: 20px;">
                        <a href="maestro_empleado.php" class="btn-volver"> Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</body>
</html>
