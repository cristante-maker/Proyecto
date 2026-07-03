<?php
// modelo/clase_nacionalizacion.php
// CLASE PRINCIPAL - CRUD COMPLETO para Nacionalizaciones

require_once __DIR__ . '/conexion.php';

class Nacionalizacion {
    // ============================================
    // PROPIEDADES
    // ============================================
    private $db;
    private $id;
    private $id_empleado;
    private $pais_origen;
    private $gaceta;
    private $fecha_gaceta;
    private $fecha_nacionalizacion;
    private $cedula;
    private $estado;
    private $observaciones;
    private $empleado_nombre;
    private $table = 'nacionalizaciones';
    
    // ============================================
    // CONSTRUCTOR
    // ============================================
    public function __construct() {
        $this->db = Conexion::getInstance();
    }
    
    // ============================================
    // GETTERS
    // ============================================
    public function getId() { return $this->id; }
    public function getIdEmpleado() { return $this->id_empleado; }
    public function getPaisOrigen() { return $this->pais_origen; }
    public function getGaceta() { return $this->gaceta; }
    public function getFechaGaceta() { return $this->fecha_gaceta; }
    public function getFechaNacionalizacion() { return $this->fecha_nacionalizacion; }
    public function getCedula() { return $this->cedula; }
    public function getEstado() { return $this->estado; }
    public function getObservaciones() { return $this->observaciones; }
    public function getEmpleadoNombre() { return $this->empleado_nombre; }
    
    // ============================================
    // SETTERS
    // ============================================
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdEmpleado($id_empleado) { $this->id_empleado = $id_empleado; return $this; }
    public function setPaisOrigen($pais_origen) { $this->pais_origen = $pais_origen; return $this; }
    public function setGaceta($gaceta) { $this->gaceta = $gaceta; return $this; }
    public function setFechaGaceta($fecha_gaceta) { $this->fecha_gaceta = $fecha_gaceta; return $this; }
    public function setFechaNacionalizacion($fecha_nacionalizacion) { $this->fecha_nacionalizacion = $fecha_nacionalizacion; return $this; }
    public function setCedula($cedula) { $this->cedula = $cedula; return $this; }
    public function setEstado($estado) { $this->estado = $estado; return $this; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; return $this; }
    public function setEmpleadoNombre($nombre) { $this->empleado_nombre = $nombre; return $this; }
    
    // ============================================
    // MÉTODO: OBTENER TODOS (LISTAR)
    // ============================================
    public function getAll() {
        $sql = "SELECT n.*, u.nombre as empleado_nombre 
                FROM {$this->table} n 
                LEFT JOIN usuarios u ON n.id_empleado = u.id 
                ORDER BY n.id DESC";
        
        $result = $this->db->query($sql);
        $nacionalizaciones = [];
        
        while ($row = $result->fetch_assoc()) {
            $nacionalizacion = new self();
            $nacionalizacion->id = $row['id'];
            $nacionalizacion->id_empleado = $row['id_empleado'];
            $nacionalizacion->pais_origen = $row['pais_origen'];
            $nacionalizacion->gaceta = $row['gaceta'];
            $nacionalizacion->fecha_gaceta = $row['fecha_gaceta'];
            $nacionalizacion->fecha_nacionalizacion = $row['fecha_nacionalizacion'];
            $nacionalizacion->cedula = $row['cedula'];
            $nacionalizacion->estado = $row['estado'];
            $nacionalizacion->observaciones = $row['observaciones'];
            $nacionalizacion->empleado_nombre = $row['empleado_nombre'];
            $nacionalizaciones[] = $nacionalizacion;
        }
        
        return $nacionalizaciones;
    }
    
    // ============================================
    // MÉTODO: OBTENER POR ID (CONSULTAR)
    // ============================================
    public function getById($id) {
        $sql = "SELECT n.*, u.nombre as empleado_nombre 
                FROM {$this->table} n 
                LEFT JOIN usuarios u ON n.id_empleado = u.id 
                WHERE n.id = " . intval($id);
        
        $result = $this->db->query($sql);
        
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->id_empleado = $row['id_empleado'];
            $this->pais_origen = $row['pais_origen'];
            $this->gaceta = $row['gaceta'];
            $this->fecha_gaceta = $row['fecha_gaceta'];
            $this->fecha_nacionalizacion = $row['fecha_nacionalizacion'];
            $this->cedula = $row['cedula'];
            $this->estado = $row['estado'];
            $this->observaciones = $row['observaciones'];
            $this->empleado_nombre = $row['empleado_nombre'];
            return $this;
        }
        
