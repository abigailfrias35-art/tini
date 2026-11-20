<?php
/**
 * admin/eliminar.php
 * Eliminación segura de registros con Prepared Statements PDO
 * Estudiante: Frias Abigail
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin');

require_once __DIR__ . '/../config/conexion.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        // Consulta Preparada contra SQL Injection
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header('Location: index.php?status=deleted');
        exit;
    } catch (\PDOException $e) {
        die("Error al eliminar el producto: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
    }
} else {
    header('Location: index.php');
    exit;
}
