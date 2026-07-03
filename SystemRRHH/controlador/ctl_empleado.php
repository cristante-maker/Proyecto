<?php
include("../modelo/conexion.php");
include("../modelo/clase_empleado.php");
$emp = new Empleado();

if(isset($_POST['registrar']) && $_POST['registrar']=="registrar"){
    $emp->setCedula($_POST['cedula']);
    $emp->setIdCiudad($_POST['id_ciudad']);
    $emp->setPrimerApellido($_POST['primer_apellido']);
    $emp->setSegundoApellido($_POST['segundo_apellido']);
    $emp->setPrimerNombre($_POST['primer_nombre']);
    $emp->setSegundoNombre($_POST['segundo_nombre']);
    $emp->setNacionalidad($_POST['nacionalidad']);
    $emp->setSexo($_POST['sexo']);
    $emp->setFechaNac($_POST['fecha_nac']);
    $fecha_nac = $_POST['fecha_nac'];
    $edad = 0;
    if (!empty($fecha_nac)) {
        $cumple = new DateTime($fecha_nac);
        $hoy = new DateTime();
        $edad = $hoy->diff($cumple)->y;
    }
    $emp->setEdad($edad);
    $emp->setEstadoCivil($_POST['estado_civil']);
    $emp->setCodigoPostal($_POST['codigo_postal']);
    $emp->setNumTelefonico($_POST['num_telefonico']);
    $emp->setEstatura($_POST['estatura']);
    $emp->setPeso($_POST['peso']);
    $emp->setGrpSanguineo($_POST['grp_sanguineo']);
    $emp->setHabManual($_POST['hab_manual']);
    $emp->setNumerosHijos($_POST['numeros_hijos']);

    $datos = $emp->RegistrarEmpleado(
        $emp->getCedula(), $emp->getIdCiudad(), $emp->getPrimerApellido(), $emp->getSegundoApellido(),
        $emp->getPrimerNombre(), $emp->getSegundoNombre(), $emp->getNacionalidad(), $emp->getSexo(),
        $emp->getFechaNac(), $emp->getEstadoCivil(), $emp->getEdad(), $emp->getCodigoPostal(),
        $emp->getNumTelefonico(), $emp->getEstatura(), $emp->getPeso(), $emp->getGrpSanguineo(),
        $emp->getHabManual(), $emp->getNumerosHijos()
    );

    if($datos === 'existe'){
        header("Location: ../vista/Maestros/maestroempleado/insert_empleado.php?msg=" . urlencode('No se pudo registrar. Esa cédula ya existe.'));
        exit;
    } elseif($datos === 'ok'){
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php?msg=" . urlencode('Empleado registrado con éxito.'));
        exit;
    } else {
        header("Location: ../vista/Maestros/maestroempleado/insert_empleado.php?msg=" . urlencode('Error al registrar. Intente nuevamente.'));
        exit;
    }
}

if(isset($_GET['L']) && $_GET['L']=="lis"){
    $datos = $emp->ListarEmpleados();
    if(empty($datos)){
        header("Location: ../vista/Maestros/maestroempleado/insert_empleado.php");
        exit;
    }else{
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php");
        exit;
    }
}

if(isset($_GET['C']) && $_GET['C']=="con"){
    $ci = base64_decode($_GET['I']);
    $emp->setCedula($ci);
    $datos = $emp->ConsultarEmpleado($emp->getCedula());

    if(!empty($datos)){
        $a = urlencode(base64_encode($datos[0]));
        $b = urlencode(base64_encode($datos[1]));
        $c = urlencode(base64_encode($datos[2]));
        $d = urlencode(base64_encode($datos[3]));
        $e = urlencode(base64_encode($datos[4]));
        $f = urlencode(base64_encode($datos[5]));
        $g = urlencode(base64_encode($datos[6]));
        $h = urlencode(base64_encode($datos[7]));
        $i = urlencode(base64_encode($datos[8]));
        $j = urlencode(base64_encode($datos[9]));
        $k = urlencode(base64_encode($datos[10]));
        $l = urlencode(base64_encode($datos[11]));
        $m = urlencode(base64_encode($datos[12]));
        $n = urlencode(base64_encode($datos[13]));
        $o = urlencode(base64_encode($datos[14]));
        $p = urlencode(base64_encode($datos[15]));
        $q = urlencode(base64_encode($datos[16]));
        $r = urlencode(base64_encode($datos[17]));

        header("Location: ../vista/Maestros/maestroempleado/select_empleado.php?a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=$g&h=$h&i=$i&j=$j&k=$k&l=$l&m=$m&n=$n&o=$o&p=$p&q=$q&r=$r");
        exit;
    }
}

