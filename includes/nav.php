<?php
/**
 * includes/nav.php
 * Barra de navegación principal y dinámica con roles y sesiones
 * Estudiante: Frias Abigail
 * Materia: Adaptación al ambiente de trabajo
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$script_dir = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$prefix = ($script_dir === 'admin' || $script_dir === 'clase5' || $script_dir === 'usuario') ? '../' : './';
$seccion_activa = $pagina_activa ?? 'inicio';

$esta_logueado = isset($_SESSION['usuario_id']);
$es_admin = $esta_logueado && (($_SESSION['rol'] ?? '') === 'admin');
$nombre_usuario = $_SESSION['usuario_nombre'] ?? '';
?>
<header>
    <div class="logo">
        <a href="<?= $prefix; ?>index.php" style="color: inherit; text-decoration: none;"><span>💜</span> TINI</a>
    </div>
    <nav>
        <a href="<?= $prefix; ?>index.php#inicio" class="<?= ($seccion_activa === 'inicio') ? 'active' : ''; ?>">Inicio</a>
        <a href="<?= $prefix; ?>index.php#bio" class="<?= ($seccion_activa === 'bio') ? 'active' : ''; ?>">Bio</a>
        <a href="<?= $prefix; ?>index.php#musica" class="<?= ($seccion_activa === 'musica') ? 'active' : ''; ?>">Música</a>
        <a href="<?= $prefix; ?>index.php#tour" class="<?= ($seccion_activa === 'tour') ? 'active' : ''; ?>">Tour</a>
        <a href="<?= $prefix; ?>index.php#tienda" class="<?= ($seccion_activa === 'tienda') ? 'active' : ''; ?>">Tienda</a>
        <a href="<?= $prefix; ?>proyecto.php" class="<?= ($seccion_activa === 'proyecto') ? 'active' : ''; ?>">Proyecto</a>

        <?php if ($es_admin): ?>
            <!-- Menú Exclusivo para Administrador -->
            <a href="<?= $prefix; ?>admin/index.php" class="<?= ($seccion_activa === 'admin') ? 'active' : ''; ?>" style="color: #00ff88; font-weight: bold;">⭐ Panel Admin</a>
            <span style="font-size: 12px; color: #cbd5e1; margin-left: 10px;">👑 <?= htmlspecialchars($nombre_usuario, ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="<?= $prefix; ?>logout.php" style="color: #ef4444; font-size: 13px;">Salir</a>

        <?php elseif ($esta_logueado): ?>
            <!-- Menú Exclusivo para Usuario / Fan Club -->
            <a href="<?= $prefix; ?>panel_usuario.php" class="<?= ($seccion_activa === 'usuario') ? 'active' : ''; ?>" style="color: #c084fc; font-weight: bold;">💜 Mi Panel Fan</a>
            <span style="font-size: 12px; color: #cbd5e1; margin-left: 10px;">👤 <?= htmlspecialchars($nombre_usuario, ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="<?= $prefix; ?>logout.php" style="color: #ef4444; font-size: 13px;">Salir</a>

        <?php else: ?>
            <!-- Menú para Visitantes Públicos -->
            <a href="<?= $prefix; ?>index.php#fanclub" class="<?= ($seccion_activa === 'fanclub') ? 'active' : ''; ?>">Fan Club</a>
            <a href="<?= $prefix; ?>login.php" class="<?= ($seccion_activa === 'login') ? 'active' : ''; ?>" style="border: 1px solid var(--accent-pink); padding: 4px 10px; border-radius: 6px;">Iniciar Sesión</a>
            <a href="<?= $prefix; ?>registro.php" class="<?= ($seccion_activa === 'registro') ? 'active' : ''; ?>" style="background: var(--accent-pink); color: #fff; padding: 4px 10px; border-radius: 6px;">Registrarse</a>
        <?php endif; ?>
    </nav>
</header>
<div style="text-align: center; margin-top: 15px;">
    <button id="toggle-tema" class="btn-tema" onclick="toggleDarkMode()">☀️ Cambiar Modo</button>
</div>
