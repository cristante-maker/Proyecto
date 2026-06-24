<?php
// controlador/ctl_pais.php

include("../modelo/conexion.php");
include("../modelo/clase_pais.php");

$pais = new Pais();

// ============================================================
// 1. REGISTRAR
// ============================================================
if(isset($_POST['registrar']) && $_POST['registrar'] == "registrar"){
    $nombre = trim($_POST['nombre'] ?? '');
    $codigo = trim($_POST['codigo_iso'] ?? '');
    
    if (empty($nombre)) {
        echo "<script>alert('El nombre del país es obligatorio.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/insert_pais.html'>";
        exit;
    }
    
    $pais->setNombre($nombre);
    $pais->setCodigoIso($codigo);

    $datos = $pais->RegistrarPais(
        $pais->getNombre(),
        $pais->getCodigoIso()
    );

    if($datos == 0){
        echo "<script>alert('No se pudo registrar el país. Verifica que el nombre no esté duplicado.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/insert_pais.html'>";
    } else {
        echo "<script>alert('País registrado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/maestro_pais.php'>";
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
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/select_pais.html'>";
        exit;
    }
    
    $pais->setNombre($nombre);
    $datos = $pais->ConsultarPais($pais->getNombre());

    if($datos == 0){
        echo "<script>alert('No se encontró el país: " . $nombre . "')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/select_pais.html'>";
    } else {
        $nom = base64_encode($datos[0]);
        $iso = base64_encode($datos[1] ?? '');
        // ✅ RUTA CORRECTA
        header("Location: ../vista/Maestros/Maestropais/select_pais.php?b=$nom&c=$iso");
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
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/update_pais.html'>";
        exit;
    }
    
    $pais->setNombre($nombre);
    $datos = $pais->MostrarPais($pais->getNombre());

    if($datos == 0){
        echo "<script>alert('No se encontró el país: " . $nombre . "')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/update_pais.html'>";
    } else {
        $nom = base64_encode($datos[0]);
        $iso = base64_encode($datos[1] ?? '');
        // ✅ RUTA CORRECTA
        header("Location: ../vista/Maestros/Maestropais/update_pais.php?b=$nom&c=$iso");
    }
    exit;
}

// ============================================================
// 4. ACTUALIZAR (MODIFICAR)
// ============================================================
if(isset($_POST['modificar']) && $_POST['modificar'] == "modificar"){
    $nombre_actual = trim($_POST['nombre_actual'] ?? '');
    $nombre_nuevo = trim($_POST['nombre'] ?? '');
    $codigo_iso = trim($_POST['codigo_iso'] ?? '');
    
    if (empty($nombre_actual) || empty($nombre_nuevo)) {
        echo "<script>alert('El nombre es obligatorio.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/update_pais.html'>";
        exit;
    }

    $pais->setNombre($nombre_nuevo);
    $pais->setCodigoIso($codigo_iso);

    $datos = $pais->ActualizarPais(
        $nombre_actual,
        $pais->getNombre(),
        $pais->getCodigoIso()
    );

    if($datos == 0){
        echo "<script>alert('No se pudo actualizar el país. Verifica que el nuevo nombre no esté duplicado.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/update_pais.html'>";
    } else {
        echo "<script>alert('País actualizado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/maestro_pais.php'>";
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
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/delete_pais.html'>";
        exit;
    }
    
    $pais->setNombre($nombre);
    $datos = $pais->EliminarPais($pais->getNombre());

    if($datos == 0){
        echo "<script>alert('No se pudo eliminar el país. Verifica que el nombre exista.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/delete_pais.html'>";
    } else {
        echo "<script>alert('País eliminado con éxito.')</script>";
        echo "<META HTTP-EQUIV='refresh' CONTENT='0; URL=../vista/Maestros/Maestropais/maestro_pais.php'>";
    }
    exit;
}
?>