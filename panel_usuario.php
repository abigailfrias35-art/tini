<?php
/**
 * panel_usuario.php
 * Panel de Usuario / Área Exclusiva de Miembros del Fan Club Oficial de TINI
 * Estudiante: Frias Abigail
 * Materia: Adaptación al ambiente de trabajo & Programación Web
 */

require_once __DIR__ . '/auth_guard.php';
verificar_acceso(); // Requiere inicio de sesión

require_once __DIR__ . '/config/conexion.php';

$usuario_id = (int)$_SESSION['usuario_id'];
$usuario_info = null;
$pedidos = [];
$error_db = null;

try {
    // 1. Obtener información del usuario actual
    $stmt_user = $pdo->prepare("
        SELECT u.id, u.nombre, u.email, u.created_at, r.nombre AS rol 
        FROM usuarios u 
        INNER JOIN roles r ON u.rol_id = r.id 
        WHERE u.id = :id 
        LIMIT 1
    ");
    $stmt_user->execute([':id' => $usuario_id]);
    $usuario_info = $stmt_user->fetch();

    // 2. Obtener historial de pedidos del usuario
    $stmt_ped = $pdo->prepare("
        SELECT p.id, p.total, p.estado, p.created_at 
        FROM pedidos p 
        WHERE p.usuario_id = :usuario_id 
        ORDER BY p.id DESC
    ");
    $stmt_ped->execute([':usuario_id' => $usuario_id]);
    $pedidos = $stmt_ped->fetchAll();

    // 3. Obtener detalles de productos para cada pedido
    foreach ($pedidos as &$ped) {
        $stmt_det = $pdo->prepare("
            SELECT dp.cantidad, dp.precio_unitario, pr.titulo, pr.imagen 
            FROM detalle_pedidos dp 
            INNER JOIN productos pr ON dp.producto_id = pr.id 
            WHERE dp.pedido_id = :pedido_id
        ");
        $stmt_det->execute([':pedido_id' => $ped['id']]);
        $ped['detalles'] = $stmt_det->fetchAll();
    }
    unset($ped);

} catch (\PDOException $e) {
    $error_db = "Error al consultar la base de datos: " . $e->getMessage();
}

$titulo_pagina = "Mi Panel Fan Club - TINI";
$pagina_activa = "usuario";
require_once __DIR__ . '/includes/header.php';
?>

<main style="max-width: 1100px; margin: 35px auto; padding: 0 20px;">

    <!-- ENCABEZADO DE BIENVENIDA -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid var(--accent-pink, #ff69b4); padding-bottom: 15px; margin-bottom: 25px;">
        <div>
            <h1 style="margin: 0; color: #fff; font-size: 26px;">
                💜 Panel de Fan Club Oficial
            </h1>
            <p style="margin: 6px 0 0 0; color: #cbd5e1; font-size: 14px;">
                Bienvenida/o, <strong><?= htmlspecialchars($usuario_info['nombre'] ?? $_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong> 
                &bull; Miembro Oficial del Fan Club TINI
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="index.php#tienda" class="btn-ticket" style="background: var(--gradient-button); text-decoration: none; font-size: 13px; padding: 10px 18px;">🛍️ Ir a la Tienda</a>
            <a href="logout.php" class="btn-ticket" style="background: #ef4444; text-decoration: none; font-size: 13px; padding: 10px 18px;">Cerrar Sesión</a>
        </div>
    </div>

    <?php if (isset($_GET['login']) && $_GET['login'] === 'success'): ?>
        <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 6px; margin-bottom: 25px; color: #a7f3d0; font-size: 13.5px;">
            ✓ ¡Inicio de sesión exitoso! Acceso concedido a los beneficios exclusivos del Fan Club de Tini.
        </div>
    <?php endif; ?>

    <?php if ($error_db): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 25px; color: #fecaca; font-size: 13px;">
            <?= htmlspecialchars($error_db, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <!-- SECCIÓN DE CARNET DIGITAL VIP Y BENEFICIOS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px; margin-bottom: 35px;">
        
        <!-- TARJETA DE MEMBRESÍA VIP -->
        <div style="background: linear-gradient(135deg, #3b0764 0%, #701a75 50%, #831843 100%); border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); position: relative; overflow: hidden;">
            <div style="position: absolute; right: -20px; bottom: -20px; font-size: 130px; opacity: 0.1; pointer-events: none;">💜</div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <span style="font-size: 18px; font-weight: bold; letter-spacing: 2px; color: #fff;">TINI FAN CLUB</span>
                <span style="background: #fbbf24; color: #000; font-weight: bold; font-size: 11px; padding: 3px 8px; border-radius: 12px; text-transform: uppercase;">VIP Pass</span>
            </div>

            <div style="margin-bottom: 20px;">
                <div style="font-size: 12px; color: #f5d0fe; text-transform: uppercase;">Titular de la Membresía</div>
                <div style="font-size: 20px; font-weight: bold; color: #fff; margin-top: 2px;">
                    <?= htmlspecialchars($usuario_info['nombre'] ?? 'Tinista Oficial', ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <div style="font-size: 13px; color: #e9d5ff;"><?= htmlspecialchars($usuario_info['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px;">
                <div>
                    <div style="font-size: 11px; color: #f5d0fe;">N° DE SOCIO</div>
                    <div style="font-family: monospace; font-size: 14px; color: #fff; font-weight: bold;">
                        #TINI-<?= str_pad((string)$usuario_id, 4, '0', STR_PAD_LEFT); ?>-2026
                    </div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #f5d0fe;">ESTADO</div>
                    <div style="color: #4ade80; font-weight: bold; font-size: 13px;">● ACTIVO</div>
                </div>
            </div>
        </div>

        <!-- TARJETA DE BENEFICIOS EXCLUSIVOS -->
        <div style="background: rgba(255,255,255,0.05); border-radius: 16px; padding: 25px; border: 1px solid rgba(255,255,255,0.1); display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h3 style="color: var(--accent-pink, #ff69b4); margin-top: 0; font-size: 18px; margin-bottom: 15px;">
                    🎁 Beneficios Exclusivos de tu Cuenta
                </h3>
                
                <div style="margin-bottom: 12px; display: flex; gap: 10px; align-items: flex-start;">
                    <span style="font-size: 20px;">🎟️</span>
                    <div>
                        <strong style="color: #fff; font-size: 13.5px;">Código de Preventa Tour 2026:</strong>
                        <div style="margin-top: 3px;">
                            <code style="background: rgba(224, 36, 179, 0.25); color: #f472b6; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 13px;">TINI2026-FANVIP</code>
                            <span style="font-size: 12px; color: #aaa; margin-left: 6px;">(Válido para Salta y Movistar Arena)</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 12px; display: flex; gap: 10px; align-items: flex-start;">
                    <span style="font-size: 20px;">⭐</span>
                    <div>
                        <strong style="color: #fff; font-size: 13.5px;">Sorteo Meet & Greet:</strong>
                        <p style="margin: 2px 0 0 0; font-size: 12.5px; color: #cbd5e1;">
                            Tu cuenta participa automáticamente en el sorteo para conocer a Tini en el backstage.
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <span style="font-size: 20px;">🎵</span>
                    <div>
                        <strong style="color: #fff; font-size: 13.5px;">Descuento en Tienda Oficial:</strong>
                        <p style="margin: 2px 0 0 0; font-size: 12.5px; color: #cbd5e1;">
                            15% OFF en remeras, vinilos y hoodies oficiales de Cupido.
                        </p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.08);">
                <a href="index.php#tour" style="color: #c084fc; text-decoration: none; font-size: 13px; font-weight: bold;">
                    Ver fechas de conciertos del Tour 2026 &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE HISTORIAL DE PEDIDOS Y COMPRAS -->
    <div style="background: rgba(255,255,255,0.05); border-radius: 14px; border: 1px solid rgba(255,255,255,0.1); padding: 25px; margin-bottom: 35px;">
        <h2 style="color: #fff; font-size: 20px; margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <span>📦</span> Mis Compras y Pedidos de Merchandising
        </h2>

        <?php if (empty($pedidos)): ?>
            <div style="text-align: center; padding: 40px 20px; background: rgba(0,0,0,0.2); border-radius: 10px;">
                <div style="font-size: 45px; margin-bottom: 10px;">🛍️</div>
                <h3 style="color: #fff; margin: 0 0 8px 0; font-size: 18px;">Aún no tienes pedidos registrados</h3>
                <p style="color: #94a3b8; font-size: 13.5px; max-width: 450px; margin: 0 auto 20px auto;">
                    Aprovecha tu membresía de Fan Club para adquirir la Remera Oficial del Tour 2026, el Hoodie Cupido o el CD Deluxe.
                </p>
                <a href="index.php#tienda" class="btn-ticket" style="background: var(--gradient-button); text-decoration: none; padding: 10px 20px; font-size: 14px;">Explorar Catálogo Oficial</a>
            </div>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php foreach ($pedidos as $p): ?>
                    <div style="background: rgba(0,0,0,0.3); border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); padding: 18px;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 12px; margin-bottom: 14px;">
                            <div>
                                <span style="font-weight: bold; color: var(--accent-pink, #ff69b4); font-size: 15px;">
                                    Pedido #PED-<?= str_pad((string)$p['id'], 4, '0', STR_PAD_LEFT); ?>
                                </span>
                                <span style="font-size: 12.5px; color: #94a3b8; margin-left: 10px;">
                                    📅 <?= date('d/m/Y H:i', strtotime($p['created_at'])); ?> hs
                                </span>
                            </div>
                            <div>
                                <?php
                                $estado_color = '#eab308'; // pendiente
                                if ($p['estado'] === 'pagado') $estado_color = '#10b981';
                                if ($p['estado'] === 'enviado') $estado_color = '#3b82f6';
                                if ($p['estado'] === 'cancelado') $estado_color = '#ef4444';
                                ?>
                                <span style="background: <?= $estado_color; ?>25; color: <?= $estado_color; ?>; border: 1px solid <?= $estado_color; ?>; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                    ● <?= htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <span style="margin-left: 15px; font-weight: bold; color: #fff; font-size: 16px;">
                                    $<?= number_format($p['total'], 2, ',', '.'); ?> ARS
                                </span>
                            </div>
                        </div>

                        <!-- Detalle de artículos comprados -->
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <?php foreach ($p['detalles'] as $item): ?>
                                <div style="display: flex; align-items: center; gap: 12px; font-size: 13.5px;">
                                    <img src="<?= htmlspecialchars($item['imagen'] ?? 'merch.jpg', ENT_QUOTES, 'UTF-8'); ?>" alt="Img" style="width: 38px; height: 38px; object-fit: cover; border-radius: 6px;">
                                    <div style="flex: 1;">
                                        <strong><?= htmlspecialchars($item['titulo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span style="color: #94a3b8; font-size: 12px; margin-left: 8px;">(x<?= (int)$item['cantidad']; ?>)</span>
                                    </div>
                                    <div style="color: #4ade80; font-weight: 600;">
                                        $<?= number_format($item['precio_unitario'] * $item['cantidad'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ACCESOS RÁPIDOS A TODAS LAS SECCIONES DE LA PÁGINA -->
    <div style="background: rgba(255,255,255,0.03); border-radius: 14px; border: 1px solid rgba(255,255,255,0.08); padding: 22px;">
        <h3 style="color: #fff; font-size: 16px; margin-top: 0; margin-bottom: 15px;">
            🚀 Accesos Rápidos a la Plataforma
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
            <a href="index.php#inicio" style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 13px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                🏠 Inicio Web
            </a>
            <a href="index.php#musica" style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 13px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                🎵 Discografía
            </a>
            <a href="index.php#tour" style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 13px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                🎫 Tour Dates 2026
            </a>
            <a href="index.php#tienda" style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 13px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                👕 Tienda Oficial
            </a>
            <a href="proyecto.php" style="background: rgba(255,255,255,0.06); padding: 12px; border-radius: 8px; text-decoration: none; color: #fff; font-size: 13px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                📄 Proyecto Técnico
            </a>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
