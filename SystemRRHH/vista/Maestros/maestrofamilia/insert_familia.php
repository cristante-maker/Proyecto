<?php
include("../../../modelo/conexion.php");
$emp_sql = $conex->prepare("SELECT cedula, primer_nombre, primer_apellido FROM empleado ORDER BY primer_nombre ASC;");
$emp_sql->execute();
$empleados = $emp_sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Familiar - Maestros RRHH</title>
    <link rel="stylesheet" href="/SystemRRHH/vista/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .crud-panel { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .crud-panel .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .crud-panel .form-group { margin-bottom: 15px; }
        .crud-panel label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; }
        .crud-panel .form-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        .crud-panel select.form-input { height: 42px; }
        .crud-buttons { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px; padding-top: 15px; border-top: 2px solid #dee2e6; }
        .crud-buttons .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .crud-buttons .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .btn-registrar { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .nav-panel { float: left; width: 15%; height: auto; padding: 20px 0; }
        .nav-panel a { display: block; padding: 10px 15px; margin: 5px 0; color: #333; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
        .nav-panel a:hover { background: #007bff; color: white; }
        .nav-panel a.active { background: #007bff; color: white; }
        .content-right { float: right; width: 83%; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .full-width { grid-column: span 2; }
        @media (max-width: 768px) { .crud-panel .form-grid { grid-template-columns: 1fr; } .full-width { grid-column: span 1; } }
    </style>
    <script type="text/javascript">
    function validarForm(f) {
        for (var i = 0; i < f.elements.length; i++) {
            var e = f.elements[i];
            if (e.type !== 'hidden' && e.type !== 'submit' && e.type !== 'button' && e.hasAttribute('required') && e.value.trim() === '') {
                alert('El campo "' + (e.name) + '" es obligatorio.');
                e.focus();
                return false;
            }
        }
        return true;
    }
    </script>
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
                <h1>Registrar Familiar</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>
            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_familia.php" class="active"> Registrar</a>
                    <a href="maestro_familia.php"> Listado</a>
                </center>
            </div>

            <div class="content-right">
                <div class="section-card crud-panel">
                    <h3>Registrar Familiar</h3>

                    <form method="POST" action="/SystemRRHH/controlador/ctl_familia.php" onsubmit="return validarForm(this)">
                        <input type="hidden" name="registrar" value="registrar">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Empleado *</label>
                                <select class="form-input" name="Cedula_Emp" required>
                                    <option value="">Seleccione un empleado</option>
                                    <?php foreach ($empleados as $emp): ?>
                                    <option value="<?php echo $emp['cedula']; ?>"><?php echo htmlspecialchars($emp['cedula'] . ' - ' . $emp['primer_nombre'] . ' ' . $emp['primer_apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Número de Cédula *</label>
                                <input type="text" class="form-input" name="Numero_Cedula" placeholder="Ej: V-87654321" required>
                            </div>
                            <div class="form-group">
                                <label>Nombre Completo *</label>
                                <input type="text" class="form-input" name="Nombre_y_Ap" placeholder="Ej: María López" required>
                            </div>
                            <div class="form-group">
                                <label>Parentesco *</label>
                                <select class="form-input" name="Parentesco" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Hijo">Hijo</option>
                                    <option value="Hija">Hija</option>
                                    <option value="Cónyuge">Cónyuge</option>
                                    <option value="Madre">Madre</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Hermano">Hermano</option>
                                    <option value="Hermana">Hermana</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select class="form-input" name="Sexo">
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" class="form-input" name="Fecha_Nac">
                            </div>
                            <div class="form-group">
                                <label>Nivel Educativo</label>
                                <input type="text" class="form-input" name="Nivel_educativo" placeholder="Ej: Bachiller">
                            </div>
                            <div class="form-group">
                                <label>Convive Colectivamente</label>
                                <select class="form-input" name="Convec_Colectiva">
                                    <option value="">Seleccione...</option>
                                    <option value="Si">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="crud-buttons">
                            <button type="submit" class="btn btn-registrar"> Registrar Familiar</button>
                            <a href="maestro_familia.php" class="btn btn-secondary"> Cancelar</a>
                        </div>
                    </form>
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
