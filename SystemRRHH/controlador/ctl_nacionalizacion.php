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

function validarCampos($data) {
    $errors = [];
    
    if (empty($data['ced_empleado']) || trim($data['ced_empleado']) === '') {
        $errors['ced_empleado'] = 'Debe seleccionar un empleado';
    }
    
    if (empty($data['numero_gaceta']) || trim($data['numero_gaceta']) === '') {
        $errors['numero_gaceta'] = 'El número de gaceta es requerido';
    }
    
    if (empty($data['fecha_gaceta'])) {
        $errors['fecha_gaceta'] = 'La fecha de gaceta es requerida';
    }
    
    if (empty($data['pais_procedente']) || trim($data['pais_procedente']) === '') {
        $errors['pais_procedente'] = 'El país de procedencia es requerido';
    }
    
    return $errors;
}

function obtenerNombreEmpleado($con, $cedula) {
    $query = $con->query("SELECT Primer_Nombre, Segundo_Nombre, Primer_apellido, Segundo_apellido 
                          FROM empleado WHERE Cedula = '$cedula'");
    if ($query && $row = $query->fetch_object()) {
        $nombre = $row->Primer_apellido;
        if ($row->Segundo_apellido) $nombre .= ' ' . $row->Segundo_apellido;
        $nombre .= ', ' . $row->Primer_Nombre;
        if ($row->Segundo_Nombre) $nombre .= ' ' . $row->Segundo_Nombre;
        return $nombre;
    }
    return '';
}

// ============================================
// PROCESAR ACCIONES
// ============================================

$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

// Si no hay acción, salir sin hacer nada
if (empty($accion)) {
    return;
}

$ced_empleado = isset($_POST['ced_empleado']) ? trim($_POST['ced_empleado']) : '';
$numero_gaceta = isset($_POST['numero_gaceta']) ? trim($_POST['numero_gaceta']) : '';
$fecha_gaceta = isset($_POST['fecha_gaceta']) ? $_POST['fecha_gaceta'] : '';
$pais_procedente = isset($_POST['pais_procedente']) ? trim($_POST['pais_procedente']) : '';

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    // Validar campos
    $data = [
        'ced_empleado' => $ced_empleado,
        'numero_gaceta' => $numero_gaceta,
        'fecha_gaceta' => $fecha_gaceta,
        'pais_procedente' => $pais_procedente
    ];
    
    $errors = validarCampos($data);
    if (!empty($errors)) {
        $mensaje = implode('\n', $errors);
        redirectWithAlert($mensaje, 'crud_nacionalizacion.php');
    }
    
    // Verificar si el empleado existe
    $checkEmpleado = $con->query("SELECT Cedula FROM empleado WHERE Cedula = '$ced_empleado'");
    if ($checkEmpleado->num_rows === 0) {
        redirectWithAlert('El empleado seleccionado no existe', 'crud_nacionalizacion.php');
    }
    
    // Verificar si ya tiene nacionalización
    $checkNac = $con->query("SELECT Ced_Empleado FROM nacionalizacion WHERE Ced_Empleado = '$ced_empleado'");
    if ($checkNac->num_rows > 0) {
        $nombre = obtenerNombreEmpleado($con, $ced_empleado);
        redirectWithAlert("El empleado $nombre ya tiene registro de nacionalización", 'crud_nacionalizacion.php');
    }
    
    // Insertar
    $sql = "INSERT INTO nacionalizacion (Ced_Empleado, Numero_gaceta, Fecha_gaceta, Pais_procedente) 
            VALUES ('$ced_empleado', '$numero_gaceta', '$fecha_gaceta', '$pais_procedente')";
    
    if ($con->query($sql)) {
        redirectWithAlert('Nacionalización registrada exitosamente', 'crud_nacionalizacion.php');
    } else {
        redirectWithAlert('Error al registrar: ' . $con->error, 'crud_nacionalizacion.php');
    }
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    $ced_original = isset($_POST['ced_original']) ? trim($_POST['ced_original']) : '';
    
    // Validar campos
    $data = [
        'ced_empleado' => $ced_empleado,
        'numero_gaceta' => $numero_gaceta,
        'fecha_gaceta' => $fecha_gaceta,
        'pais_procedente' => $pais_procedente
    ];
    
    $errors = validarCampos($data);
    if (!empty($errors)) {
        $mensaje = implode('\n', $errors);
        redirectWithAlert($mensaje, "crud_mdf_nacionalizacion.php?ced=$ced_original");
    }
    
    // Verificar si el empleado existe
    $checkEmpleado = $con->query("SELECT Cedula FROM empleado WHERE Cedula = '$ced_empleado'");
    if ($checkEmpleado->num_rows === 0) {
        redirectWithAlert('El empleado seleccionado no existe', "crud_mdf_nacionalizacion.php?ced=$ced_original");
    }
    
    // Si cambió la cédula, verificar que no exista otra nacionalización con esa cédula
    if ($ced_empleado !== $ced_original) {
        $checkNac = $con->query("SELECT Ced_Empleado FROM nacionalizacion WHERE Ced_Empleado = '$ced_empleado'");
        if ($checkNac->num_rows > 0) {
            $nombre = obtenerNombreEmpleado($con, $ced_empleado);
            redirectWithAlert("El empleado $nombre ya tiene registro de nacionalización", "crud_mdf_nacionalizacion.php?ced=$ced_original");
        }
    }
    
    // Actualizar
    $sql = "UPDATE nacionalizacion 
            SET Ced_Empleado = '$ced_empleado', 
                Numero_gaceta = '$numero_gaceta', 
                Fecha_gaceta = '$fecha_gaceta', 
                Pais_procedente = '$pais_procedente' 
            WHERE Ced_Empleado = '$ced_original'";
    
    if ($con->query($sql)) {
        redirectWithAlert('Nacionalización modificada exitosamente', 'crud_nacionalizacion.php');
    } else {
        redirectWithAlert('Error al modificar: ' . $con->error, "crud_mdf_nacionalizacion.php?ced=$ced_original");
    }
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $ced_empleado = isset($_POST['ced_empleado']) ? trim($_POST['ced_empleado']) : '';
    
    if (empty($ced_empleado)) {
        redirectWithAlert('Cédula no proporcionada', 'crud_nacionalizacion.php');
    }
    
    // Verificar si existe
    $check = $con->query("SELECT Ced_Empleado FROM nacionalizacion WHERE Ced_Empleado = '$ced_empleado'");
    if ($check->num_rows === 0) {
        redirectWithAlert('El registro de nacionalización no existe', 'crud_nacionalizacion.php');
    }
    
    // Eliminar
    if ($con->query("DELETE FROM nacionalizacion WHERE Ced_Empleado = '$ced_empleado'")) {
        redirectWithAlert('Nacionalización eliminada exitosamente', 'crud_nacionalizacion.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "crud_elm_nacionalizacion.php?ced=$ced_empleado");
    }
}
?>