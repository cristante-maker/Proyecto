<?php
// modelo/clase_pais.php

class Pais {
    public $nombre, $codigo_iso;

    public function setNombre($nombre){ 
        $this->nombre = trim($nombre); 
    }
    
    public function setCodigoIso($codigo_iso){ 
        $this->codigo_iso = strtoupper(trim($codigo_iso)); 
    }
    
    public function getNombre(){ 
        return $this->nombre; 
    }
    
    public function getCodigoIso(){ 
        return $this->codigo_iso; 
    }

    // ============================================
    // 1. REGISTRAR PAÍS
    // ============================================
    public function RegistrarPais($nombre, $codigo_iso){
        include("conexion.php");

        try {
            // ✅ TABLA "pais" (sin "es")
            $sql = $conex->prepare("SELECT nombre FROM pais WHERE nombre = ?;");
            $sql->execute([$nombre]);
            $existe = $sql->rowCount();

            if ($existe > 0) {
                return 0;
            }

            $sql = $conex->prepare("INSERT INTO pais (nombre, codigo_iso) VALUES (?, ?);");
            $insertar = $sql->execute([$nombre, $codigo_iso]);
            return $insertar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en RegistrarPais: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 2. CONSULTAR PAÍS
    // ============================================
    public function ConsultarPais($nombre){
        include("conexion.php");

        try {
            // ✅ TABLA "pais" (sin "es")
            $sql = $conex->prepare("SELECT nombre, codigo_iso FROM pais WHERE nombre = ?;");
            $sql->execute([$nombre]);
            $num_reg = $sql->rowCount();

            if ($num_reg > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                return [
                    $data['nombre'],
                    $data['codigo_iso']
                ];
            } else {
                return 0;
            }
            
        } catch (PDOException $e) {
            error_log("Error en ConsultarPais: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 3. MOSTRAR PAÍS (para Actualizar)
    // ============================================
    public function MostrarPais($nombre){
        return $this->ConsultarPais($nombre);
    }

    // ============================================
    // 4. ACTUALIZAR PAÍS
    // ============================================
    public function ActualizarPais($nombre_actual, $nombre_nuevo, $codigo_iso){
        include("conexion.php");

        try {
            if ($nombre_actual != $nombre_nuevo) {
                $sql = $conex->prepare("SELECT nombre FROM pais WHERE nombre = ?;");
                $sql->execute([$nombre_nuevo]);
                if ($sql->rowCount() > 0) {
                    return 0;
                }
            }

            $sql = $conex->prepare("UPDATE pais SET nombre = ?, codigo_iso = ? WHERE nombre = ?;");
            $actualizar = $sql->execute([$nombre_nuevo, $codigo_iso, $nombre_actual]);
            return $actualizar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en ActualizarPais: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 5. ELIMINAR PAÍS
    // ============================================
    public function EliminarPais($nombre){
        include("conexion.php");

        try {
            $sql = $conex->prepare("SELECT nombre FROM pais WHERE nombre = ?;");
            $sql->execute([$nombre]);
            $existe = $sql->rowCount();

            if ($existe == 0) {
                return 0;
            }

            $sql = $conex->prepare("DELETE FROM pais WHERE nombre = ?;");
            $eliminar = $sql->execute([$nombre]);
            return $eliminar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en EliminarPais: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 6. LISTAR TODOS LOS PAÍSES
    // ============================================
    public function ListarPaises(){
        include("conexion.php");

        try {
            $sql = $conex->prepare("SELECT nombre, codigo_iso FROM pais ORDER BY nombre ASC;");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error en ListarPaises: " . $e->getMessage());
            return [];
        }
    }
}
?>