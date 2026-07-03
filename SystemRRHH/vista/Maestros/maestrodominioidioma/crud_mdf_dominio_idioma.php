<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener ID de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener datos del dominio
$sql = $con->query("SELECT d.*, e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido,
                    i.Nombre as idioma_nombre
                    FROM dominio d
                    INNER JOIN empleado e ON d.Cedula_Emp = e.Cedula
                    INNER JOIN idioma i ON d.id_idioma = i.id_idioma
                    WHERE d.id_dominio = $id");
$datos = $sql->fetch_object();

// Si no existe, redirigir
if (!$datos) {
    header("Location: crud_dominio_idioma.php");
    exit();
}

// Obtener lista de empleados para el combo box
$empleados = $con->query("SELECT Cedula, Primer_Nombre, Segundo_Nombre, Primer_apellido, Segundo_apellido 
                          FROM empleado 
                          ORDER BY Primer_apellido, Primer_Nombre");

// Obtener lista de idiomas para el combo box
$idiomas = $con->query("SELECT id_idioma, Nombre FROM idioma ORDER BY Nombre");

// Obtener lista de dominios para la tabla
$query = $con->query("SELECT d.id_dominio, d.Cedula_Emp, d.id_idioma, 
                      d.Comprende, d.Habla, d.Lee, d.Escribe,
                      e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido,
                      i.Nombre as idioma_nombre
                      FROM dominio d
                      INNER JOIN empleado e ON d.Cedula_Emp = e.Cedula
                      INNER JOIN idioma i ON d.id_idioma = i.id_idioma
                      ORDER BY e.Primer_apellido, i.Nombre");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Dominio de Idioma</title>
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
                <h1>Modificar Dominio de Idioma</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- Formulario de modificación -->
                <div class="section-card">
                    <h3>Modificar Dominio de Idioma</h3>
                    <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_dominio_idioma.php"); ?>
                    
                    <form method="POST" action="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Seleccionar Empleado *</label>
                                <?php 
                                    $nombre_actual = $datos->Primer_apellido;
                                    if ($datos->Segundo_apellido) $nombre_actual .= ' ' . $datos->Segundo_apellido;
                                    $nombre_actual .= ', ' . $datos->Primer_Nombre;
                                    if ($datos->Segundo_Nombre) $nombre_actual .= ' ' . $datos->Segundo_Nombre;
                                ?>
                                <select class="form-input" name="ced_empleado" required>
                                    <option value="<?= $datos->Cedula_Emp ?>" selected>
                                        <?= htmlspecialchars($nombre_actual) ?> (<?= $datos->Cedula_Emp ?>)
                                    </option>
                                    <?php while($emp = $empleados->fetch_object()): ?>
                                        <?php if ($emp->Cedula != $datos->Cedula_Emp): ?>
                                            <?php 
                                                $nombre_completo = $emp->Primer_apellido;
                                                if ($emp->Segundo_apellido) $nombre_completo .= ' ' . $emp->Segundo_apellido;
                                                $nombre_completo .= ', ' . $emp->Primer_Nombre;
                                                if ($emp->Segundo_Nombre) $nombre_completo .= ' ' . $emp->Segundo_Nombre;
                                            ?>
                                            <option value="<?= $emp->Cedula ?>">
                                                <?= htmlspecialchars($nombre_completo) ?> (<?= $emp->Cedula ?>)
                                            </option>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Seleccionar Idioma *</label>
                                <select class="form-input" name="id_idioma" required>
                                    <option value="<?= $datos->id_idioma ?>" selected>
                                        <?= htmlspecialchars($datos->idioma_nombre) ?>
                                    </option>
                                    <?php while($idioma = $idiomas->fetch_object()): ?>
                                        <?php if ($idioma->id_idioma != $datos->id_idioma): ?>
                                            <option value="<?= $idioma->id_idioma ?>">
                                                <?= htmlspecialchars($idioma->Nombre) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Niveles de Dominio *</label>
                                <div style="display: flex; gap: 20px; flex-wrap: wrap; padding-top: 5px;">
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="checkbox" name="Comprende" value="Sí" <?= $datos->Comprende === 'Sí' ? 'checked' : '' ?>> Comprende
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="checkbox" name="Habla" value="Sí" <?= $datos->Habla === 'Sí' ? 'checked' : '' ?>> Habla
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="checkbox" name="Lee" value="Sí" <?= $datos->Lee === 'Sí' ? 'checked' : '' ?>> Lee
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                        <input type="checkbox" name="Escribe" value="Sí" <?= $datos->Escribe === 'Sí' ? 'checked' : '' ?>> Escribe
                                    </label>
                                </div>
                                <small style="color: #6b7280; font-size: 12px;">Seleccione al menos un nivel</small>
                            </div>
                        </div>
                        <div style="text-align: right; margin-top: 15px;">
                            <input type="hidden" name="id_dominio" value="<?= $id ?>">
                            <button type="submit" class="btn btn-warning" name="accion" value="modificar">
                                Modificar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de dominios -->
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Dominios de Idiomas</h2>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Idioma</th>
                                    <th>Comprende</th>
                                    <th>Habla</th>
                                    <th>Lee</th>
                                    <th>Escribe</th>
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
                                    <tr <?= ($row->id_dominio == $id) ? 'style="background: #fef3c7;"' : '' ?>>
                                        <td><strong><?= $row->id_dominio ?></strong></td>
                                        <td><?= htmlspecialchars($nombre_emp) ?></td>
                                        <td><?= htmlspecialchars($row->idioma_nombre) ?></td>
                                        <td><?= $row->Comprende === 'Sí' ? '✅ Sí' : '❌ No' ?></td>
                                        <td><?= $row->Habla === 'Sí' ? '✅ Sí' : '❌ No' ?></td>
                                        <td><?= $row->Lee === 'Sí' ? '✅ Sí' : '❌ No' ?></td>
                                        <td><?= $row->Escribe === 'Sí' ? '✅ Sí' : '❌ No' ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="crud_mdf_dominio_idioma.php?id=<?= $row->id_dominio ?>" class="action-btn action-btn-edit">Editar</a>
                                                <a href="crud_elm_dominio_idioma.php?id=<?= $row->id_dominio ?>" class="action-btn action-btn-delete" onclick="return confirm('¿Estás seguro de eliminar este dominio?')">Eliminar</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 30px;">No hay registros de dominio de idiomas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 15px;">
                        <a href="crud_dominio_idioma.php" class="btn btn-secondary">Volver al listado</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>