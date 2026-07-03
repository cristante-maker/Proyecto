<?php
include("../modelo/conexion.php");
include("../modelo/clase_familia.php");
$fam = new Familia();

if(isset($_POST['registrar']) && $_POST['registrar']=="registrar"){
    $fam->setCedulaEmp($_POST['Cedula_Emp']);
    $fam->setNumeroCedula($_POST['Numero_Cedula']);
    $fam->setNombreyAp($_POST['Nombre_y_Ap']);
    $fam->setParentesco($_POST['Parentesco']);
    $fam->setSexo($_POST['Sexo']);
    $fam->setFechaNac($_POST['Fecha_Nac']);
    $fam->setNivelEducativo($_POST['Nivel_educativo']);
    $fam->setConvecColectiva($_POST['Convec_Colectiva']);

    $datos = $fam->RegistrarFamiliar(
        $fam->getCedulaEmp(), $fam->getNumeroCedula(), $fam->getNombreyAp(), $fam->getParentesco(),
        $fam->getSexo(), $fam->getFechaNac(), $fam->getNivelEducativo(), $fam->getConvecColectiva()
    );

    if($datos === 'ok'){
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php?msg=" . urlencode('Familiar registrado con éxito.'));
        exit;
    } else {
        header("Location: ../vista/Maestros/maestrofamilia/insert_familia.php?msg=" . urlencode('Error al registrar. Intente nuevamente.'));
        exit;
    }
}

if(isset($_GET['L']) && $_GET['L']=="lis"){
    $datos = $fam->ListarFamiliares();
    if(empty($datos)){
        header("Location: ../vista/Maestros/maestrofamilia/insert_familia.php");
        exit;
    }else{
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php");
        exit;
    }
}

if(isset($_GET['C']) && $_GET['C']=="con"){
    $ci = base64_decode($_GET['I']);
    $fam->setIdFamiliar($ci);
    $datos = $fam->ConsultarFamiliar($fam->getIdFamiliar());

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

        header("Location: ../vista/Maestros/maestrofamilia/select_familia.php?a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=$g&h=$h&i=$i");
        exit;
    }
}

if(isset($_GET['M']) && $_GET['M']=="mos"){
    $ci = base64_decode($_GET['I']);
    $fam->setIdFamiliar($ci);
    $datos = $fam->MostrarFamiliar($fam->getIdFamiliar());

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

        header("Location: ../vista/Maestros/maestrofamilia/update_familia.php?a=$a&b=$b&c=$c&d=$d&e=$e&f=$f&g=$g&h=$h&i=$i");
        exit;
    }
}

if(isset($_POST['modificar']) && $_POST['modificar']=="modificar"){
    $fam->setIdFamiliar($_POST['id_familiar']);
    $fam->setCedulaEmp($_POST['Cedula_Emp']);
    $fam->setNumeroCedula($_POST['Numero_Cedula']);
    $fam->setNombreyAp($_POST['Nombre_y_Ap']);
    $fam->setParentesco($_POST['Parentesco']);
    $fam->setSexo($_POST['Sexo']);
    $fam->setFechaNac($_POST['Fecha_Nac']);
    $fam->setNivelEducativo($_POST['Nivel_educativo']);
    $fam->setConvecColectiva($_POST['Convec_Colectiva']);

    $datos = $fam->ActualizarFamiliar(
        $fam->getIdFamiliar(), $fam->getCedulaEmp(), $fam->getNumeroCedula(), $fam->getNombreyAp(),
        $fam->getParentesco(), $fam->getSexo(), $fam->getFechaNac(), $fam->getNivelEducativo(), $fam->getConvecColectiva()
    );

    if(empty($datos)){
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php?msg=" . urlencode('No se pudo actualizar.'));
        exit;
    } else {
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php?msg=" . urlencode('Familiar actualizado con éxito.'));
        exit;
    }
}

if(isset($_GET['E']) && $_GET['E']=="eli"){
    $ci = base64_decode($_GET['I']);
    $fam->setIdFamiliar($ci);
    $datos = $fam->EliminarFamiliar($fam->getIdFamiliar());

    if($datos === 'fk'){
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php?msg=" . urlencode('No se puede eliminar: este familiar tiene registros asociados.'));
        exit;
    } elseif(!empty($datos)){
        header("Location: ../vista/Maestros/maestrofamilia/maestro_familia.php?msg=" . urlencode('Familiar eliminado con éxito.'));
        exit;
    }
}
?>
