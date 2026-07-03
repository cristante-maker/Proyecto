<?php

class Familia {
    public $id_familiar, $Cedula_Emp, $Numero_Cedula, $Nombre_y_Ap, $Parentesco, $Sexo, $Fecha_Nac, $Nivel_educativo, $Convec_Colectiva;

    public function setIdFamiliar($id_familiar){ $this->id_familiar = $id_familiar; }
    public function setCedulaEmp($Cedula_Emp){ $this->Cedula_Emp = $Cedula_Emp; }
    public function setNumeroCedula($Numero_Cedula){ $this->Numero_Cedula = $Numero_Cedula; }
    public function setNombreyAp($Nombre_y_Ap){ $this->Nombre_y_Ap = $Nombre_y_Ap; }
    public function setParentesco($Parentesco){ $this->Parentesco = $Parentesco; }
    public function setSexo($Sexo){ $this->Sexo = $Sexo; }
    public function setFechaNac($Fecha_Nac){ $this->Fecha_Nac = $Fecha_Nac; }
    public function setNivelEducativo($Nivel_educativo){ $this->Nivel_educativo = $Nivel_educativo; }
    public function setConvecColectiva($Convec_Colectiva){ $this->Convec_Colectiva = $Convec_Colectiva; }

    public function getIdFamiliar(){ return $this->id_familiar; }
    public function getCedulaEmp(){ return $this->Cedula_Emp; }
    public function getNumeroCedula(){ return $this->Numero_Cedula; }
    public function getNombreyAp(){ return $this->Nombre_y_Ap; }
    public function getParentesco(){ return $this->Parentesco; }
    public function getSexo(){ return $this->Sexo; }
    public function getFechaNac(){ return $this->Fecha_Nac; }
    public function getNivelEducativo(){ return $this->Nivel_educativo; }
    public function getConvecColectiva(){ return $this->Convec_Colectiva; }

    public function RegistrarFamiliar($Cedula_Emp, $Numero_Cedula, $Nombre_y_Ap, $Parentesco, $Sexo, $Fecha_Nac, $Nivel_educativo, $Convec_Colectiva){
        include("conexion.php");
        $Fecha_Nac = empty($Fecha_Nac) ? null : $Fecha_Nac;
        $sql = $conex->prepare("INSERT INTO familia (Cedula_Emp, Numero_Cedula, Nombre_y_Ap, Parentesco, Sexo, Fecha_Nac, Nivel_educativo, Convec_Colectiva) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
        $r = $sql->execute([$Cedula_Emp, $Numero_Cedula, $Nombre_y_Ap, $Parentesco, $Sexo, $Fecha_Nac, $Nivel_educativo, $Convec_Colectiva]);
        return $r ? 'ok' : 'error';
    }

    public function ConsultarFamiliar($id_familiar){
        include("conexion.php");
        $sql = $conex->prepare("SELECT * FROM familia WHERE id_familiar = ?;");
        $sql->execute([$id_familiar]);
        $num_reg = $sql->rowCount();
        @$data = $sql->fetch(PDO::FETCH_ASSOC);
        if ($num_reg) {
            return [
                $data['id_familiar'], $data['Cedula_Emp'], $data['Numero_Cedula'], $data['Nombre_y_Ap'],
                $data['Parentesco'], $data['Sexo'], $data['Fecha_Nac'], $data['Nivel_educativo'],
                $data['Convec_Colectiva']
            ];
        }
        return 0;
    }

    public function MostrarFamiliar($id_familiar){ return $this->ConsultarFamiliar($id_familiar); }

    public function ActualizarFamiliar($id_familiar, $Cedula_Emp, $Numero_Cedula, $Nombre_y_Ap, $Parentesco, $Sexo, $Fecha_Nac, $Nivel_educativo, $Convec_Colectiva){
        include("conexion.php");
        $Fecha_Nac = empty($Fecha_Nac) ? null : $Fecha_Nac;
        $sql = $conex->prepare("UPDATE familia SET Cedula_Emp = ?, Numero_Cedula = ?, Nombre_y_Ap = ?, Parentesco = ?, Sexo = ?, Fecha_Nac = ?, Nivel_educativo = ?, Convec_Colectiva = ? WHERE id_familiar = ?;");
        return $sql->execute([$Cedula_Emp, $Numero_Cedula, $Nombre_y_Ap, $Parentesco, $Sexo, $Fecha_Nac, $Nivel_educativo, $Convec_Colectiva, $id_familiar]);
    }

    public function EliminarFamiliar($id_familiar){
        include("conexion.php");
        try {
            $sql = $conex->prepare("SELECT id_familiar FROM familia WHERE id_familiar = ?;");
            $sql->execute([$id_familiar]);
            if ($sql->rowCount()) {
                $sql = $conex->prepare("DELETE FROM familia WHERE id_familiar = ?;");
                return $sql->execute([$id_familiar]);
            }
            return 0;
        } catch (PDOException $e) {
            return 'fk';
        }
    }

    public function ListarFamiliares(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT f.*, e.primer_nombre, e.primer_apellido FROM familia f LEFT JOIN empleado e ON f.Cedula_Emp = e.cedula ORDER BY f.Nombre_y_Ap ASC;");
        $sql->execute();
        return $sql;
    }

    public function ListarEmpleados(){
        include("conexion.php");
        $sql = $conex->prepare("SELECT cedula, primer_nombre, primer_apellido FROM empleado ORDER BY primer_nombre ASC;");
        $sql->execute();
        return $sql;
    }
}
?>
