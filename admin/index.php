<?php
/**
 * admin/index.php
 * Panel de Administración CRUD de Merchandising y Productos de TINI
 * Estudiante: Frias Abigail
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin');

require_once __DIR__ . '/../config/conexion.php';

// Obtener listado de productos con Consulta Preparada Segura
$productos = [];
$error_db = null;

try {
    $stmt = $pdo->query("
        SELECT p.id, p.titulo, p.descripcion, p.precio, p.stock, p.imagen, c.nombre AS categoria 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.activo = 1 
        ORDER BY p.id DESC
    ");
    $productos = $stmt->fetchAll();
} catch (\PDOException $e) {
    $error_db = "No se pudo consultar la base de datos (verifique si ejecutó schema.sql): " . $e->getMessage();
}

$titulo_pagina = "Panel de Administración - CRUD TINI";
$pagina_activa = "admin";
require_once __DIR__ . '/../includes/header.php';
?>

<main style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--accent-pink, #ff69b4); padding-bottom: 15px; margin-bottom: 25px;">
        <div>
            <h1 style="margin: 0; color: #fff; font-size: 24px;">Administración de Merchandising & Contenido</h1>
            <p style="margin: 5px 0 0 0; color: #cbd5e1; font-size: 13.5px;">
                Bienvenida/o, <strong><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Administrador', ENT_QUOTES, 'UTF-8'); ?></strong> 
                (Rol: <span style="color:#00ff88; font-weight:bold;"><?= htmlspecialchars($_SESSION['rol'] ?? 'admin', ENT_QUOTES, 'UTF-8'); ?></span>)
            </p>
        </div>
        <div>
            <a href="crear.php" class="btn-ticket" style="background: #10b981; text-decoration: none; display: inline-block; padding: 10px 16px; font-size: 13px;">+ Nuevo Producto</a>
            <a href="../logout.php" class="btn-ticket" style="background: #ef4444; text-decoration: none; display: inline-block; padding: 10px 16px; font-size: 13px; margin-left: 8px;">Cerrar Sesión</a>
        </div>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'created'): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #a7f3d0;">
                ✓ Producto agregado exitosamente al catálogo oficial de TINI.
            </div>
        <?php elseif ($_GET['status'] === 'updated'): ?>
            <div style="background: rgba(59, 130, 246, 0.2); border-left: 4px solid #3b82f6; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #bfdbfe;">
                ✓ Los datos del producto fueron actualizados correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #fecaca;">
                ✓ El producto fue eliminado del sistema.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($error_db): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #fecaca; font-size: 13px;">
            <?= htmlspecialchars($error_db, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <div style="background: rgba(255,255,255,0.05); border-radius: 10px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
            <thead>
                <tr style="background: rgba(0,0,0,0.4); border-bottom: 1px solid rgba(255,255,255,0.15); color: #c084fc;">
                    <th style="padding: 12px 15px;">ID</th>
                    <th style="padding: 12px 15px;">Imagen</th>
                    <th style="padding: 12px 15px;">Título del Artículo</th>
                    <th style="padding: 12px 15px;">Categoría</th>
                    <th style="padding: 12px 15px;">Precio</th>
                    <th style="padding: 12px 15px;">Stock</th>
                    <th style="padding: 12px 15px; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="7" style="padding: 25px; text-align: center; color: #aaa;">
                            No hay productos registrados en el sistema. Presione "+ Nuevo Producto" para cargar el primero.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 12px 15px; font-weight: bold; color: #ff69b4;"><?= $p['id']; ?></td>
                            <td style="padding: 12px 15px;">
                                <img src="../<?= htmlspecialchars($p['imagen'] ?? 'merch.jpg', ENT_QUOTES, 'UTF-8'); ?>" alt="Img" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;">
                            </td>
                            <td style="padding: 12px 15px; font-weight: 600;">
                                <?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                                <div style="font-size: 11.5px; color: #94a3b8; font-weight: normal;"><?= htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 60, '...'), ENT_QUOTES, 'UTF-8'); ?></div>
                            </td>
                            <td style="padding: 12px 15px; color: #38bdf8;">
                                <?= htmlspecialchars($p['categoria'] ?? 'General', ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="padding: 12px 15px; color: #4ade80; font-weight: bold;">
                                $<?= number_format($p['precio'], 2, ',', '.'); ?>
                            </td>
                            <td style="padding: 12px 15px;">
                                <span style="background: rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 10px; font-size: 12px;">
                                    <?= (int)$p['stock']; ?> un.
                                </span>
                            </td>
                            <td style="padding: 12px 15px; text-align: center; white-space: nowrap;">
                                <a href="editar.php?id=<?= $p['id']; ?>" style="background: #eab308; color: #000; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; margin-right: 5px;">Editar</a>
                                <a href="eliminar.php?id=<?= $p['id']; ?>" onclick="return confirm('¿Confirmas que deseas eliminar <?= htmlspecialchars(addslashes($p['titulo']), ENT_QUOTES, 'UTF-8'); ?>?');" style="background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold;">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
