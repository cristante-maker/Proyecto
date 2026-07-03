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
$ciu_sql = $conex->prepare("SELECT id_ciudad, nombre FROM ciudad ORDER BY nombre ASC;");
$ciu_sql->execute();
$ciudades = $ciu_sql->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado - Maestros RRHH</title>
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
    var ETIQUETAS = {'cedula':'Cédula','id_ciudad':'Ciudad','primer_nombre':'Primer Nombre','primer_apellido':'Primer Apellido'};
    function validarForm(f) {
        for (var i = 0; i < f.elements.length; i++) {
            var e = f.elements[i];
            if (e.type !== 'hidden' && e.type !== 'submit' && e.type !== 'button' && e.hasAttribute('required') && e.value.trim() === '') {
                alert('El campo "' + (ETIQUETAS[e.name] || e.name) + '" es obligatorio.');
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
                <h1>Editar Empleado</h1>
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
                <div class="section-card crud-panel">
                    <h3>Editar Empleado</h3>

                    <form method="POST" action="/SystemRRHH/controlador/ctl_empleado.php" onsubmit="return validarForm(this)">
                        <input type="hidden" name="modificar" value="modificar">
                        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($cedula); ?>">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Cédula</label>
                                <input type="text" class="form-input" value="<?php echo htmlspecialchars($cedula); ?>" disabled style="background:#eee;">
                            </div>
                            <div class="form-group">
                                <label>Ciudad *</label>
                                <select class="form-input" name="id_ciudad" required>
                                    <?php foreach ($ciudades as $ciu): ?>
                                    <option value="<?php echo $ciu['id_ciudad']; ?>" <?php echo $ciu['id_ciudad'] == $id_ciudad ? 'selected' : ''; ?>><?php echo htmlspecialchars($ciu['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Primer Nombre *</label>
                                <input type="text" class="form-input" name="primer_nombre" value="<?php echo htmlspecialchars($primer_nombre); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Segundo Nombre</label>
                                <input type="text" class="form-input" name="segundo_nombre" value="<?php echo htmlspecialchars($segundo_nombre); ?>">
                            </div>
                            <div class="form-group">
                                <label>Primer Apellido *</label>
                                <input type="text" class="form-input" name="primer_apellido" value="<?php echo htmlspecialchars($primer_apellido); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Segundo Apellido</label>
                                <input type="text" class="form-input" name="segundo_apellido" value="<?php echo htmlspecialchars($segundo_apellido); ?>">
                            </div>
                            <div class="form-group">
                                <label>Nacionalidad</label>
                                <input type="text" class="form-input" name="nacionalidad" value="<?php echo htmlspecialchars($nacionalidad); ?>">
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select class="form-input" name="sexo">
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino" <?php echo $sexo=='Masculino'?'selected':''; ?>>Masculino</option>
                                    <option value="Femenino" <?php echo $sexo=='Femenino'?'selected':''; ?>>Femenino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Nacimiento</label>
                                <input type="date" class="form-input" name="fecha_nac" value="<?php echo htmlspecialchars($fecha_nac); ?>">
                            </div>
                            <div class="form-group">
                                <label>Estado Civil</label>
                                <select class="form-input" name="estado_civil">
                                    <option value="">Seleccione...</option>
                                    <option value="Soltero" <?php echo $estado_civil=='Soltero'?'selected':''; ?>>Soltero</option>
                                    <option value="Casado" <?php echo $estado_civil=='Casado'?'selected':''; ?>>Casado</option>
                                    <option value="Divorciado" <?php echo $estado_civil=='Divorciado'?'selected':''; ?>>Divorciado</option>
                                    <option value="Viudo" <?php echo $estado_civil=='Viudo'?'selected':''; ?>>Viudo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Código Postal</label>
                                <input type="text" class="form-input" name="codigo_postal" value="<?php echo htmlspecialchars($codigo_postal); ?>">
                            </div>
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" class="form-input" name="num_telefonico" value="<?php echo htmlspecialchars($num_telefonico); ?>">
                            </div>
                            <div class="form-group">
                                <label>Estatura (m)</label>
                                <input type="number" class="form-input" name="estatura" value="<?php echo htmlspecialchars($estatura); ?>" step="0.01" min="0" max="3">
                            </div>
                            <div class="form-group">
                                <label>Peso (kg)</label>
                                <input type="number" class="form-input" name="peso" value="<?php echo htmlspecialchars($peso); ?>" step="0.1" min="0" max="500">
                            </div>
                            <div class="form-group">
                                <label>Grupo Sanguíneo</label>
                                <select class="form-input" name="grp_sanguineo">
                                    <option value="">Seleccione...</option>
                                    <option value="A+" <?php echo $grp_sanguineo=='A+'?'selected':''; ?>>A+</option>
                                    <option value="A-" <?php echo $grp_sanguineo=='A-'?'selected':''; ?>>A-</option>
                                    <option value="B+" <?php echo $grp_sanguineo=='B+'?'selected':''; ?>>B+</option>
                                    <option value="B-" <?php echo $grp_sanguineo=='B-'?'selected':''; ?>>B-</option>
                                    <option value="AB+" <?php echo $grp_sanguineo=='AB+'?'selected':''; ?>>AB+</option>
                                    <option value="AB-" <?php echo $grp_sanguineo=='AB-'?'selected':''; ?>>AB-</option>
                                    <option value="O+" <?php echo $grp_sanguineo=='O+'?'selected':''; ?>>O+</option>
                                    <option value="O-" <?php echo $grp_sanguineo=='O-'?'selected':''; ?>>O-</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Habilidad Manual</label>
                                <select class="form-input" name="hab_manual">
                                    <option value="">Seleccione...</option>
                                    <option value="Diestro" <?php echo $hab_manual=='Diestro'?'selected':''; ?>>Diestro</option>
                                    <option value="Zurdo" <?php echo $hab_manual=='Zurdo'?'selected':''; ?>>Zurdo</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Número de Hijos</label>
                                <input type="number" class="form-input" name="numeros_hijos" value="<?php echo htmlspecialchars($numeros_hijos); ?>" min="0">
                            </div>
                        </div>

                        <div class="crud-buttons">
                            <button type="submit" class="btn btn-primary"> Guardar Cambios</button>
                            <a href="maestro_empleado.php" class="btn btn-secondary"> Cancelar</a>
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
