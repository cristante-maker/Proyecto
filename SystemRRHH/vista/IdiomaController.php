<?php
// controlador/IdiomaController.php
// CONTROLADOR - Maneja las peticiones CRUD

require_once __DIR__ . '/../modelo/clase_idioma.php';

class IdiomaController {
    private $model;
    
    public function __construct() {
        $this->model = new Idioma();
    }
    
    // ============================================
    // LISTAR TODOS
    // ============================================
    public function listar() {
        try {
            $idiomas = $this->model->getAll();
            
            $data = [];
            foreach ($idiomas as $idioma) {
                $data[] = $idioma->toArray();
            }
            
            return $this->response(true, 'Datos cargados correctamente', $data);
        } catch (Exception $e) {
            return $this->response(false, 'Error al listar: ' . $e->getMessage());
        }
    }
    
    // ============================================
    // OBTENER POR ID (CONSULTAR)
    // ============================================
    public function obtener($id) {
        try {
            $idioma = $this->model->getById($id);
            
            if ($idioma) {
                return $this->response(true, 'Registro encontrado', $idioma->toArray());
            }
            
            return $this->response(false, 'Registro no encontrado');
        } catch (Exception $e) {
            return $this->response(false, 'Error al obtener: ' . $e->getMessage());
        }
    }
    
    // ============================================
    // CREAR (INSERTAR)
    // ============================================
    public function crear($data) {
        try {
            $result = $this->model->create($data);
            
            if ($result['success']) {
                $idioma = $this->model->getById($result['id']);
                return $this->response(true, $result['message'], $idioma ? $idioma->toArray() : null);
            }
            
            return $this->response(false, $result['message'] ?? 'Error al crear', null, $result['errors'] ?? null);
        } catch (Exception $e) {
            return $this->response(false, 'Error al crear: ' . $e->getMessage());
        }
    }
    
    // ============================================
    // ACTUALIZAR (MODIFICAR)
    // ============================================
    public function actualizar($data) {
        try {
            $result = $this->model->update($data);
            
            if ($result['success']) {
                $idioma = $this->model->getById($data['id']);
                return $this->response(true, $result['message'], $idioma ? $idioma->toArray() : null);
            }
            
            return $this->response(false, $result['message'] ?? 'Error al actualizar', null, $result['errors'] ?? null);
        } catch (Exception $e) {
            return $this->response(false, 'Error al actualizar: ' . $e->getMessage());
        }
    }
    
    // ============================================
    // ELIMINAR
    // ============================================
    public function eliminar($id) {
        try {
            $result = $this->model->delete($id);
            
            if ($result['success']) {
                return $this->response(true, $result['message']);
            }
            
            return $this->response(false, $result['message'] ?? 'Error al eliminar');
        } catch (Exception $e) {
            return $this->response(false, 'Error al eliminar: ' . $e->getMessage());
        }
    }
    
    // ============================================
    // RESPONDER EN JSON
    // ============================================
    private function response($success, $message, $data = null, $errors = null) {
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        return $response;
    }
}
?>