<?php
/**
 * contacto.php
 * Formulario de contacto y buscador dinámico de Tini con sanitización XSS
 * Estudiante: Frias Abigail
 */

$titulo_pagina = "Contacto y Búsqueda de Merch";
$pagina_activa = "contacto";

require_once __DIR__ . '/includes/header.php';

// Variables de estado
$mensaje_exito = "";
$errores = [];

// Persistencia de campos POST
$nombre = "";
$email = "";
$asunto = "";
$mensaje = "";

// Procesamiento POST (Formulario de Contacto)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asunto = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    // Validaciones en servidor
    if (empty($nombre)) {
        $errores[] = "El nombre es un campo obligatorio.";
    } elseif (strlen($nombre) < 3) {
        $errores[] = "El nombre debe contener al menos 3 caracteres.";
    }

    if (empty($email)) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato de correo electrónico no es válido.";
    }

    if (empty($mensaje)) {
        $errores[] = "El mensaje no puede quedar vacío.";
    }

    if (empty($errores)) {
        $mensaje_exito = "¡Gracias " . htmlspecialchars($nombre) . "! Tu mensaje sobre TINI fue enviado con éxito. Te responderemos a " . htmlspecialchars($email) . " a la brevedad.";
        // Limpiar persistencia tras éxito
        $nombre = "";
        $email = "";
        $asunto = "";
        $mensaje = "";
    }
}

// Procesamiento GET (Buscador de Discografía y Merch)
$busqueda = trim($_GET['q'] ?? '');
$categoria_filtro = trim($_GET['cat'] ?? 'todas');

// Catálogo de prueba para búsquedas
$catalogo_tini = [
    ['tipo' => 'album', 'nombre' => 'Cupido (Álbum Deluxe)', 'anio' => '2023', 'precio' => 18000],
    ['tipo' => 'album', 'nombre' => 'TINI TINI TINI', 'anio' => '2020', 'precio' => 15000],
    ['tipo' => 'album', 'nombre' => 'Un Mechón de Pelo', 'anio' => '2024', 'precio' => 20000],
    ['tipo' => 'merch', 'nombre' => 'Remera Tour Oficial 2026', 'anio' => '2026', 'precio' => 25000],
    ['tipo' => 'merch', 'nombre' => 'Hoodie Cupido Rosa', 'anio' => '2026', 'precio' => 42000],
    ['tipo' => 'merch', 'nombre' => 'Gorra TINI Oficial', 'anio' => '2026', 'precio' => 12000],
];

$resultados_busqueda = [];
if (!empty($busqueda) || $categoria_filtro !== 'todas') {
    foreach ($catalogo_tini as $item) {
        $coincide_texto = empty($busqueda) || (stripos($item['nombre'], $busqueda) !== false);
        $coincide_cat = ($categoria_filtro === 'todas') || ($item['tipo'] === $categoria_filtro);
        if ($coincide_texto && $coincide_cat) {
            $resultados_busqueda[] = $item;
        }
    }
}
?>

