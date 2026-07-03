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

function paisExiste($con, $nombre, $excluirId = null) {
    $sql = "SELECT id_pais FROM pais WHERE Nombre = '$nombre'";
    if ($excluirId) {
        $sql .= " AND id_pais != $excluirId";
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

// Si no hay acción, no hacer nada (salir sin redirigir)
if (empty($accion)) {
    return; // Salir del controlador sin hacer nada
}

$nombre = validarNombre($_POST['nombre'] ?? '');

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    if (!$nombre) {
        redirectWithAlert('El nombre es obligatorio', '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    }
    
    if (paisExiste($con, $nombre)) {
        redirectWithAlert('El país ya existe', '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    }
    
    $result = $con->query("INSERT INTO pais (Nombre) VALUES ('$nombre')");
    $mensaje = $result ? 'País registrado exitosamente' : 'Error al registrar: ' . $con->error;
    redirectWithAlert($mensaje, '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    if (!$nombre) {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        redirectWithAlert('El nombre es obligatorio', "/SystemRRHH/vista/Maestros/Maestropais/crud_mdf_pais.php?id=$id");
    }
    
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        redirectWithAlert('ID inválido', '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    }
    
    if (paisExiste($con, $nombre, $id)) {
        redirectWithAlert('El país ya existe', "/SystemRRHH/vista/Maestros/Maestropais/crud_mdf_pais.php?id=$id");
    }
    
    $result = $con->query("UPDATE pais SET Nombre = '$nombre' WHERE id_pais = $id");
    $mensaje = $result ? 'País modificado exitosamente' : 'Error al modificar: ' . $con->error;
    $url = $result 
        ? '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php'
        : "/SystemRRHH/vista/Maestros/Maestropais/crud_mdf_pais.php?id=$id";
    redirectWithAlert($mensaje, $url);
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        redirectWithAlert('ID inválido', '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    }
    
    // Verificar si el país existe
    $check = $con->query("SELECT Nombre FROM pais WHERE id_pais = $id");
    if ($check->num_rows === 0) {
        redirectWithAlert('El país no existe', '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    }
    
    $pais = $check->fetch_object();
    
    // Eliminar
    if ($con->query("DELETE FROM pais WHERE id_pais = $id")) {
        redirectWithAlert("País \"$pais->Nombre\" eliminado exitosamente", '/SystemRRHH/vista/Maestros/Maestropais/crud_pais.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "/SystemRRHH/vista/Maestros/Maestropais/crud_elm_pais.php?id=$id");
    }
}
?>