        return null;
    }
    
    // ============================================
    // MÉTODO: CREAR (INSERTAR)
    // ============================================
    public function create($data) {
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        $id_empleado = intval($data['empleado']);
        $pais_origen = $this->db->escape($data['pais_origen']);
        $gaceta = $this->db->escape($data['gaceta']);
        $fecha_gaceta = "'" . $this->db->escape($data['fecha_gaceta']) . "'";
        $fecha_nacionalizacion = !empty($data['fecha_nacionalizacion']) ? "'" . $this->db->escape($data['fecha_nacionalizacion']) . "'" : 'NULL';
        $cedula = !empty($data['cedula']) ? "'" . $this->db->escape($data['cedula']) . "'" : 'NULL';
        $estado = $this->db->escape($data['estado'] ?? 'Pendiente');
        $observaciones = !empty($data['observaciones']) ? "'" . $this->db->escape($data['observaciones']) . "'" : 'NULL';
        
        $sql = "INSERT INTO {$this->table} 
                (id_empleado, pais_origen, gaceta, fecha_gaceta, fecha_nacionalizacion, cedula, estado, observaciones) 
                VALUES 
                ($id_empleado, '$pais_origen', '$gaceta', $fecha_gaceta, $fecha_nacionalizacion, $cedula, '$estado', $observaciones)";
        
        if ($this->db->query($sql)) {
            $this->id = $this->db->lastInsertId();
            return ['success' => true, 'message' => '✅ Registro creado exitosamente', 'id' => $this->id];
        }
        
        return ['success' => false, 'message' => 'Error al crear: ' . $this->db->getError()];
    }
    
    // ============================================
    // MÉTODO: ACTUALIZAR (MODIFICAR)
    // ============================================
    public function update($data) {
        if (empty($data['id'])) {
            return ['success' => false, 'message' => 'ID no proporcionado'];
        }
        
        $errors = $this->validate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        $id = intval($data['id']);
        $id_empleado = intval($data['empleado']);
        $pais_origen = $this->db->escape($data['pais_origen']);
        $gaceta = $this->db->escape($data['gaceta']);
        $fecha_gaceta = "'" . $this->db->escape($data['fecha_gaceta']) . "'";
        $fecha_nacionalizacion = !empty($data['fecha_nacionalizacion']) ? "'" . $this->db->escape($data['fecha_nacionalizacion']) . "'" : 'NULL';
        $cedula = !empty($data['cedula']) ? "'" . $this->db->escape($data['cedula']) . "'" : 'NULL';
        $estado = $this->db->escape($data['estado']);
        $observaciones = !empty($data['observaciones']) ? "'" . $this->db->escape($data['observaciones']) . "'" : 'NULL';
        
        $sql = "UPDATE {$this->table} SET 
                id_empleado = $id_empleado,
                pais_origen = '$pais_origen',
                gaceta = '$gaceta',
                fecha_gaceta = $fecha_gaceta,
                fecha_nacionalizacion = $fecha_nacionalizacion,
                cedula = $cedula,
                estado = '$estado',
                observaciones = $observaciones
                WHERE id = $id";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => '✅ Registro actualizado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar: ' . $this->db->getError()];
    }
    
    // ============================================
    // MÉTODO: ELIMINAR
    // ============================================
    public function delete($id) {
        if (empty($id)) {
            return ['success' => false, 'message' => 'ID no proporcionado'];
        }
        
        $sql = "DELETE FROM {$this->table} WHERE id = " . intval($id);
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => '✅ Registro eliminado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al eliminar: ' . $this->db->getError()];
    }
    
    // ============================================
    // MÉTODO: VALIDAR DATOS
    // ============================================
    public function validate($data) {
        $errors = [];
        
        if (empty($data['empleado']) || $data['empleado'] === '') {
            $errors['empleado'] = 'El empleado es requerido';
        }
        
        if (empty($data['pais_origen']) || trim($data['pais_origen']) === '') {
            $errors['pais_origen'] = 'El país de origen es requerido';
        } elseif (strlen($data['pais_origen']) < 2) {
            $errors['pais_origen'] = 'El país debe tener al menos 2 caracteres';
        } elseif (strlen($data['pais_origen']) > 50) {
            $errors['pais_origen'] = 'El país no puede tener más de 50 caracteres';
        }
        
        if (empty($data['gaceta']) || trim($data['gaceta']) === '') {
            $errors['gaceta'] = 'El número de gaceta es requerido';
        } elseif (strlen($data['gaceta']) < 3) {
            $errors['gaceta'] = 'La gaceta debe tener al menos 3 caracteres';
        } elseif (strlen($data['gaceta']) > 20) {
            $errors['gaceta'] = 'La gaceta no puede tener más de 20 caracteres';
        }
        
        if (empty($data['fecha_gaceta']) || $data['fecha_gaceta'] === '') {
            $errors['fecha_gaceta'] = 'La fecha de gaceta es requerida';
        }
        
        if (empty($data['estado']) || $data['estado'] === '') {
            $errors['estado'] = 'El estado es requerido';
        }
        
        if (!empty($data['cedula']) && !preg_match('/^[VEve]?-?\d{7,8}$/', $data['cedula'])) {
            $errors['cedula'] = 'Ingrese una cédula válida (ej: V-12345678)';
        }
        
        if (!empty($data['observaciones']) && strlen($data['observaciones']) > 500) {
            $errors['observaciones'] = 'Las observaciones no pueden tener más de 500 caracteres';
        }
        
        return $errors;
    }
    
    // ============================================
    // MÉTODO: CONVERTIR A ARRAY
    // ============================================
    public function toArray() {
        return [
            'id' => $this->id,
            'id_empleado' => $this->id_empleado,
            'pais_origen' => $this->pais_origen,
            'gaceta' => $this->gaceta,
            'fecha_gaceta' => $this->fecha_gaceta,
            'fecha_nacionalizacion' => $this->fecha_nacionalizacion,
            'cedula' => $this->cedula,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'empleado_nombre' => $this->empleado_nombre
        ];
    }
    
    // ============================================
    // MÉTODO: OBTENER EMPLEADOS PARA SELECT
    // ============================================
    public function getEmpleadosForSelect() {
        $sql = "SELECT id, nombre FROM usuarios ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        
        $options = [];
        while ($row = $result->fetch_assoc()) {
            $options[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre']
            ];
        }
        
        return $options;
    }
}
?>