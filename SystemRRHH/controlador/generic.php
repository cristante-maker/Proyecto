<?php
// ============================================
// API GENERICA PARA MAESTROS
// ============================================
// Uso: api/generic.php?table=empleado&id=1
// ============================================

require_once '../config/database.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$table = isset($_GET['table']) ? $_GET['table'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if (!$table) {
    jsonResponse(['error' => 'Tabla requerida'], 400);
}

// Tablas permitidas (seguridad)
$allowedTables = [
    'pais', 'estado', 'ciudad', 'tipo_institucion', 'institucion',
    'idioma', 'tipo_permiso', 'documento', 'empleado'
];

if (!in_array($table, $allowedTables)) {
    jsonResponse(['error' => 'Tabla no permitida'], 403);
}

try {
    $pdo = getConnection();

    // Obtener nombres de columnas de la tabla
    $stmt = $pdo->query("DESCRIBE $table");
    $columns = $stmt->fetchAll();
    $columnNames = array_map(function($col) {
        return $col['Field'];
    }, $columns);
    
    // Excluir columnas automáticas
    $excludeColumns = ['id', 'created_at', 'updated_at'];
    $insertColumns = array_filter($columnNames, function($col) use ($excludeColumns) {
        return !in_array($col, $excludeColumns);
    });

    switch ($method) {
        // ============================================
        // GET - Obtener registros
        // ============================================
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
                $stmt->execute([$id]);
                $result = $stmt->fetch();
                jsonResponse($result ?: ['error' => 'Registro no encontrado'], $result ? 200 : 404);
            } else {
                $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC");
                jsonResponse($stmt->fetchAll());
            }
            break;

        // ============================================
        // POST - Crear registro
        // ============================================
        case 'POST':
            $fields = [];
            $placeholders = [];
            $values = [];
            
            foreach ($insertColumns as $col) {
                if (isset($input[$col])) {
                    $fields[] = $col;
                    $placeholders[] = '?';
                    $values[] = sanitize($input[$col]);
                }
            }
            
            if (empty($fields)) {
                jsonResponse(['error' => 'No hay datos para insertar'], 400);
            }
            
            $sql = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            
            jsonResponse(['message' => 'Registro creado', 'id' => $pdo->lastInsertId()], 201);
            break;

        // ============================================
        // PUT - Actualizar registro
        // ============================================
        case 'PUT':
            if (!$id) {
                jsonResponse(['error' => 'ID requerido'], 400);
            }
            
            $sets = [];
            $values = [];
            
            foreach ($insertColumns as $col) {
                if (isset($input[$col])) {
                    $sets[] = "$col = ?";
                    $values[] = sanitize($input[$col]);
                }
            }
            
            if (empty($sets)) {
                jsonResponse(['error' => 'No hay datos para actualizar'], 400);
            }
            
            $values[] = $id;
            $sql = "UPDATE $table SET " . implode(', ', $sets) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            
            jsonResponse(['message' => 'Registro actualizado']);
            break;

        // ============================================
        // DELETE - Eliminar registro
        // ============================================
        case 'DELETE':
            if (!$id) {
                jsonResponse(['error' => 'ID requerido'], 400);
            }
            
            $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
            $stmt->execute([$id]);
            
            jsonResponse(['message' => 'Registro eliminado']);
            break;

        default:
            jsonResponse(['error' => 'Método no permitido'], 405);
    }

} catch (PDOException $e) {
    jsonResponse(['error' => 'Error en la base de datos: ' . $e->getMessage()], 500);
}