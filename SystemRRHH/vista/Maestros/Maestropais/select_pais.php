<?php
// vista/select_pais.php
// Muestra el detalle de un país consultado

$nombre  = base64_decode($_GET['b'] ?? '');
$codigo  = base64_decode($_GET['c'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del País</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .nav-panel {
            float: left;
            width: 15%;
            height: auto;
            padding: 20px 0;
        }
        .nav-panel a {
            display: block;
            padding: 10px 15px;
            margin: 5px 0;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .nav-panel a:hover {
            background: #007bff;
            color: white;
        }
        .nav-panel a.active {
            background: #007bff;
            color: white;
        }
        .content-right {
            float: right;
            width: 83%;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .detail-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .detail-item {
            padding: 12px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            display: inline-block;
            width: 150px;
        }
        .detail-value {
            font-weight: 500;
            color: #212529;
        }
        .btn-volver {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-volver:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h2>Sistema de Gestión</h2>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/dashboard.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Dashboard.png" alt="Dashboard" class="menu-icon">
                        </span>
                        Administración
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="/SystemRRHH/vista/maestros.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Maestros.png" alt="Maestros" class="menu-icon">
                        </span>
                        Maestros
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/reclutamiento.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Reclutamiento.png" alt="Reclutamiento" class="menu-icon">
                        </span>
                        Reclutamiento
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/contratacion.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Contratacion.png" alt="Contratación" class="menu-icon">
                        </span>
                        Contratación
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/asistencia.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Asistencia.png" alt="Asistencia" class="menu-icon">
                        </span>
                        Asistencia
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/vacaciones.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Vacaciones.png" alt="Vacaciones" class="menu-icon">
                        </span>
                        Vacaciones
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/reposos.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Reposo.png" alt="Reposos" class="menu-icon">
                        </span>
                        Reposos
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/permisos.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Permiso.png" alt="Permisos" class="menu-icon">
                        </span>
                        Permisos
                    </a>
                </li>
                <li class="menu-item">
                    <a href="/SystemRRHH/vista/configuracion.html">
                        <span class="icon">
                            <img src="/SystemRRHH/vista/Iconos/Configuracion.png" alt="Configuración" class="menu-icon">
                        </span>
                        Configuración
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content clearfix">
            <header class="header">
                <h1>Detalle del País</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>

            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_pais.html">📝 Registrar</a>
                    <a href="select_pais.html" class="active">🔍 Consultar</a>
                    <a href="update_pais.html">✏️ Actualizar</a>
                    <a href="delete_pais.html">🗑️ Eliminar</a>
                    <a href="maestro_pais.php">📋 Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card">
                    <h3>Información del País</h3>
                    
                    <?php if (!empty($nombre)): ?>
                    <div class="detail-card">
                        <div class="detail-item">
                            <span class="detail-label">📌 Nombre:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($nombre); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">🔤 Código ISO:</span>
                            <span class="detail-value"><?php echo $codigo ?: 'No especificado'; ?></span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #dc3545;">
                        <h3>⚠️ No se encontró el país</h3>
                        <p>Verifica que el nombre ingresado sea correcto.</p>
                    </div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 20px;">
                        <a href="/SystemRRHH/vista/Maestros/Maestropais/select_pais.html" class="btn-volver">← Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>