<main style="max-width: 960px; margin: 40px auto; padding: 0 20px;">

    <h1 style="color: var(--accent-pink, #ff69b4); text-align: center; margin-bottom: 30px;">
        Centro de Contacto & Buscador Oficial TINI
    </h1>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">

        <!-- Módulo GET: Buscador de Productos y Música -->
        <div style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
            <h2 style="color: #c084fc; font-size: 18px; margin-top: 0;">🔍 Búsqueda Rápida (Método GET)</h2>
            <p style="font-size: 13px; color: #ccc;">Busca canciones, álbumes o productos de merchandising oficiales:</p>
            
            <form action="contacto.php" method="GET">
                <div style="margin-bottom: 12px;">
                    <label for="q" style="font-size: 13px; font-weight: bold;">Término de búsqueda:</label>
                    <input type="text" id="q" name="q" value="<?= htmlspecialchars($busqueda); ?>" placeholder="Ej: Cupido, Remera, Tour..." style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="cat" style="font-size: 13px; font-weight: bold;">Filtrar por categoría:</label>
                    <select id="cat" name="cat" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                        <option value="todas" <?= ($categoria_filtro === 'todas') ? 'selected' : ''; ?>>Todas las categorías</option>
                        <option value="album" <?= ($categoria_filtro === 'album') ? 'selected' : ''; ?>>Álbumes / Música</option>
                        <option value="merch" <?= ($categoria_filtro === 'merch') ? 'selected' : ''; ?>>Merchandising Oficial</option>
                    </select>
                </div>
                <button type="submit" class="btn-ticket" style="width: 100%; border: none; cursor: pointer;">Buscar en el Sitio</button>
            </form>

            <?php if (!empty($busqueda) || $categoria_filtro !== 'todas'): ?>
                <div style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <h3 style="font-size: 15px; color: #00ff88;">Resultados para: "<?= htmlspecialchars($busqueda); ?>"</h3>
                    <?php if (empty($resultados_busqueda)): ?>
                        <p style="font-size: 13px; color: #ff8080;">No se encontraron elementos que coincidan con la búsqueda.</p>
                    <?php else: ?>
                        <ul style="font-size: 13px; padding-left: 18px;">
                            <?php foreach ($resultados_busqueda as $res): ?>
                                <li>
                                    <strong><?= htmlspecialchars($res['nombre']); ?></strong> 
                                    (<?= htmlspecialchars(strtoupper($res['tipo'])); ?> - <?= htmlspecialchars($res['anio']); ?>) 
                                    - <span style="color:var(--accent-pink);">$<?= number_format($res['precio'], 0, ',', '.'); ?> ARS</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Módulo POST: Formulario de Contacto -->
        <div style="background: rgba(255,255,255,0.05); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
            <h2 style="color: #c084fc; font-size: 18px; margin-top: 0;">✉️ Formulario de Contacto (Método POST)</h2>
            <p style="font-size: 13px; color: #ccc;">Envíanos tu consulta, mensaje para el Fan Club o propuesta:</p>

            <?php if (!empty($errores)): ?>
                <div style="background: rgba(255, 0, 0, 0.2); border-left: 4px solid #ff4d4d; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px;">
                    <strong style="color: #ff9999;">Corrige los siguientes errores:</strong>
                    <ul style="margin: 5px 0 0 16px; padding: 0; color: #ffd6d6; font-size: 12.5px;">
                        <?php foreach ($errores as $err): ?>
                            <li><?= htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensaje_exito)): ?>
                <div style="background: rgba(0, 255, 136, 0.2); border-left: 4px solid #00ff88; padding: 12px 14px; border-radius: 4px; margin-bottom: 15px; color: #d4ffeb; font-size: 13px;">
                    <?= $mensaje_exito; ?>
                </div>
            <?php endif; ?>

            <form action="contacto.php" method="POST">
                <div style="margin-bottom: 12px;">
                    <label for="nombre" style="font-size: 13px; font-weight: bold;">Nombre Completo *:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre); ?>" placeholder="Tu nombre" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label for="email" style="font-size: 13px; font-weight: bold;">Correo Electrónico *:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" placeholder="tu@email.com" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label for="asunto" style="font-size: 13px; font-weight: bold;">Asunto:</label>
                    <input type="text" id="asunto" name="asunto" value="<?= htmlspecialchars($asunto); ?>" placeholder="Motivo de tu mensaje" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="mensaje" style="font-size: 13px; font-weight: bold;">Mensaje *:</label>
                    <textarea id="mensaje" name="mensaje" rows="4" placeholder="Escribe aquí tu consulta..." required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;"><?= htmlspecialchars($mensaje); ?></textarea>
                </div>
                <button type="submit" class="btn-ticket" style="width: 100%; border: none; cursor: pointer;">Enviar Mensaje 💌</button>
            </form>
        </div>

    </div>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
