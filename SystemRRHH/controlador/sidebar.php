<nav class="sidebar">
    <h2>Sistema de Gestión</h2>
    <ul class="menu-list">
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <span class="icon"><img src="../assets/Iconos/Dashboard.png" alt="Dashboard" class="menu-icon"></span>
                Administración
            </a>
        </li>
        <li class="menu-item <?php echo strpos($_SERVER['PHP_SELF'], 'maestro') !== false ? 'active' : ''; ?>">
            <a href="maestros.php">
                <span class="icon"><img src="../assets/Iconos/Maestros.png" alt="Maestros" class="menu-icon"></span>
                Maestros
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'reclutamiento.php' ? 'active' : ''; ?>">
            <a href="reclutamiento.php">
                <span class="icon"><img src="../assets/Iconos/Reclutamiento.png" alt="Reclutamiento" class="menu-icon"></span>
                Reclutamiento
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'contratacion.php' ? 'active' : ''; ?>">
            <a href="contratacion.php">
                <span class="icon"><img src="../assets/Iconos/Contratacion.png" alt="Contratación" class="menu-icon"></span>
                Contratación
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'asistencia.php' ? 'active' : ''; ?>">
            <a href="asistencia.php">
                <span class="icon"><img src="../assets/Iconos/Asistencia.png" alt="Asistencia" class="menu-icon"></span>
                Asistencia
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'vacaciones.php' ? 'active' : ''; ?>">
            <a href="vacaciones.php">
                <span class="icon"><img src="../assets/Iconos/Vacaciones.png" alt="Vacaciones" class="menu-icon"></span>
                Vacaciones
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'reposos.php' ? 'active' : ''; ?>">
            <a href="reposos.php">
                <span class="icon"><img src="../assets/Iconos/Reposo.png" alt="Reposos" class="menu-icon"></span>
                Reposos
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'permisos.php' ? 'active' : ''; ?>">
            <a href="permisos.php">
                <span class="icon"><img src="../assets/Iconos/Permiso.png" alt="Permisos" class="menu-icon"></span>
                Permisos
            </a>
        </li>
        <li class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'configuracion.php' ? 'active' : ''; ?>">
            <a href="configuracion.php">
                <span class="icon"><img src="../assets/Iconos/Configuracion.png" alt="Configuración" class="menu-icon"></span>
                Configuración
            </a>
        </li>
    </ul>
</nav>