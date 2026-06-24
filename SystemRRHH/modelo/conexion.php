<?php
// modelo/conexion.php

try {
    $servidor = "localhost";
    $namebd = "databas";        // ← CAMBIADO a "databas" (según el error)
    $usuario = "root";          // ← Usuario XAMPP
    $clave = "";                // ← Contraseña vacía
    
    $dsn = "mysql:host=" . $servidor . ";dbname=" . $namebd . ";charset=utf8mb4";
    $conex = new PDO($dsn, $usuario, $clave);
    $conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conex->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    print "❌ ¡Error de Conexión!: " . $e->getMessage() . "<br/>";
    die();
}
?>