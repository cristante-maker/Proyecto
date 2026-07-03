<?php

class Empleado {
    public $cedula, $id_ciudad, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre;
    public $nacionalidad, $sexo, $fecha_nac, $estado_civil, $edad, $codigo_postal;
    public $num_telefonico, $estatura, $peso, $grp_sanguineo, $hab_manual, $numeros_hijos;

    public function setCedula($cedula){ $this->cedula = $cedula; }
    public function setIdCiudad($id_ciudad){ $this->id_ciudad = $id_ciudad; }
    public function setPrimerApellido($primer_apellido){ $this->primer_apellido = $primer_apellido; }
    public function setSegundoApellido($segundo_apellido){ $this->segundo_apellido = $segundo_apellido; }
    public function setPrimerNombre($primer_nombre){ $this->primer_nombre = $primer_nombre; }
    public function setSegundoNombre($segundo_nombre){ $this->segundo_nombre = $segundo_nombre; }
    public function setNacionalidad($nacionalidad){ $this->nacionalidad = $nacionalidad; }
    public function setSexo($sexo){ $this->sexo = $sexo; }
    public function setFechaNac($fecha_nac){ $this->fecha_nac = $fecha_nac; }
    public function setEstadoCivil($estado_civil){ $this->estado_civil = $estado_civil; }
    public function setEdad($edad){ $this->edad = $edad; }
    public function setCodigoPostal($codigo_postal){ $this->codigo_postal = $codigo_postal; }
    public function setNumTelefonico($num_telefonico){ $this->num_telefonico = $num_telefonico; }
    public function setEstatura($estatura){ $this->estatura = $estatura; }
    public function setPeso($peso){ $this->peso = $peso; }
    public function setGrpSanguineo($grp_sanguineo){ $this->grp_sanguineo = $grp_sanguineo; }
    public function setHabManual($hab_manual){ $this->hab_manual = $hab_manual; }
    public function setNumerosHijos($numeros_hijos){ $this->numeros_hijos = $numeros_hijos; }

    public function getCedula(){ return $this->cedula; }
    public function getIdCiudad(){ return $this->id_ciudad; }
    public function getPrimerApellido(){ return $this->primer_apellido; }
    public function getSegundoApellido(){ return $this->segundo_apellido; }
    public function getPrimerNombre(){ return $this->primer_nombre; }
    public function getSegundoNombre(){ return $this->segundo_nombre; }
    public function getNacionalidad(){ return $this->nacionalidad; }
    public function getSexo(){ return $this->sexo; }
    public function getFechaNac(){ return $this->fecha_nac; }
    public function getEstadoCivil(){ return $this->estado_civil; }
    public function getEdad(){ return $this->edad; }
    public function getCodigoPostal(){ return $this->codigo_postal; }
    public function getNumTelefonico(){ return $this->num_telefonico; }
    public function getEstatura(){ return $this->estatura; }
    public function getPeso(){ return $this->peso; }
    public function getGrpSanguineo(){ return $this->grp_sanguineo; }
    public function getHabManual(){ return $this->hab_manual; }
    public function getNumerosHijos(){ return $this->numeros_hijos; }

    public function RegistrarEmpleado($cedula, $id_ciudad, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $nacionalidad, $sexo, $fecha_nac, $estado_civil, $edad, $codigo_postal, $num_telefonico, $estatura, $peso, $grp_sanguineo, $hab_manual, $numeros_hijos){
        include("conexion.php");
        $fecha_nac = empty($fecha_nac) ? null : $fecha_nac;
        $sql = $conex->prepare("SELECT cedula FROM empleado WHERE cedula = ?;");
        $sql->execute([$cedula]);
        if ($sql->rowCount() > 0) return 'existe';
        $sql = $conex->prepare("INSERT INTO empleado (cedula, id_ciudad, primer_apellido, segundo_apellido, primer_nombre, segundo_nombre, nacionalidad, sexo, fecha_nac, estado_civil, edad, codigo_postal, num_telefonico, estatura, peso, grp_sanguineo, hab_manual, numeros_hijos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $r = $sql->execute([$cedula, $id_ciudad, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $nacionalidad, $sexo, $fecha_nac, $estado_civil, $edad, $codigo_postal, $num_telefonico, $estatura, $peso, $grp_sanguineo, $hab_manual, $numeros_hijos]);
        return $r ? 'ok' : 'error';
    }

    public function ConsultarEmpleado($cedula){
        include("conexion.php");
        $sql = $conex->prepare("SELECT * FROM empleado WHERE cedula = ?;");
        $sql->execute([$cedula]);
        $num_reg = $sql->rowCount();
        @$data = $sql->fetch(PDO::FETCH_ASSOC);
        if ($num_reg) {
            return [
                $data['Cedula'], $data['id_ciudad'], $data['Primer_apellido'], $data['Segundo_apellido'],
                $data['Primer_Nombre'], $data['Segundo_Nombre'], $data['Nacionalidad'], $data['Sexo'],
                $data['Fecha_Nac'], $data['Estado_Civil'], $data['Edad'], $data['Codigo_postal'],
                $data['Num_Telefonico'], $data['Estatura'], $data['Peso'], $data['Grp_Sanguineo'],
                $data['Hab_manual'], $data['Numeros_hijos']
            ];
        }
        return 0;
    }

    public function MostrarEmpleado($cedula){ return $this->ConsultarEmpleado($cedula); }

    public function ActualizarEmpleado($cedula, $id_ciudad, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $nacionalidad, $sexo, $fecha_nac, $estado_civil, $edad, $codigo_postal, $num_telefonico, $estatura, $peso, $grp_sanguineo, $hab_manual, $numeros_hijos){
        include("conexion.php");
        $fecha_nac = empty($fecha_nac) ? null : $fecha_nac;
        $sql = $conex->prepare("UPDATE empleado SET id_ciudad = ?, primer_apellido = ?, segundo_apellido = ?, primer_nombre = ?, segundo_nombre = ?, nacionalidad = ?, sexo = ?, fecha_nac = ?, estado_civil = ?, edad = ?, codigo_postal = ?, num_telefonico = ?, estatura = ?, peso = ?, grp_sanguineo = ?, hab_manual = ?, numeros_hijos = ? WHERE cedula = ?;");
        return $sql->execute([$id_ciudad, $primer_apellido, $segundo_apellido, $primer_nombre, $segundo_nombre, $nacionalidad, $sexo, $fecha_nac, $estado_civil, $edad, $codigo_postal, $num_telefonico, $estatura, $peso, $grp_sanguineo, $hab_manual, $numeros_hijos, $cedula]);
    }

    public function EliminarEmpleado($cedula){
        include("conexion.php");
        try {
            $sql = $conex->prepare("SELECT cedula FROM empleado WHERE cedula = ?;");
            $sql->execute([$cedula]);
            if ($sql->rowCount()) {
                $sql = $conex->prepare("DELETE FROM empleado WHERE cedula = ?;");
                return $sql->execute([$cedula]);
            }
            return 0;
        } catch (PDOException $e) {
            return 'fk';
        }
    }

    public function ListarEmpleados(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT * FROM empleado ORDER BY primer_nombre, primer_apellido ASC;");
        $sql->execute();
        return $sql;
    }

    public function ListarCiudades(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT id_ciudad, nombre FROM ciudad ORDER BY nombre ASC;");
        $sql->execute();
        return $sql;
    }
}
?>
