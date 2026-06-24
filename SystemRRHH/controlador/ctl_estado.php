<?php
// controlador/ctl_estado.php

include(__DIR__ . "/../modelo/conexion.php");
include(__DIR__ . "/../modelo/clase_estado.php");

$estado = new Estado();

// ============================================================
// 1. REGISTRAR
// ============================================================
if(isset($_POST['registrar']) && $_POST['registrar'] == "registrar"){
    $nombre = trim($_POST['nombre'] ?? '');
    $pais_id = trim($_POST['pais_id'] ?? 0);
    $capital = trim($_POST['capital'] ?? '');
    
    if (empty($nombre) || $pais_id == 0) {
        echo "<script>alert('El nombre del estado y el país son obligatorios.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/insert_estado.html'>";
        exit;
    }
    
    $estado->setNombre($nombre);
    $estado->setPaisId($pais_id);
    $estado->setCapital($capital);

    $datos = $estado->RegistrarEstado(
        $estado->getNombre(),
        $estado->getPaisId(),
        $estado->getCapital()
    );

    if($datos == 0){
        echo "<script>alert('No se pudo registrar el estado. Verifica que el nombre no esté duplicado.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/insert_estado.html'>";
    } else {
        echo "<script>alert('Estado registrado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/maestro_estado.php'>";
    }
    exit;
}

// ============================================================
// 2. CONSULTAR (por NOMBRE)
// ============================================================
if(isset($_POST['consultar']) && $_POST['consultar'] == "consultar"){
    $nombre = trim($_POST['nombre'] ?? '');
    
    if (empty($nombre)) {
        echo "<script>alert('Debes ingresar un nombre para consultar.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/select_estado.html'>";
        exit;
    }
    
    $estado->setNombre($nombre);
    $datos = $estado->ConsultarEstado($estado->getNombre());

    if($datos == 0){
        echo "<script>alert('No se encontró el estado: " . $nombre . "')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/select_estado.html'>";
    } else {
        $nom = base64_encode($datos[0]);
        $pais = base64_encode($datos[1] ?? '');
        $cap = base64_encode($datos[2] ?? '');
        header("Location: ../vista/Maestros/Maestroestado/select_estado.php?b=$nom&c=$pais&d=$cap");
    }
    exit;
}

// ============================================================
// 3. MOSTRAR (para Actualizar - por NOMBRE)
// ============================================================
if(isset($_POST['mostrar']) && $_POST['mostrar'] == "mostrar"){
    $nombre = trim($_POST['nombre'] ?? '');
    
    if (empty($nombre)) {
        echo "<script>alert('Debes ingresar un nombre para buscar.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/update_estado.html'>";
        exit;
    }
    
    $estado->setNombre($nombre);
    $datos = $estado->MostrarEstado($estado->getNombre());

    if($datos == 0){
        echo "<script>alert('No se encontró el estado: " . $nombre . "')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/update_estado.html'>";
    } else {
        $nom = base64_encode($datos[0]);
        $pais = base64_encode($datos[1] ?? '');
        $cap = base64_encode($datos[2] ?? '');
        header("Location: ../vista/Maestros/Maestroestado/update_estado.php?b=$nom&c=$pais&d=$cap");
    }
    exit;
}

// ============================================================
// 4. ACTUALIZAR (MODIFICAR)
// ============================================================
if(isset($_POST['modificar']) && $_POST['modificar'] == "modificar"){
    $nombre_actual = trim($_POST['nombre_actual'] ?? '');
    $nombre_nuevo = trim($_POST['nombre'] ?? '');
    $pais_id = trim($_POST['pais_id'] ?? 0);
    $capital = trim($_POST['capital'] ?? '');
    
    if (empty($nombre_actual) || empty($nombre_nuevo) || $pais_id == 0) {
        echo "<script>alert('El nombre y el país son obligatorios.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/update_estado.html'>";
        exit;
    }

    $estado->setNombre($nombre_nuevo);
    $estado->setPaisId($pais_id);
    $estado->setCapital($capital);

    $datos = $estado->ActualizarEstado(
        $nombre_actual,
        $estado->getNombre(),
        $estado->getPaisId(),
        $estado->getCapital()
    );

    if($datos == 0){
        echo "<script>alert('No se pudo actualizar el estado. Verifica que el nuevo nombre no esté duplicado.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/update_estado.html'>";
    } else {
        echo "<script>alert('Estado actualizado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/maestro_estado.php'>";
    }
    exit;
}

// ============================================================
// 5. ELIMINAR (por NOMBRE)
// ============================================================
if(isset($_POST['eliminar']) && $_POST['eliminar'] == "eliminar"){
    $nombre = trim($_POST['nombre'] ?? '');
    
    if (empty($nombre)) {
        echo "<script>alert('Debes ingresar un nombre para eliminar.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/delete_estado.html'>";
        exit;
    }
    
    $estado->setNombre($nombre);
    $datos = $estado->EliminarEstado($estado->getNombre());

    if($datos == 0){
        echo "<script>alert('No se pudo eliminar el estado. Verifica que el nombre exista.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/delete_estado.html'>";
    } else {
        echo "<script>alert('Estado eliminado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestroestado/maestro_estado.php'>";
    }
    exit;
}
?>