<?php
/**
 * includes/nav.php
 * Barra de navegación principal del sitio con resaltado de sección activa
 * Estudiante: Frias Abigail
 */
$seccion_activa = $pagina_activa ?? 'inicio';
?>
<header>
    <div class="logo"><span>💜</span> TINI</div>
    <nav>
        <a href="index.php#inicio" class="<?= ($seccion_activa === 'inicio') ? 'active' : ''; ?>">Inicio</a>
        <a href="index.php#bio" class="<?= ($seccion_activa === 'bio') ? 'active' : ''; ?>">Bio</a>
        <a href="index.php#musica" class="<?= ($seccion_activa === 'musica') ? 'active' : ''; ?>">Música</a>
        <a href="index.php#tour" class="<?= ($seccion_activa === 'tour') ? 'active' : ''; ?>">Tour</a>
        <a href="index.php#tienda" class="<?= ($seccion_activa === 'tienda') ? 'active' : ''; ?>">Tienda</a>
        <a href="index.php#fanclub" class="<?= ($seccion_activa === 'fanclub') ? 'active' : ''; ?>">Fan Club</a>
        <a href="proyecto.php" class="<?= ($seccion_activa === 'proyecto') ? 'active' : ''; ?>">Proyecto</a>
        <a href="contacto.php" class="<?= ($seccion_activa === 'contacto') ? 'active' : ''; ?>">Contacto / Búsqueda</a>
        <a href="admin/index.php" class="<?= ($seccion_activa === 'admin') ? 'active' : ''; ?>">Panel Admin</a>
    </nav>
</header>
<div style="text-align: center; margin-top: 15px;">
    <button id="toggle-tema" class="btn-tema">☀️ Cambiar Modo</button>
</div>
