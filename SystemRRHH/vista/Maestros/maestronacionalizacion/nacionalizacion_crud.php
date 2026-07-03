<?php
// nacionalizacion_crud.php
// Ubicación: SystemRRHH/vista/Maestros/maestronacionalizacion/nacionalizacion_crud.php

require_once __DIR__ . '/../../../controlador/NacionalizacionController.php';

// Configurar headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obtener parámetros
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Instanciar controlador
$controller = new NacionalizacionController();

try {
    $response = null;
    
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($action === 'list') {
                $response = $controller->listar();
            } elseif ($action === 'get' && $id) {
                $response = $controller->obtener($id);
            } elseif ($action === 'empleados') {
                $response = $controller->getEmpleados();
            } else {
                $response = ['success' => false, 'message' => 'Acción GET no válida'];
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($action === 'create' || $action === '') {
                $response = $controller->crear($data);
            } else {
                $response = ['success' => false, 'message' => 'Acción POST no válida'];
            }
            break;
            
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($action === 'update' || $action === '') {
                $response = $controller->actualizar($data);
            } else {
                $response = ['success' => false, 'message' => 'Acción PUT no válida'];
            }
            break;
            
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($action === 'delete' || $action === '') {
                $id = $data['id'] ?? null;
                $response = $controller->eliminar($id);
            } else {
                $response = ['success' => false, 'message' => 'Acción DELETE no válida'];
            }
            break;
            
        default:
            $response = ['success' => false, 'message' => 'Método no permitido'];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
?>