<?php
/**
 * proyecto.php
 * Vista de información del proyecto y ficha técnica del sitio
 * Estudiante: Frias Abigail
 */

$titulo_pagina = "Proyecto - Ficha Técnica y Objetivos";
$pagina_activa = "proyecto";

require_once __DIR__ . '/includes/header.php';
?>

<main style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <h1 style="color: var(--accent-pink, #ff69b4); border-bottom: 2px solid var(--accent-pink, #ff69b4); padding-bottom: 10px;">
        Acerca del Proyecto Web TINI
    </h1>

    <div style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 10px; margin-top: 25px; border: 1px solid rgba(255,255,255,0.1);">
        <h2>Información Institucional</h2>
        <ul style="line-height: 1.8;">
            <li><strong>Estudiante:</strong> Frias Abigail</li>
            <li><strong>Materia:</strong> Adaptación al ambiente de trabajo &bull; Programación Web</li>
            <li><strong>Temática:</strong> Plataforma integral de la artista internacional <em>TINI (Martina Stoessel)</em></li>
            <li><strong>Entorno de Ejecución:</strong> <?= htmlspecialchars(APP_ENV); ?> (PHP 8.x + MySQL + Servidor Apache XAMPP/Laragon)</li>
        </ul>

        <h2 style="margin-top: 25px;">Evolución Arquitectónica (Unidad 4)</h2>
        <p>El proyecto nació como una maqueta HTML5/CSS3 estática y fue refactorizado exitosamente aplicando arquitectura <strong>Server-Side Rendering (SSR)</strong> con <strong>Server Side Includes (SSI)</strong> mediante PHP:</p>
        <ol style="line-height: 1.8;">
            <li><strong>Modularización de Plantillas:</strong> Extracción de <code>includes/header.php</code>, <code>includes/nav.php</code> y <code>includes/footer.php</code> para erradicar la duplicación de código en vistas.</li>
            <li><strong>Metadatos y Títulos Dinámicos:</strong> Cada pantalla inyecta su propia variable <code>$titulo_pagina</code> y resalta dinámicamente el ítem activo en la barra de navegación.</li>
            <li><strong>Variables de Entorno (.env):</strong> Configuración centralizada de credenciales y constantes en <code>config/env.php</code>, protegiendo credenciales sensibles mediante <code>.gitignore</code>.</li>
        </ol>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
