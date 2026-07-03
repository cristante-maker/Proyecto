<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// Obtener ID de la URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar si el registro existe
$check = $con->query("SELECT d.*, e.Primer_Nombre, e.Segundo_Nombre, e.Primer_apellido, e.Segundo_apellido,
                      i.Nombre as idioma_nombre
                      FROM dominio d
                      INNER JOIN empleado e ON d.Cedula_Emp = e.Cedula
                      INNER JOIN idioma i ON d.id_idioma = i.id_idioma
                      WHERE d.id_dominio = $id");
if ($check->num_rows === 0) {
    header("Location: crud_dominio_idioma.php");
    exit();
}

$dominio = $check->fetch_object();

// Obtener lista para la tabla
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
    <title>Eliminar Dominio de Idioma</title>
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
                <h1>Eliminar Dominio de Idioma</h1>
                <div class="user-profile">
                    <span>Juan Pérez</span>
                    <div class="user-avatar">JP</div>
                </div>
            </header>

            <div class="container mt-4">
                <!-- INCLUIR EL CONTROLADOR -->
                <?php require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/controlador/ctl_dominio_idioma.php"); ?>

                <!-- Tarjeta de confirmación de eliminación -->
                <div class="section-card" style="border: 2px solid #f5c6cb;">
                    <h3 style="color: #721c24;">⚠️ Confirmar Eliminación</h3>
                    <div style="background: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px; padding: 15px; margin: 15px 0;">
                        <h4>¿Estás seguro de eliminar este dominio de idioma?</h4>
                        <?php 
                            $nombre_emp = $dominio->Primer_apellido;
                            if ($dominio->Segundo_apellido) $nombre_emp .= ' ' . $dominio->Segundo_apellido;
                            $nombre_emp .= ', ' . $dominio->Primer_Nombre;
                            if ($dominio->Segundo_Nombre) $nombre_emp .= ' ' . $dominio->Segundo_Nombre;
                        ?>
                        <p><strong>Empleado:</strong> <?= htmlspecialchars($nombre_emp) ?> (<?= $dominio->Cedula_Emp ?>)</p>
                        <p><strong>Idioma:</strong> <?= htmlspecialchars($dominio->idioma_nombre) ?></p>
                        <p><strong>Niveles:</strong> 
                            <?php 
                                $niveles = [];
                                if ($dominio->Comprende === 'Sí') $niveles[] = 'Comprende';
                                if ($dominio->Habla === 'Sí') $niveles[] = 'Habla';
                                if ($dominio->Lee === 'Sí') $niveles[] = 'Lee';
                                if ($dominio->Escribe === 'Sí') $niveles[] = 'Escribe';
                                echo implode(', ', $niveles);
                            ?>
                        </p>
                        <p style="color: #721c24;">⚠️ Esta acción no se puede deshacer</p>
                    </div>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id_dominio" value="<?= $id ?>">
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                            <a href="crud_dominio_idioma.php" class="btn btn-secondary">Cancelar</a>
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
                                    <tr <?= ($row->id_dominio == $id) ? 'style="background: #f8d7da;"' : '' ?>>
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
                                                <a href="crud_elm_dominio_idioma.php?id=<?= $row->id_dominio ?>" class="action-btn action-btn-delete">Eliminar</a>
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