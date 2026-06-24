<?php
include("../modelo/conexion.php");
$sql = $conex->query("SELECT * FROM pais");
foreach($sql as $row) {
    echo $row['nombre'] . "<br>";
}
?>