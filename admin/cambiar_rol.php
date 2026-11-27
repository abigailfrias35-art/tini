<?php
/**
 * admin/cambiar_rol.php
 * Cambio seguro de rol de usuario mediante Prepared Statements PDO
 * Estudiante: Frias Abigail
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin');

require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int)($_POST['usuario_id'] ?? 0);
    $nuevo_rol_id = (int)($_POST['nuevo_rol_id'] ?? 2);

    // Evitar que el administrador actual se quite a sí mismo los privilegios
    if ($usuario_id === (int)$_SESSION['usuario_id']) {
        header('Location: index.php?tab=usuarios&error=self_demote');
        exit;
    }

    if ($usuario_id > 0 && ($nuevo_rol_id === 1 || $nuevo_rol_id === 2)) {
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET rol_id = :rol_id, updated_at = NOW() WHERE id = :id");
            $stmt->execute([
                ':rol_id' => $nuevo_rol_id,
                ':id'     => $usuario_id
            ]);

            header('Location: index.php?tab=usuarios&status=role_updated');
            exit;
        } catch (\PDOException $e) {
            die("Error al actualizar rol del usuario: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
}

header('Location: index.php?tab=usuarios');
exit;
