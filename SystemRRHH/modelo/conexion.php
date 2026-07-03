<?php
// /opt/lampp/htdocs/SystemRRHH/modelo/conexion.php
function conexion() {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $bd = "db_system";

    $connect = mysqli_connect($host, $user, $pass);

    if (!$connect) {
        die("Error de conexión a MySQL: " . mysqli_connect_error());
    }

    if (!mysqli_select_db($connect, $bd)) {
        die("Error al seleccionar la base de datos: " . mysqli_error($connect));
    }

    return $connect;
}
?>