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

function estadoExiste($con, $nombre, $id_pais, $excluirId = null) {
    $sql = "SELECT id_estado FROM estado WHERE Nombre = '$nombre' AND id_pais = $id_pais";
    if ($excluirId) {
        $sql .= " AND id_estado != $excluirId";
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
$id_pais = isset($_POST['id_pais']) ? (int)$_POST['id_pais'] : 0;

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    if (!$nombre) {
        redirectWithAlert('El nombre del estado es obligatorio', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    if ($id_pais <= 0) {
        redirectWithAlert('Debes seleccionar un país', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    // Verificar si el estado ya existe en ese país
    if (estadoExiste($con, $nombre, $id_pais)) {
        redirectWithAlert('El estado ya existe en este país', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    $result = $con->query("INSERT INTO estado (Nombre, id_pais) VALUES ('$nombre', $id_pais)");
    $mensaje = $result ? 'Estado registrado exitosamente' : 'Error al registrar: ' . $con->error;
    redirectWithAlert($mensaje, '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if (!$nombre) {
        redirectWithAlert('El nombre del estado es obligatorio', "/SystemRRHH/vista/Maestros/maestroestado/crud_mdf_estado.php?id=$id");
    }
    
    if ($id_pais <= 0) {
        redirectWithAlert('Debes seleccionar un país', "/SystemRRHH/vista/Maestros/maestroestado/crud_mdf_estado.php?id=$id");
    }
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    // Verificar si el estado ya existe en ese país (excluyendo el actual)
    if (estadoExiste($con, $nombre, $id_pais, $id)) {
        redirectWithAlert('El estado ya existe en este país', "/SystemRRHH/vista/Maestros/maestroestado/crud_mdf_estado.php?id=$id");
    }
    
    $result = $con->query("UPDATE estado SET Nombre = '$nombre', id_pais = $id_pais WHERE id_estado = $id");
    $mensaje = $result ? 'Estado modificado exitosamente' : 'Error al modificar: ' . $con->error;
    $url = $result 
        ? '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php'
        : "/SystemRRHH/vista/Maestros/maestroestado/crud_mdf_estado.php?id=$id";
    redirectWithAlert($mensaje, $url);
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    // Verificar si el estado existe
    $check = $con->query("SELECT e.Nombre, p.Nombre as pais_nombre 
                          FROM estado e 
                          INNER JOIN pais p ON e.id_pais = p.id_pais 
                          WHERE e.id_estado = $id");
    if ($check->num_rows === 0) {
        redirectWithAlert('El estado no existe', '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    }
    
    $estado = $check->fetch_object();
    
    // Eliminar
    if ($con->query("DELETE FROM estado WHERE id_estado = $id")) {
        redirectWithAlert("Estado \"$estado->Nombre\" de $estado->pais_nombre eliminado exitosamente", '/SystemRRHH/vista/Maestros/maestroestado/crud_estado.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "/SystemRRHH/vista/Maestros/maestroestado/crud_elm_estado.php?id=$id");
    }
}
?>