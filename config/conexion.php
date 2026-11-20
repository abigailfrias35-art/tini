<?php
/**
 * config/conexion.php
 * Conexión segura a la Base de Datos MySQL mediante PDO
 * Proyecto: TINI - Plataforma Web Oficial
 * Materia: Adaptación al ambiente de trabajo & Programación Web
 * Estudiante: Frias Abigail (2026)
 */

require_once __DIR__ . '/env.php';

$host    = DB_HOST;
$db      = DB_NAME;
$user    = DB_USER;
$pass    = DB_PASS;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Obligatorio: usa consultas preparadas nativas en el motor MySQL
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Manejo seguro de excepción sin exponer credenciales sensibles
    error_log("Error de conexión a BD: " . $e->getMessage());
    die("<h3>Error crítico de conexión al servidor de base de datos. Por favor verifique que MySQL esté iniciado en XAMPP/Laragon.</h3>");
}
