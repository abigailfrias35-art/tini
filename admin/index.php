<?php
/**
 * admin/index.php
 * Panel de Administración Integral con Acceso Total al Sitio y Gestión Centralizada
 * Estudiante: Frias Abigail
 * Materia: Adaptación al ambiente de trabajo & Programación Web
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin'); // Control estricto de acceso exclusivo para rol 'admin'

require_once __DIR__ . '/../config/conexion.php';

// Variables de estado
$tab_activa = $_GET['tab'] ?? 'productos';
$error_db = null;

// Métricas y datos
$total_productos = 0;
$total_usuarios  = 0;
$total_pedidos   = 0;
$total_ventas    = 0.00;

$productos = [];
$pedidos   = [];
$usuarios  = [];

try {
    // 1. Consultar métricas globales
    $total_productos = (int)$pdo->query("SELECT COUNT(*) FROM productos WHERE activo = 1")->fetchColumn();
    $total_usuarios  = (int)$pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $total_pedidos   = (int)$pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
    $total_ventas    = (float)$pdo->query("SELECT COALESCE(SUM(total), 0) FROM pedidos WHERE estado = 'pagado'")->fetchColumn();

    // 2. Consultar catálogo de productos (CRUD)
    $stmt_prod = $pdo->query("
        SELECT p.id, p.titulo, p.descripcion, p.precio, p.stock, p.imagen, c.nombre AS categoria 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.activo = 1 
        ORDER BY p.id DESC
    ");
    $productos = $stmt_prod->fetchAll();

    // 3. Consultar pedidos con datos de usuario
    $stmt_ped = $pdo->query("
        SELECT p.id, p.total, p.estado, p.created_at, u.nombre AS cliente, u.email 
        FROM pedidos p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.id DESC
    ");
    $pedidos = $stmt_ped->fetchAll();

    // 4. Consultar listado de usuarios y sus roles
    $stmt_usr = $pdo->query("
        SELECT u.id, u.nombre, u.email, u.created_at, u.rol_id, r.nombre AS rol_nombre 
        FROM usuarios u 
        INNER JOIN roles r ON u.rol_id = r.id 
        ORDER BY u.id ASC
    ");
    $usuarios = $stmt_usr->fetchAll();

} catch (\PDOException $e) {
    $error_db = "Error al conectar con la base de datos MySQL: " . $e->getMessage();
}

$titulo_pagina = "Panel de Administración Total - TINI";
$pagina_activa = "admin";
require_once __DIR__ . '/../includes/header.php';
?>

<main style="max-width: 1200px; margin: 30px auto; padding: 0 20px;">

    <!-- BARRA SUPERIOR DE ACCESO RÁPIDO A TODA LA PÁGINA WEB -->
    <div style="background: rgba(0,0,0,0.4); border-radius: 12px; padding: 15px 20px; border: 1px solid rgba(224, 36, 179, 0.3); margin-bottom: 25px;">
        <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; color: #f472b6; font-weight: bold; margin-bottom: 8px;">
            🌐 Navegación Rápida a Toda la Página (Acceso Total de Administrador)
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <a href="../index.php#inicio" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">🏠 Inicio Web</a>
            <a href="../index.php#bio" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">📖 Biografía</a>
            <a href="../index.php#musica" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">🎵 Discografía</a>
            <a href="../index.php#tour" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">🎫 Tour 2026</a>
            <a href="../index.php#tienda" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">🛍️ Tienda Oficial</a>
            <a href="../panel_usuario.php" target="_blank" style="background: rgba(192, 132, 252, 0.2); color: #d8b4fe; border: 1px solid #c084fc; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px; font-weight: bold;">💜 Ver Panel Fan Club</a>
            <a href="../contacto.php" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">📝 Búsqueda & Contacto</a>
            <a href="../clase5/index.php" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">📋 Módulo Clase 5</a>
            <a href="../proyecto.php" target="_blank" style="background: rgba(255,255,255,0.08); color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12.5px;">📄 Ficha Técnica</a>
        </div>
    </div>

    <!-- ENCABEZADO PRINCIPAL DEL PANEL -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid var(--accent-pink, #ff69b4); padding-bottom: 15px; margin-bottom: 25px;">
        <div>
            <h1 style="margin: 0; color: #fff; font-size: 26px;">
                ⭐ Panel de Control del Administrador
            </h1>
            <p style="margin: 5px 0 0 0; color: #cbd5e1; font-size: 14px;">
                Sesión iniciada como: <strong><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Administrador', ENT_QUOTES, 'UTF-8'); ?></strong> 
                (Rol: <span style="color:#00ff88; font-weight:bold;">👑 Administrador General</span>)
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="crear.php" class="btn-ticket" style="background: #10b981; text-decoration: none; font-size: 13px; padding: 10px 16px;">+ Nuevo Producto</a>
            <a href="../logout.php" class="btn-ticket" style="background: #ef4444; text-decoration: none; font-size: 13px; padding: 10px 16px;">Cerrar Sesión</a>
        </div>
    </div>

    <!-- NOTIFICACIONES Y ALERTAS -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'created'): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #a7f3d0; font-size: 13.5px;">
                ✓ Producto agregado exitosamente al catálogo oficial de TINI.
            </div>
        <?php elseif ($_GET['status'] === 'updated'): ?>
            <div style="background: rgba(59, 130, 246, 0.2); border-left: 4px solid #3b82f6; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #bfdbfe; font-size: 13.5px;">
                ✓ Datos del producto actualizados correctamente en MySQL.
            </div>
        <?php elseif ($_GET['status'] === 'deleted'): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fecaca; font-size: 13.5px;">
                ✓ Producto eliminado del sistema con éxito.
            </div>
        <?php elseif ($_GET['status'] === 'order_updated'): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #a7f3d0; font-size: 13.5px;">
                ✓ Estado del pedido actualizado correctamente.
            </div>
        <?php elseif ($_GET['status'] === 'role_updated'): ?>
            <div style="background: rgba(168, 85, 247, 0.2); border-left: 4px solid #a855f7; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #e9d5ff; font-size: 13.5px;">
                ✓ Rol de usuario actualizado con éxito.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'self_demote'): ?>
        <div style="background: rgba(245, 158, 11, 0.2); border-left: 4px solid #f59e0b; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fde68a; font-size: 13.5px;">
            ⚠️ Por seguridad, no puedes quitarte a ti misma el rol de Administrador.
        </div>
    <?php endif; ?>

    <?php if ($error_db): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; color: #fecaca; font-size: 13px;">
            <?= htmlspecialchars($error_db, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- TARJETAS DE MÉTRICAS GLOBALES (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 36px; background: rgba(224, 36, 179, 0.2); padding: 12px; border-radius: 10px;">🛍️</div>
            <div>
                <div style="font-size: 12px; color: #cbd5e1; text-transform: uppercase;">Catálogo Activo</div>
                <div style="font-size: 26px; font-weight: bold; color: #fff;"><?= $total_productos; ?> productos</div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 36px; background: rgba(59, 130, 246, 0.2); padding: 12px; border-radius: 10px;">👥</div>
            <div>
                <div style="font-size: 12px; color: #cbd5e1; text-transform: uppercase;">Usuarios Registrados</div>
                <div style="font-size: 26px; font-weight: bold; color: #fff;"><?= $total_usuarios; ?> cuentas</div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 36px; background: rgba(234, 179, 8, 0.2); padding: 12px; border-radius: 10px;">📦</div>
            <div>
                <div style="font-size: 12px; color: #cbd5e1; text-transform: uppercase;">Pedidos Totales</div>
                <div style="font-size: 26px; font-weight: bold; color: #fff;"><?= $total_pedidos; ?> órdenes</div>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 36px; background: rgba(16, 185, 129, 0.2); padding: 12px; border-radius: 10px;">💵</div>
            <div>
                <div style="font-size: 12px; color: #cbd5e1; text-transform: uppercase;">Ventas Pagadas</div>
                <div style="font-size: 24px; font-weight: bold; color: #4ade80;">$<?= number_format($total_ventas, 2, ',', '.'); ?></div>
            </div>
        </div>

    </div>

    <!-- PESTAÑAS DE NAVEGACIÓN DE MÓDULOS -->
    <div style="display: flex; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.15); margin-bottom: 25px;">
        <a href="index.php?tab=productos" style="padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none; font-size: 14px; font-weight: bold; <?= ($tab_activa === 'productos') ? 'background: rgba(255,255,255,0.1); color: var(--accent-pink, #ff69b4); border-bottom: 2px solid var(--accent-pink, #ff69b4);' : 'color: #aaa;'; ?>">
            🛍️ Catálogo de Productos (CRUD)
        </a>
        <a href="index.php?tab=pedidos" style="padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none; font-size: 14px; font-weight: bold; <?= ($tab_activa === 'pedidos') ? 'background: rgba(255,255,255,0.1); color: var(--accent-pink, #ff69b4); border-bottom: 2px solid var(--accent-pink, #ff69b4);' : 'color: #aaa;'; ?>">
            📦 Gestión de Pedidos (<?= count($pedidos); ?>)
        </a>
        <a href="index.php?tab=usuarios" style="padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none; font-size: 14px; font-weight: bold; <?= ($tab_activa === 'usuarios') ? 'background: rgba(255,255,255,0.1); color: var(--accent-pink, #ff69b4); border-bottom: 2px solid var(--accent-pink, #ff69b4);' : 'color: #aaa;'; ?>">
            👥 Gestión de Usuarios & Roles (<?= count($usuarios); ?>)
        </a>
    </div>

    <!-- MÓDULO 1: CRUD PRODUCTOS -->
    <?php if ($tab_activa === 'productos'): ?>
        <div style="background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.4); border-bottom: 1px solid rgba(255,255,255,0.15); color: #c084fc;">
                        <th style="padding: 14px 15px;">ID</th>
                        <th style="padding: 14px 15px;">Foto</th>
                        <th style="padding: 14px 15px;">Título del Artículo</th>
                        <th style="padding: 14px 15px;">Categoría</th>
                        <th style="padding: 14px 15px;">Precio</th>
                        <th style="padding: 14px 15px;">Stock</th>
                        <th style="padding: 14px 15px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #aaa;">
                                No hay productos cargados. Presione "+ Nuevo Producto" para crear uno.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px 15px; font-weight: bold; color: var(--accent-pink, #ff69b4);">#<?= $p['id']; ?></td>
                                <td style="padding: 12px 15px;">
                                    <img src="../<?= htmlspecialchars($p['imagen'] ?? 'merch.jpg', ENT_QUOTES, 'UTF-8'); ?>" alt="Img" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                </td>
                                <td style="padding: 12px 15px; font-weight: 600;">
                                    <?= htmlspecialchars($p['titulo'], ENT_QUOTES, 'UTF-8'); ?>
                                    <div style="font-size: 11.5px; color: #94a3b8; font-weight: normal; margin-top: 2px;">
                                        <?= htmlspecialchars(mb_strimwidth($p['descripcion'], 0, 65, '...'), ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; color: #38bdf8;">
                                    <?= htmlspecialchars($p['categoria'] ?? 'General', ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 12px 15px; color: #4ade80; font-weight: bold;">
                                    $<?= number_format($p['precio'], 2, ',', '.'); ?>
                                </td>
                                <td style="padding: 12px 15px;">
                                    <span style="background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 12px; font-size: 12px;">
                                        <?= (int)$p['stock']; ?> un.
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; text-align: center; white-space: nowrap;">
                                    <a href="editar.php?id=<?= $p['id']; ?>" style="background: #eab308; color: #000; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; margin-right: 5px;">Editar</a>
                                    <a href="eliminar.php?id=<?= $p['id']; ?>" onclick="return confirm('¿Confirmas eliminar <?= htmlspecialchars(addslashes($p['titulo']), ENT_QUOTES, 'UTF-8'); ?>?');" style="background: #ef4444; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold;">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- MÓDULO 2: GESTIÓN DE PEDIDOS -->
    <?php if ($tab_activa === 'pedidos'): ?>
        <div style="background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.4); border-bottom: 1px solid rgba(255,255,255,0.15); color: #c084fc;">
                        <th style="padding: 14px 15px;">N° Pedido</th>
                        <th style="padding: 14px 15px;">Cliente</th>
                        <th style="padding: 14px 15px;">Email</th>
                        <th style="padding: 14px 15px;">Total</th>
                        <th style="padding: 14px 15px;">Fecha</th>
                        <th style="padding: 14px 15px;">Estado Actual</th>
                        <th style="padding: 14px 15px; text-align: center;">Cambiar Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #aaa;">
                                No hay pedidos registrados aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $ped): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px 15px; font-weight: bold; color: var(--accent-pink, #ff69b4);">
                                    #PED-<?= str_pad((string)$ped['id'], 4, '0', STR_PAD_LEFT); ?>
                                </td>
                                <td style="padding: 12px 15px; font-weight: 600;">
                                    <?= htmlspecialchars($ped['cliente'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 12px 15px; color: #94a3b8;">
                                    <?= htmlspecialchars($ped['email'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td style="padding: 12px 15px; color: #4ade80; font-weight: bold;">
                                    $<?= number_format($ped['total'], 2, ',', '.'); ?>
                                </td>
                                <td style="padding: 12px 15px; font-size: 12.5px; color: #cbd5e1;">
                                    <?= date('d/m/Y H:i', strtotime($ped['created_at'])); ?>
                                </td>
                                <td style="padding: 12px 15px;">
                                    <?php
                                    $col = '#eab308';
                                    if ($ped['estado'] === 'pagado') $col = '#10b981';
                                    if ($ped['estado'] === 'enviado') $col = '#3b82f6';
                                    if ($ped['estado'] === 'cancelado') $col = '#ef4444';
                                    ?>
                                    <span style="background: <?= $col; ?>25; color: <?= $col; ?>; border: 1px solid <?= $col; ?>; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                        ● <?= htmlspecialchars($ped['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px 15px; text-align: center;">
                                    <form action="cambiar_estado_pedido.php" method="POST" style="display: inline-flex; gap: 6px; align-items: center;">
                                        <input type="hidden" name="pedido_id" value="<?= $ped['id']; ?>">
                                        <select name="nuevo_estado" style="padding: 5px; border-radius: 4px; background: #222; color: #fff; border: 1px solid #444; font-size: 12px;">
                                            <option value="pendiente" <?= ($ped['estado'] === 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="pagado" <?= ($ped['estado'] === 'pagado') ? 'selected' : ''; ?>>Pagado</option>
                                            <option value="enviado" <?= ($ped['estado'] === 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                                            <option value="cancelado" <?= ($ped['estado'] === 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                        <button type="submit" style="background: #3b82f6; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; cursor: pointer;">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- MÓDULO 3: GESTIÓN DE USUARIOS Y ROLES -->
    <?php if ($tab_activa === 'usuarios'): ?>
        <div style="background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); overflow-x: auto; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.4); border-bottom: 1px solid rgba(255,255,255,0.15); color: #c084fc;">
                        <th style="padding: 14px 15px;">ID</th>
                        <th style="padding: 14px 15px;">Nombre de Usuario</th>
                        <th style="padding: 14px 15px;">Correo Electrónico</th>
                        <th style="padding: 14px 15px;">Rol Asignado</th>
                        <th style="padding: 14px 15px;">Fecha de Registro</th>
                        <th style="padding: 14px 15px; text-align: center;">Acción de Rol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usr): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 12px 15px; font-weight: bold; color: var(--accent-pink, #ff69b4);">#<?= $usr['id']; ?></td>
                            <td style="padding: 12px 15px; font-weight: 600;">
                                <?= htmlspecialchars($usr['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="padding: 12px 15px; color: #94a3b8;">
                                <?= htmlspecialchars($usr['email'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="padding: 12px 15px;">
                                <?php if ($usr['rol_nombre'] === 'admin'): ?>
                                    <span style="background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                        👑 Administrador
                                    </span>
                                <?php else: ?>
                                    <span style="background: rgba(192, 132, 252, 0.2); color: #c084fc; border: 1px solid #c084fc; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                        💜 Fan Club (Usuario)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px 15px; font-size: 12.5px; color: #cbd5e1;">
                                <?= date('d/m/Y', strtotime($usr['created_at'])); ?>
                            </td>
                            <td style="padding: 12px 15px; text-align: center;">
                                <?php if ($usr['id'] === (int)$_SESSION['usuario_id']): ?>
                                    <span style="color: #64748b; font-size: 12px; font-style: italic;">(Tu sesión activa)</span>
                                <?php else: ?>
                                    <form action="cambiar_rol.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="usuario_id" value="<?= $usr['id']; ?>">
                                        <?php if ($usr['rol_nombre'] === 'admin'): ?>
                                            <input type="hidden" name="nuevo_rol_id" value="2">
                                            <button type="submit" onclick="return confirm('¿Cambiar a rol Usuario Fan Club?');" style="background: #eab308; color: #000; border: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: bold;">
                                                Pasar a Usuario
                                            </button>
                                        <?php else: ?>
                                            <input type="hidden" name="nuevo_rol_id" value="1">
                                            <button type="submit" onclick="return confirm('¿Ascender a este usuario como Administrador?');" style="background: #10b981; color: #fff; border: none; padding: 5px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: bold;">
                                                Hacer Admin
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