if(isset($_GET['M']) && $_GET['M']=="mos"){
    $ci = base64_decode($_GET['I']);
    $emp->setCedula($ci);
    $datos = $emp->MostrarEmpleado($emp->getCedula());

    if(!empty($datos)){
        $a = urlencode(base64_encode($datos[0]));
        $b = urlencode(base64_encode($datos[1]));
        $c = urlencode(base64_encode($datos[2]));
        $d = urlencode(base64_encode($datos[3]));
        $e = urlencode(base64_encode($datos[4]));
        $f = urlencode(base64_encode($datos[5]));
        $g = urlencode(base64_encode($datos[6]));
        $h = urlencode(base64_encode($datos[7]));
        $i = urlencode(base64_encode($datos[8]));
        $j = urlencode(base64_encode($datos[9]));
        $k = urlencode(base64_encode($datos[10]));
        $l = urlencode(base64_encode($datos[11]));
        $m = urlencode(base64_encode($datos[12]));
        $n = urlencode(base64_encode($datos[13]));
        $o = urlencode(base64_encode($datos[14]));
        $p = urlencode(base64_encode($datos[15]));
        $q = urlencode(base64_encode($datos[16]));
        $r = urlencode(base64_encode($datos[17]));

        header("Location: ../vista/Maestros/maestroempleado/update_empleado.php?a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=$g&h=$h&i=$i&j=$j&k=$k&l=$l&m=$m&n=$n&o=$o&p=$p&q=$q&r=$r");
        exit;
    }
}

if(isset($_POST['modificar']) && $_POST['modificar']=="modificar"){
    $emp->setCedula($_POST['cedula']);
    $emp->setIdCiudad($_POST['id_ciudad']);
    $emp->setPrimerApellido($_POST['primer_apellido']);
    $emp->setSegundoApellido($_POST['segundo_apellido']);
    $emp->setPrimerNombre($_POST['primer_nombre']);
    $emp->setSegundoNombre($_POST['segundo_nombre']);
    $emp->setNacionalidad($_POST['nacionalidad']);
    $emp->setSexo($_POST['sexo']);
    $emp->setFechaNac($_POST['fecha_nac']);
    $fecha_nac = $_POST['fecha_nac'];
    $edad = 0;
    if (!empty($fecha_nac)) {
        $cumple = new DateTime($fecha_nac);
        $hoy = new DateTime();
        $edad = $hoy->diff($cumple)->y;
    }
    $emp->setEdad($edad);
    $emp->setEstadoCivil($_POST['estado_civil']);
    $emp->setCodigoPostal($_POST['codigo_postal']);
    $emp->setNumTelefonico($_POST['num_telefonico']);
    $emp->setEstatura($_POST['estatura']);
    $emp->setPeso($_POST['peso']);
    $emp->setGrpSanguineo($_POST['grp_sanguineo']);
    $emp->setHabManual($_POST['hab_manual']);
    $emp->setNumerosHijos($_POST['numeros_hijos']);

    $datos = $emp->ActualizarEmpleado(
        $emp->getCedula(), $emp->getIdCiudad(), $emp->getPrimerApellido(), $emp->getSegundoApellido(),
        $emp->getPrimerNombre(), $emp->getSegundoNombre(), $emp->getNacionalidad(), $emp->getSexo(),
        $emp->getFechaNac(), $emp->getEstadoCivil(), $emp->getEdad(), $emp->getCodigoPostal(),
        $emp->getNumTelefonico(), $emp->getEstatura(), $emp->getPeso(), $emp->getGrpSanguineo(),
        $emp->getHabManual(), $emp->getNumerosHijos()
    );

    if(empty($datos)){
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php?msg=" . urlencode('No se pudo actualizar.'));
        exit;
    } else {
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php?msg=" . urlencode('Empleado actualizado con éxito.'));
        exit;
    }
}

if(isset($_GET['E']) && $_GET['E']=="eli"){
    $ci = base64_decode($_GET['I']);
    $emp->setCedula($ci);
    $datos = $emp->EliminarEmpleado($emp->getCedula());

    if($datos === 'fk'){
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php?msg=" . urlencode('No se puede eliminar: este empleado tiene registros asociados.'));
        exit;
    } elseif(!empty($datos)){
        header("Location: ../vista/Maestros/maestroempleado/maestro_empleado.php?msg=" . urlencode('Empleado eliminado con éxito.'));
        exit;
    }
}
?>
