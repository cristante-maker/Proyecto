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
    
    if (empty($data['id_idioma']) || $data['id_idioma'] <= 0) {
        $errors['id_idioma'] = 'Debe seleccionar un idioma';
    }
    
    // Validar que al menos un campo de nivel esté marcado
    $tieneNivel = false;
    $niveles = ['Comprende', 'Habla', 'Lee', 'Escribe'];
    foreach ($niveles as $nivel) {
        if (isset($data[$nivel]) && $data[$nivel] === 'Sí') {
            $tieneNivel = true;
            break;
        }
    }
    
    if (!$tieneNivel) {
        $errors['nivel'] = 'Debe seleccionar al menos un nivel de dominio (Comprende, Habla, Lee o Escribe)';
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
$id_idioma = isset($_POST['id_idioma']) ? (int)$_POST['id_idioma'] : 0;
$comprende = isset($_POST['Comprende']) ? $_POST['Comprende'] : 'No';
$habla = isset($_POST['Habla']) ? $_POST['Habla'] : 'No';
$lee = isset($_POST['Lee']) ? $_POST['Lee'] : 'No';
$escribe = isset($_POST['Escribe']) ? $_POST['Escribe'] : 'No';

// ============================================
// REGISTRAR
// ============================================
if ($accion === 'registrar') {
    // Validar campos
    $data = [
        'ced_empleado' => $ced_empleado,
        'id_idioma' => $id_idioma,
        'Comprende' => $comprende,
        'Habla' => $habla,
        'Lee' => $lee,
        'Escribe' => $escribe
    ];
    
    $errors = validarCampos($data);
    if (!empty($errors)) {
        $mensaje = implode('\n', $errors);
        redirectWithAlert($mensaje, 'crud_dominio_idioma.php');
    }
    
    // Verificar si el empleado existe
    $checkEmpleado = $con->query("SELECT Cedula FROM empleado WHERE Cedula = '$ced_empleado'");
    if ($checkEmpleado->num_rows === 0) {
        redirectWithAlert('El empleado seleccionado no existe', 'crud_dominio_idioma.php');
    }
    
    // Verificar si el idioma existe
    $checkIdioma = $con->query("SELECT id_idioma FROM idioma WHERE id_idioma = $id_idioma");
    if ($checkIdioma->num_rows === 0) {
        redirectWithAlert('El idioma seleccionado no existe', 'crud_dominio_idioma.php');
    }
    
    // Verificar si ya tiene ese idioma registrado
    $checkDom = $con->query("SELECT id_dominio FROM dominio WHERE Cedula_Emp = '$ced_empleado' AND id_idioma = $id_idioma");
    if ($checkDom->num_rows > 0) {
        $nombre = obtenerNombreEmpleado($con, $ced_empleado);
        $idioma = $con->query("SELECT Nombre FROM idioma WHERE id_idioma = $id_idioma")->fetch_object()->Nombre;
        redirectWithAlert("El empleado $nombre ya tiene registrado el idioma $idioma", 'crud_dominio_idioma.php');
    }
    
    // Insertar
    $sql = "INSERT INTO dominio (id_idioma, Cedula_Emp, Comprende, Habla, Lee, Escribe) 
            VALUES ($id_idioma, '$ced_empleado', '$comprende', '$habla', '$lee', '$escribe')";
    
    if ($con->query($sql)) {
        redirectWithAlert('Dominio de idioma registrado exitosamente', 'crud_dominio_idioma.php');
    } else {
        redirectWithAlert('Error al registrar: ' . $con->error, 'crud_dominio_idioma.php');
    }
}

// ============================================
// MODIFICAR
// ============================================
if ($accion === 'modificar') {
    $id_dominio = isset($_POST['id_dominio']) ? (int)$_POST['id_dominio'] : 0;
    
    // Validar campos
    $data = [
        'ced_empleado' => $ced_empleado,
        'id_idioma' => $id_idioma,
        'Comprende' => $comprende,
        'Habla' => $habla,
        'Lee' => $lee,
        'Escribe' => $escribe
    ];
    
    $errors = validarCampos($data);
    if (!empty($errors)) {
        $mensaje = implode('\n', $errors);
        redirectWithAlert($mensaje, "crud_mdf_dominio_idioma.php?id=$id_dominio");
    }
    
    // Verificar si el empleado existe
    $checkEmpleado = $con->query("SELECT Cedula FROM empleado WHERE Cedula = '$ced_empleado'");
    if ($checkEmpleado->num_rows === 0) {
        redirectWithAlert('El empleado seleccionado no existe', "crud_mdf_dominio_idioma.php?id=$id_dominio");
    }
    
    // Verificar si el idioma existe
    $checkIdioma = $con->query("SELECT id_idioma FROM idioma WHERE id_idioma = $id_idioma");
    if ($checkIdioma->num_rows === 0) {
        redirectWithAlert('El idioma seleccionado no existe', "crud_mdf_dominio_idioma.php?id=$id_dominio");
    }
    
    // Actualizar
    $sql = "UPDATE dominio 
            SET id_idioma = $id_idioma, 
                Cedula_Emp = '$ced_empleado',
                Comprende = '$comprende', 
                Habla = '$habla', 
                Lee = '$lee', 
                Escribe = '$escribe' 
            WHERE id_dominio = $id_dominio";
    
    if ($con->query($sql)) {
        redirectWithAlert('Dominio de idioma modificado exitosamente', 'crud_dominio_idioma.php');
    } else {
        redirectWithAlert('Error al modificar: ' . $con->error, "crud_mdf_dominio_idioma.php?id=$id_dominio");
    }
}

// ============================================
// ELIMINAR
// ============================================
if ($accion === 'eliminar') {
    $id_dominio = isset($_POST['id_dominio']) ? (int)$_POST['id_dominio'] : 0;
    
    if ($id_dominio <= 0) {
        redirectWithAlert('ID inválido', 'crud_dominio_idioma.php');
    }
    
    // Verificar si existe
    $check = $con->query("SELECT id_dominio FROM dominio WHERE id_dominio = $id_dominio");
    if ($check->num_rows === 0) {
        redirectWithAlert('El registro de dominio no existe', 'crud_dominio_idioma.php');
    }
    
    // Eliminar
    if ($con->query("DELETE FROM dominio WHERE id_dominio = $id_dominio")) {
        redirectWithAlert('Dominio de idioma eliminado exitosamente', 'crud_dominio_idioma.php');
    } else {
        redirectWithAlert('Error al eliminar: ' . $con->error, "crud_elm_dominio_idioma.php?id=$id_dominio");
    }
}
?>