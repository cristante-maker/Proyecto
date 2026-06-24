<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado - Maestros RRHH</title>
    <!-- RUTA CORREGIDA: CSS relativo -->
    <link rel="stylesheet" href="../../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        .crud-panel {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .crud-panel .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .crud-panel .form-group {
            margin-bottom: 15px;
        }
        .crud-panel label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
        }
        .crud-panel .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .crud-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }
        .crud-buttons .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .crud-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-refrescar { background: #17a2b8; color: white; }
        .btn-limpiar { background: #6c757d; color: white; }
        .btn-success { background: #28a745; color: white; }

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

        /* Mensajes de operación */
        .mensaje-operacion {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            display: none;
        }
        .mensaje-operacion.exito {
            display: block;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .mensaje-operacion.error {
            display: block;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .mensaje-operacion.info {
            display: block;
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar con rutas corregidas -->
        <nav class="sidebar">
            <h2>Sistema de Gestión</h2>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="../../dashboard.html">
                        <span class="icon"><img src="../../../Iconos/Dashboard.png" alt="Dashboard" class="menu-icon"></span>
                        Administración
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="../../maestros.html">
                        <span class="icon"><img src="../../../Iconos/Maestros.png" alt="Maestros" class="menu-icon"></span>
                        Maestros
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../reclutamiento.html">
                        <span class="icon"><img src="../../../Iconos/Reclutamiento.png" alt="Reclutamiento" class="menu-icon"></span>
                        Reclutamiento
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../contratacion.html">
                        <span class="icon"><img src="../../../Iconos/Contratacion.png" alt="Contratación" class="menu-icon"></span>
                        Contratación
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../asistencia.html">
                        <span class="icon"><img src="../../../Iconos/Asistencia.png" alt="Asistencia" class="menu-icon"></span>
                        Asistencia
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../vacaciones.html">
                        <span class="icon"><img src="../../../Iconos/Vacaciones.png" alt="Vacaciones" class="menu-icon"></span>
                        Vacaciones
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../reposos.html">
                        <span class="icon"><img src="../../../Iconos/Reposo.png" alt="Reposos" class="menu-icon"></span>
                        Reposos
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../permisos.html">
                        <span class="icon"><img src="../../../Iconos/Permiso.png" alt="Permisos" class="menu-icon"></span>
                        Permisos
                    </a>
                </li>
                <li class="menu-item">
                    <a href="../../configuracion.html">
                        <span class="icon"><img src="../../../Iconos/Configuracion.png" alt="Configuración" class="menu-icon"></span>
                        Configuración
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content clearfix">
            <header class="header">
                <h1>Maestro: Estado</h1>
                <div class="user-profile"><span>Juan Pérez</span><div class="user-avatar">JP</div></div>
            </header>

            <!-- ========================================= -->
            <!-- PANEL DE NAVEGACIÓN (MENÚ CRUD)           -->
            <!-- ========================================= -->
            <div class="nav-panel">
                <center>
                    <h4 style="margin-bottom: 15px;">Operaciones</h4>
                    <a href="insert_estado.html">📝 Registrar</a>
                    <a href="select_estado.html">🔍 Consultar</a>
                    <a href="update_estado.html">✏️ Actualizar</a>
                    <a href="delete_estado.html">🗑️ Eliminar</a>
                    <a href="maestro_estado.php" class="active">📋 Listado</a>
                </center>
            </div>

            <!-- ========================================= -->
            <!-- CONTENIDO PRINCIPAL                        -->
            <!-- ========================================= -->
            <div class="content-right">

                <!-- ========================================= -->
                <!-- PANEL CRUD (REGISTRAR)                    -->
                <!-- ========================================= -->
                <div class="section-card crud-panel">
                    <h3>Gestión de Estados</h3>
                    
                    <!-- Mensaje de operación -->
                    <div id="mensajeOperacion" class="mensaje-operacion"></div>

                    <!-- FORMULARIO DE REGISTRO CON RUTA CORREGIDA -->
                    <form method="POST" action="../../../controlador/ctl_estado.php">
                        <input type="hidden" name="registrar" value="registrar">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre del Estado *</label>
                                <input type="text" class="form-input" id="nombre" name="nombre" placeholder="Ej: Lara" required>
                            </div>
                            <div class="form-group">
                                <label>País *</label>
                                <!-- CAMBIADO: name="pais_id" en lugar de "pais" -->
                                <select class="form-input" id="pais_id" name="pais_id" required>
                                    <option value="">Seleccione un país...</option>
                                    <?php
                                    // RUTA CORREGIDA: ../../../modelo/conexion.php
                                    include("../../../modelo/conexion.php");
                                    $sql = $conex->prepare("SELECT id, nombre FROM pais ORDER BY nombre ASC;");
                                    $sql->execute();
                                    $paises = $sql->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($paises as $p) {
                                        echo '<option value="' . htmlspecialchars($p['id']) . '">' . htmlspecialchars($p['nombre']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Capital</label>
                                <input type="text" class="form-input" id="capital" name="capital" placeholder="Ej: Barquisimeto">
                            </div>
                        </div>

                        <div class="crud-buttons">
                            <button type="submit" class="btn btn-success">
                                ➕ Registrar Estado
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ========================================= -->
                <!-- LISTADO DE ESTADOS                        -->
                <!-- ========================================= -->
                <div class="section-card">
                    <div class="section-header">
                        <h2>Listado de Estados</h2>
                        <div>
                            <form method="POST" action="maestro_estado.php" style="display: inline;">
                                <input type="text" class="search-input" name="buscar" placeholder="Buscar estado..." value="<?php echo isset($_POST['buscar']) ? htmlspecialchars($_POST['buscar']) : ''; ?>">
                                <button type="submit" name="filtrar" value="filtrar" class="btn btn-refrescar">🔍</button>
                                <button type="submit" name="limpiar" value="limpiar" class="btn btn-limpiar">🧹</button>
                            </form>
                        </div>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>País</th>
                                    <th>Capital</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // ============================================
                                // LISTADO DE ESTADOS (PHP PURO)
                                // ============================================
                                
                                // RUTAS CORREGIDAS
                                include("../../../modelo/conexion.php");
                                include("../../../modelo/clase_estado.php");
                                
                                $estado = new Estado();
                                
                                // Verificar búsqueda
                                $buscar = '';
                                if (isset($_POST['filtrar']) && isset($_POST['buscar']) && !empty($_POST['buscar'])) {
                                    $buscar = trim($_POST['buscar']);
                                }
                                
                                if (!empty($buscar)) {
                                    $datos = $estado->ConsultarEstado($buscar);
                                    if ($datos != 0) {
                                        $estados = [[
                                            'id' => null,
                                            'nombre' => $datos[0],
                                            'pais' => $datos[1],
                                            'capital' => $datos[2]
                                        ]];
                                    } else {
                                        $estados = [];
                                    }
                                } else {
                                    $estados = $estado->ListarEstados();
                                }
                                
                                if (empty($estados)) {
                                    echo '<tr><td colspan="5" style="text-align: center;">No hay estados registrados</td></tr>';
                                } else {
                                    foreach ($estados as $est) {
                                        echo '<tr>';
                                        echo '<td>' . ($est['id'] ?? '-') . '</td>';
                                        echo '<td><strong>' . htmlspecialchars($est['nombre']) . '</strong></td>';
                                        echo '<td>' . htmlspecialchars($est['pais']) . '</td>';
                                        echo '<td>' . htmlspecialchars($est['capital'] ?? '-') . '</td>';
                                        echo '<td>
                                            <div class="action-btns">
                                                <form action="../../../controlador/ctl_estado.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="mostrar" value="mostrar">
                                                    <input type="hidden" name="nombre" value="' . htmlspecialchars($est['nombre']) . '">
                                                    <button type="submit" class="action-btn action-btn-edit">Editar</button>
                                                </form>
                                                <form action="../../../controlador/ctl_estado.php" method="POST" style="display:inline;" onsubmit="return confirm(\'¿Estás seguro de eliminar este estado?\')">
                                                    <input type="hidden" name="eliminar" value="eliminar">
                                                    <input type="hidden" name="nombre" value="' . htmlspecialchars($est['nombre']) . '">
                                                    <button type="submit" class="action-btn action-btn-delete">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- FIN content-right -->
        </main>
    </div>
</body>
</html>