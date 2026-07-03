<?php
// modelo/clase_dominio_idioma.php
// CLASE PRINCIPAL - CRUD COMPLETO para Dominio de Idioma

require_once __DIR__ . '/conexion.php';

class DominioIdioma {
    // ============================================
    // PROPIEDADES
    // ============================================
    private $db;
    private $id;
    private $id_empleado;
    private $id_idioma;
    private $nivel;
    private $observaciones;
    private $empleado_nombre;
    private $idioma_nombre;
    private $table = 'dominio_idioma';
    
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
    public function getIdIdioma() { return $this->id_idioma; }
    public function getNivel() { return $this->nivel; }
    public function getObservaciones() { return $this->observaciones; }
    public function getEmpleadoNombre() { return $this->empleado_nombre; }
    public function getIdiomaNombre() { return $this->idioma_nombre; }
    
    // ============================================
    // SETTERS
    // ============================================
    public function setId($id) { $this->id = $id; return $this; }
    public function setIdEmpleado($id_empleado) { $this->id_empleado = $id_empleado; return $this; }
    public function setIdIdioma($id_idioma) { $this->id_idioma = $id_idioma; return $this; }
    public function setNivel($nivel) { $this->nivel = $nivel; return $this; }
    public function setObservaciones($observaciones) { $this->observaciones = $observaciones; return $this; }
    public function setEmpleadoNombre($nombre) { $this->empleado_nombre = $nombre; return $this; }
    public function setIdiomaNombre($nombre) { $this->idioma_nombre = $nombre; return $this; }
    
    // ============================================
    // MÉTODO: OBTENER TODOS (LISTAR)
    // ============================================
    public function getAll() {
        $sql = "SELECT d.*, 
                       u.nombre as empleado_nombre, 
                       i.nombre as idioma_nombre 
                FROM {$this->table} d 
                LEFT JOIN usuarios u ON d.id_empleado = u.id 
                LEFT JOIN idiomas i ON d.id_idioma = i.id 
                ORDER BY d.id DESC";
        
        $result = $this->db->query($sql);
        $dominios = [];
        
        while ($row = $result->fetch_assoc()) {
            $dominio = new self();
            $dominio->id = $row['id'];
            $dominio->id_empleado = $row['id_empleado'];
            $dominio->id_idioma = $row['id_idioma'];
            $dominio->nivel = $row['nivel'];
            $dominio->observaciones = $row['observaciones'];
            $dominio->empleado_nombre = $row['empleado_nombre'];
            $dominio->idioma_nombre = $row['idioma_nombre'];
            $dominios[] = $dominio;
        }
        
        return $dominios;
    }
    
    // ============================================
    // MÉTODO: OBTENER POR ID (CONSULTAR)
    // ============================================
    public function getById($id) {
        $sql = "SELECT d.*, 
                       u.nombre as empleado_nombre, 
                       i.nombre as idioma_nombre 
                FROM {$this->table} d 
                LEFT JOIN usuarios u ON d.id_empleado = u.id 
                LEFT JOIN idiomas i ON d.id_idioma = i.id 
                WHERE d.id = " . intval($id);
        
        $result = $this->db->query($sql);
        
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->id_empleado = $row['id_empleado'];
            $this->id_idioma = $row['id_idioma'];
            $this->nivel = $row['nivel'];
            $this->observaciones = $row['observaciones'];
            $this->empleado_nombre = $row['empleado_nombre'];
            $this->idioma_nombre = $row['idioma_nombre'];
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
        $id_idioma = intval($data['idioma']);
        $nivel = $this->db->escape($data['nivel']);
        $observaciones = !empty($data['observaciones']) ? "'" . $this->db->escape($data['observaciones']) . "'" : 'NULL';
        
        $sql = "INSERT INTO {$this->table} (id_empleado, id_idioma, nivel, observaciones) 
                VALUES ($id_empleado, $id_idioma, '$nivel', $observaciones)";
        
        if ($this->db->query($sql)) {
            $this->id = $this->db->lastInsertId();
            return ['success' => true, 'message' => '✅ Dominio de idioma creado exitosamente', 'id' => $this->id];
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
        $id_idioma = intval($data['idioma']);
        $nivel = $this->db->escape($data['nivel']);
        $observaciones = !empty($data['observaciones']) ? "'" . $this->db->escape($data['observaciones']) . "'" : 'NULL';
        
        $sql = "UPDATE {$this->table} SET 
                id_empleado = $id_empleado,
                id_idioma = $id_idioma,
                nivel = '$nivel',
                observaciones = $observaciones
                WHERE id = $id";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => '✅ Dominio de idioma actualizado exitosamente'];
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
            return ['success' => true, 'message' => '✅ Dominio de idioma eliminado exitosamente'];
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
        
        if (empty($data['idioma']) || $data['idioma'] === '') {
            $errors['idioma'] = 'El idioma es requerido';
        }
        
        if (empty($data['nivel']) || $data['nivel'] === '') {
            $errors['nivel'] = 'El nivel es requerido';
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
            'id_idioma' => $this->id_idioma,
            'nivel' => $this->nivel,
            'observaciones' => $this->observaciones,
            'empleado_nombre' => $this->empleado_nombre,
            'idioma_nombre' => $this->idioma_nombre
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
    
    // ============================================
    // MÉTODO: OBTENER IDIOMAS PARA SELECT
    // ============================================
    public function getIdiomasForSelect() {
        $sql = "SELECT id, nombre FROM idiomas ORDER BY nombre ASC";
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