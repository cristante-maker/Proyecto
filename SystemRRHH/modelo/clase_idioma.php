<?php
// modelo/clase_idioma.php
// CLASE PRINCIPAL - CRUD COMPLETO para Idiomas

require_once __DIR__ . '/conexion.php';

class Idioma {
    // ============================================
    // PROPIEDADES
    // ============================================
    private $db;
    private $id;
    private $nombre;
    private $codigo_iso;
    private $table = 'idiomas';
    
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
    public function getNombre() { return $this->nombre; }
    public function getCodigoIso() { return $this->codigo_iso; }
    
    // ============================================
    // SETTERS
    // ============================================
    public function setId($id) { $this->id = $id; return $this; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function setCodigoIso($codigo_iso) { $this->codigo_iso = $codigo_iso; return $this; }
    
    // ============================================
    // MÉTODO: OBTENER TODOS (LISTAR)
    // ============================================
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $result = $this->db->query($sql);
        $idiomas = [];
        
        while ($row = $result->fetch_assoc()) {
            $idioma = new self();
            $idioma->id = $row['id'];
            $idioma->nombre = $row['nombre'];
            $idioma->codigo_iso = $row['codigo_iso'];
            $idiomas[] = $idioma;
        }
        
        return $idiomas;
    }
    
    // ============================================
    // MÉTODO: OBTENER POR ID (CONSULTAR)
    // ============================================
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = " . intval($id);
        $result = $this->db->query($sql);
        
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->codigo_iso = $row['codigo_iso'];
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
        
        $nombre = $this->db->escape($data['nombre']);
        $codigo_iso = $this->db->escape(strtoupper($data['codigo_iso']));
        
        $sql = "INSERT INTO {$this->table} (nombre, codigo_iso) VALUES ('$nombre', '$codigo_iso')";
        
        if ($this->db->query($sql)) {
            $this->id = $this->db->lastInsertId();
            return ['success' => true, 'message' => '✅ Idioma creado exitosamente', 'id' => $this->id];
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
        $nombre = $this->db->escape($data['nombre']);
        $codigo_iso = $this->db->escape(strtoupper($data['codigo_iso']));
        
        $sql = "UPDATE {$this->table} SET nombre = '$nombre', codigo_iso = '$codigo_iso' WHERE id = $id";
        
        if ($this->db->query($sql)) {
            return ['success' => true, 'message' => '✅ Idioma actualizado exitosamente'];
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
            return ['success' => true, 'message' => '✅ Idioma eliminado exitosamente'];
        }
        
        return ['success' => false, 'message' => 'Error al eliminar: ' . $this->db->getError()];
    }
    
    // ============================================
    // MÉTODO: VALIDAR DATOS
    // ============================================
    public function validate($data) {
        $errors = [];
        
        if (empty($data['nombre']) || trim($data['nombre']) === '') {
            $errors['nombre'] = 'El nombre del idioma es requerido';
        } elseif (strlen($data['nombre']) < 2) {
            $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        } elseif (strlen($data['nombre']) > 50) {
            $errors['nombre'] = 'El nombre no puede tener más de 50 caracteres';
        }
        
        if (empty($data['codigo_iso']) || trim($data['codigo_iso']) === '') {
            $errors['codigo_iso'] = 'El código ISO es requerido';
        } elseif (!preg_match('/^[A-Za-z]{2}$/', $data['codigo_iso'])) {
            $errors['codigo_iso'] = 'El código ISO debe tener exactamente 2 letras';
        }
        
        return $errors;
    }
    
    // ============================================
    // MÉTODO: CONVERTIR A ARRAY
    // ============================================
    public function toArray() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo_iso' => $this->codigo_iso
        ];
    }
}
?>