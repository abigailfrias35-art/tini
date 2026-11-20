<?php
/**
 * config/env.php
 * Carga de variables de entorno y configuración centralizada
 * Materia: Adaptación al ambiente de trabajo
 * Estudiante: Frias Abigail
 */

// Carga básica del archivo .env si existe
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            // Quitar comillas si las hay
            $value = trim($value, "\"'");
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Constantes globales de la aplicación
if (!defined('APP_NAME')) {
    define('APP_NAME', $_ENV['APP_NAME'] ?? 'TINI - Sitio Oficial & Fan Club');
}
if (!defined('APP_EMAIL')) {
    define('APP_EMAIL', $_ENV['APP_EMAIL'] ?? 'abigailfrias35@gmail.com');
}
if (!defined('APP_ENV')) {
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'local');
}
if (!defined('APP_URL')) {
    define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/tini');
}

// Configuración de Base de Datos MySQL
if (!defined('DB_HOST')) {
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'tini_db');
}
if (!defined('DB_USER')) {
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', $_ENV['DB_PASS'] ?? '');
}
