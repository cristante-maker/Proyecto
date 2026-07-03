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

function idiomaExiste($con, $nombre, $excluirId = null) {
    $sql = "SELECT id_idioma FROM idioma WHERE Nombre = '$nombre'";
    if ($excluirId) {
        $sql .= " AND id_idioma != $excluirId";
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

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    if (!$nombre) {
        redirectWithAlert('El nombre del idioma es obligatorio', 'crud_idioma.php');
    }
    
    if (idiomaExiste($con, $nombre)) {
        redirectWithAlert('El idioma ya existe', 'crud_idioma.php');
    }
    
    $result = $con->query("INSERT INTO idioma (Nombre) VALUES ('$nombre')");
    $mensaje = $result ? 'Idioma registrado exitosamente' : 'Error al registrar: ' . $con->error;
    redirectWithAlert($mensaje, 'crud_idioma.php');
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if (!$nombre) {
        redirectWithAlert('El nombre del idioma es obligatorio', "crud_mdf_idioma.php?id=$id");
    }
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', 'crud_idioma.php');
    }
    
    if (idiomaExiste($con, $nombre, $id)) {
        redirectWithAlert('El idioma ya existe', "crud_mdf_idioma.php?id=$id");
    }
    
    $result = $con->query("UPDATE idioma SET Nombre = '$nombre' WHERE id_idioma = $id");
    $mensaje = $result ? 'Idioma modificado exitosamente' : 'Error al modificar: ' . $con->error;
    $url = $result ? 'crud_idioma.php' : "crud_mdf_idioma.php?id=$id";
    redirectWithAlert($mensaje, $url);
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        redirectWithAlert('ID inválido', 'crud_idioma.php');
    }
    
    // Verificar si el idioma existe
    $check = $con->query("SELECT Nombre FROM idioma WHERE id_idioma = $id");
    if ($check->num_rows === 0) {
        redirectWithAlert('El idioma no existe', 'crud_idioma.php');
    }
    
    $idioma = $check->fetch_object();
    
    // Eliminar
    if ($con->query("DELETE FROM idioma WHERE id_idioma = $id")) {
        redirectWithAlert("Idioma \"$idioma->Nombre\" eliminado exitosamente", 'crud_idioma.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "crud_elm_idioma.php?id=$id");
    }
}
?>