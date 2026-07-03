<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/SystemRRHH/modelo/conexion.php");
$con = conexion();

// ============================================
// FUNCIONES AUXILIARES
// ============================================

function redirectWithAlert($message, $url) {
    echo "<script>alert('$message'); window.location.href='$url';</script>";
    exit();
}

function ciudadExiste($con, $nombre, $id_estado, $excluirId = null) {
    $sql = "SELECT id_ciudad FROM ciudad WHERE Nombre = '$nombre' AND id_estado = $id_estado";
    if ($excluirId) {
        $sql .= " AND id_ciudad != $excluirId";
    }
    $result = $con->query($sql);
    return $result->num_rows > 0;
}

function validarNombre($nombre) {
    $nombre = trim($nombre);
    return !empty($nombre) ? $nombre : null;
}

// ============================================
// PROCESAR ACCIONES
// ============================================

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// Si no hay acción, salir sin hacer nada
if (empty($accion)) {
    return;
}

$nombre = validarNombre($_POST['nombre'] ?? '');
$id_estado = isset($_POST['id_estado']) ? (int)$_POST['id_estado'] : 0;

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    if (!$nombre) {
        redirectWithAlert('El nombre de la ciudad es obligatorio', 'crud_ciudad.php');
    }
    
    if ($id_estado <= 0) {
        redirectWithAlert('Debes seleccionar un estado', 'crud_ciudad.php');
    }
    
    // Verificar si la ciudad ya existe en ese estado
    if (ciudadExiste($con, $nombre, $id_estado)) {
        redirectWithAlert('La ciudad ya existe en este estado', 'crud_ciudad.php');
    }
    
    $result = $con->query("INSERT INTO ciudad (Nombre, id_estado) VALUES ('$nombre', $id_estado)");
    $mensaje = $result ? 'Ciudad registrada exitosamente' : 'Error al registrar: ' . $con->error;
    redirectWithAlert($mensaje, 'crud_ciudad.php');
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if (!$nombre) {
        redirectWithAlert('El nombre de la ciudad es obligatorio', "crud_mdf_ciudad.php?id=$id");
    }
    
    if ($id_estado <= 0) {
        redirectWithAlert('Debes seleccionar un estado', "crud_mdf_ciudad.php?id=$id");
    }
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', 'crud_ciudad.php');
    }
    
    // Verificar si la ciudad ya existe en ese estado (excluyendo la actual)
    if (ciudadExiste($con, $nombre, $id_estado, $id)) {
        redirectWithAlert('La ciudad ya existe en este estado', "crud_mdf_ciudad.php?id=$id");
    }
    
    $result = $con->query("UPDATE ciudad SET Nombre = '$nombre', id_estado = $id_estado WHERE id_ciudad = $id");
    $mensaje = $result ? 'Ciudad modificada exitosamente' : 'Error al modificar: ' . $con->error;
    $url = $result ? 'crud_ciudad.php' : "crud_mdf_ciudad.php?id=$id";
    redirectWithAlert($mensaje, $url);
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', 'crud_ciudad.php');
    }
    
    // Verificar si la ciudad existe con sus relaciones
    $check = $con->query("SELECT c.Nombre as ciudad_nombre, e.Nombre as estado_nombre, p.Nombre as pais_nombre 
                          FROM ciudad c 
                          INNER JOIN estado e ON c.id_estado = e.id_estado 
                          INNER JOIN pais p ON e.id_pais = p.id_pais 
                          WHERE c.id_ciudad = $id");
    if ($check->num_rows === 0) {
        redirectWithAlert('La ciudad no existe', 'crud_ciudad.php');
    }
    
    $ciudad = $check->fetch_object();
    
    // Eliminar
    if ($con->query("DELETE FROM ciudad WHERE id_ciudad = $id")) {
        redirectWithAlert("Ciudad \"$ciudad->ciudad_nombre\" de $ciudad->estado_nombre, $ciudad->pais_nombre eliminada exitosamente", 'crud_ciudad.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "crud_elm_ciudad.php?id=$id");
    }
}
?>