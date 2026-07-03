<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

$pais_id = isset($_GET['pais_id']) ? (int)$_GET['pais_id'] : 0;

if ($pais_id > 0) {
    $result = $con->query("SELECT id_estado, Nombre FROM estado WHERE id_pais = $pais_id ORDER BY Nombre");
    $estados = [];
    
    while($row = $result->fetch_object()) {
        $estados[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($estados);
} else {
    echo json_encode([]);
}
?>