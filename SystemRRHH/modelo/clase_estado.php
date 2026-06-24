<?php
// modelo/clase_estado.php

class Estado {
    public $nombre, $pais_id, $capital;

    public function setNombre($nombre){ 
        $this->nombre = trim($nombre); 
    }
    
    public function setPaisId($pais_id){ 
        $this->pais_id = (int) $pais_id; 
    }
    
    public function setCapital($capital){ 
        $this->capital = trim($capital); 
    }
    
    public function getNombre(){ 
        return $this->nombre; 
    }
    
    public function getPaisId(){ 
        return $this->pais_id; 
    }
    
    public function getCapital(){ 
        return $this->capital; 
    }

    // ============================================
    // 1. REGISTRAR ESTADO
    // ============================================
    public function RegistrarEstado($nombre, $pais_id, $capital){
        include(__DIR__ . "/conexion.php"); // Ruta absoluta para evitar problemas

        try {
            $sql = $conex->prepare("SELECT nombre FROM estado WHERE nombre = ?;");
            $sql->execute([$nombre]);
            if ($sql->rowCount() > 0) {
                return 0; // Ya existe
            }

            $sql = $conex->prepare("INSERT INTO estado (nombre, pais_id, capital) VALUES (?, ?, ?);");
            $insertar = $sql->execute([$nombre, $pais_id, $capital]);
            return $insertar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en RegistrarEstado: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 2. CONSULTAR ESTADO (devuelve también el nombre del país)
    // ============================================
    public function ConsultarEstado($nombre){
        include(__DIR__ . "/conexion.php");

        try {
            $sql = $conex->prepare("
                SELECT e.nombre, p.nombre AS pais, e.capital 
                FROM estado e 
                INNER JOIN pais p ON e.pais_id = p.id 
                WHERE e.nombre = ?
            ");
            $sql->execute([$nombre]);
            if ($sql->rowCount() > 0) {
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                return [
                    $data['nombre'],
                    $data['pais'],
                    $data['capital']
                ];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            error_log("Error en ConsultarEstado: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 3. MOSTRAR ESTADO (para Actualizar)
    // ============================================
    public function MostrarEstado($nombre){
        return $this->ConsultarEstado($nombre);
    }

    // ============================================
    // 4. ACTUALIZAR ESTADO
    // ============================================
    public function ActualizarEstado($nombre_actual, $nombre_nuevo, $pais_id, $capital){
        include(__DIR__ . "/conexion.php");

        try {
            if ($nombre_actual != $nombre_nuevo) {
                $sql = $conex->prepare("SELECT nombre FROM estado WHERE nombre = ?;");
                $sql->execute([$nombre_nuevo]);
                if ($sql->rowCount() > 0) {
                    return 0;
                }
            }

            $sql = $conex->prepare("UPDATE estado SET nombre = ?, pais_id = ?, capital = ? WHERE nombre = ?;");
            $actualizar = $sql->execute([$nombre_nuevo, $pais_id, $capital, $nombre_actual]);
            return $actualizar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en ActualizarEstado: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 5. ELIMINAR ESTADO
    // ============================================
    public function EliminarEstado($nombre){
        include(__DIR__ . "/conexion.php");

        try {
            $sql = $conex->prepare("SELECT nombre FROM estado WHERE nombre = ?;");
            $sql->execute([$nombre]);
            if ($sql->rowCount() == 0) {
                return 0;
            }

            $sql = $conex->prepare("DELETE FROM estado WHERE nombre = ?;");
            $eliminar = $sql->execute([$nombre]);
            return $eliminar ? 1 : 0;
            
        } catch (PDOException $e) {
            error_log("Error en EliminarEstado: " . $e->getMessage());
            return 0;
        }
    }

    // ============================================
    // 6. LISTAR TODOS LOS ESTADOS (con nombre del país)
    // ============================================
    public function ListarEstados(){
    include("conexion.php");
    try {
        $sql = $conex->prepare("
            SELECT estado.id, estado.nombre, pais.nombre AS pais, estado.capital 
            FROM estado 
            INNER JOIN pais ON estado.pais_id = pais.id 
            ORDER BY estado.nombre ASC
        ");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en ListarEstados: " . $e->getMessage());
        return [];
    }
}
}
?>