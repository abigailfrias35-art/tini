<?php
/**
 * includes/footer.php
 * Pie de página y cierre del documento con scripts
 * Estudiante: Frias Abigail
 */
$script_dir = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$asset_prefix = ($script_dir === 'admin' || $script_dir === 'clase5' || $script_dir === 'usuario') ? '../' : './';
?>
<footer>
    <div style="text-align: center; padding: 25px 15px; margin-top: 50px; border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3);">
        <p style="margin: 0 0 6px 0;">&copy; <?= date('Y'); ?> <strong><?= htmlspecialchars(APP_NAME); ?></strong> &bull; Sitio web desarrollado por <strong>Frias Abigail</strong></p>
        <p style="font-size: 13px; color: #a8a8a8; margin: 0 0 4px 0;">Materia: Adaptación al ambiente de trabajo &bull; Programación Web</p>
        <p style="font-size: 12px; color: #888; margin: 0;">Contacto oficial: <a href="mailto:<?= htmlspecialchars(APP_EMAIL); ?>" style="color: var(--accent-pink, #ff69b4); text-decoration: none;"><?= htmlspecialchars(APP_EMAIL); ?></a> | Entorno: <span style="color:#00ff88;"><?= htmlspecialchars(APP_ENV); ?></span></p>
    </div>
</footer>

<!-- Scripts JavaScript -->
<script src="<?= $asset_prefix; ?>js.js"></script>
</body>
</html>
