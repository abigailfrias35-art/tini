<?php
/**
 * admin/cambiar_estado_pedido.php
 * Actualización del estado de pedidos mediante Prepared Statements PDO
 * Estudiante: Frias Abigail
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin');

require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = (int)($_POST['pedido_id'] ?? 0);
    $nuevo_estado = trim($_POST['nuevo_estado'] ?? '');

    $estados_validos = ['pendiente', 'pagado', 'enviado', 'cancelado'];

    if ($pedido_id > 0 && in_array($nuevo_estado, $estados_validos, true)) {
        try {
            $stmt = $pdo->prepare("UPDATE pedidos SET estado = :estado, updated_at = NOW() WHERE id = :id");
            $stmt->execute([
                ':estado' => $nuevo_estado,
                ':id'     => $pedido_id
            ]);

            header('Location: index.php?tab=pedidos&status=order_updated');
            exit;
        } catch (\PDOException $e) {
            die("Error al actualizar estado del pedido: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
}

header('Location: index.php?tab=pedidos');
exit;
