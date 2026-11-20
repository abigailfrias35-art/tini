<?php
/**
 * clase5/index.php
 * TP Clase 5: Procesamiento de Formularios POST y GET con Sanitización Estricta (XSS)
 * Materia: Adaptación al ambiente de trabajo & Programación Web
 * Estudiante: Frias Abigail
 * Proyecto: TINI - Sitio Oficial & Fan Club
 */

require_once __DIR__ . '/../config/env.php';

// ==========================================
// 1. MANEJO DEL FORMULARIO POST (FAN CLUB)
// ==========================================
$post_exito = false;
$post_errores = [];

// Variables para persistencia de datos ante error
$p_nombre = '';
$p_email = '';
$p_ciudad = '';
$p_era = '';
$p_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion_post'])) {
    // Sanitización básica con trim() y htmlspecialchars()
    $p_nombre = trim($_POST['nombre'] ?? '');
    $p_email = trim($_POST['email'] ?? '');
    $p_ciudad = trim($_POST['ciudad'] ?? '');
    $p_era = trim($_POST['era'] ?? '');
    $p_mensaje = trim($_POST['mensaje'] ?? '');

    // Validaciones estrictas en el lado del servidor
    if (empty($p_nombre)) {
        $post_errores[] = "El nombre y apellido son obligatorios.";
    } elseif (mb_strlen($p_nombre) < 3) {
        $post_errores[] = "El nombre debe contener al menos 3 caracteres.";
    }

    if (empty($p_email)) {
        $post_errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($p_email, FILTER_VALIDATE_EMAIL)) {
        $post_errores[] = "El formato del correo electrónico ingresado no es válido.";
    }

    if (empty($p_era)) {
        $post_errores[] = "Debes seleccionar tu álbum o era favorita de Tini.";
    }

    if (empty($p_mensaje)) {
        $post_errores[] = "Por favor cuéntanos por qué deseas unirte al Fan Club.";
    } elseif (mb_strlen($p_mensaje) < 10) {
        $post_errores[] = "El mensaje debe tener al menos 10 caracteres.";
    }

    // Si no hubo errores, procesamos el registro
    if (empty($post_errores)) {
        $post_exito = true;
        $mensaje_confirmacion = "¡Bienvenida/o al Fan Club Oficial, " . htmlspecialchars($p_nombre, ENT_QUOTES, 'UTF-8') . "! Te enviamos la credencial digital a " . htmlspecialchars($p_email, ENT_QUOTES, 'UTF-8') . ".";
        
        // Reset de campos para vaciar el formulario tras éxito
        $p_nombre = $p_email = $p_ciudad = $p_era = $p_mensaje = '';
    }
}

// ==========================================
// 2. MANEJO DEL FORMULARIO GET (BUSCADOR)
// ==========================================
$g_busqueda = trim($_GET['buscar'] ?? '');
$g_categoria = trim($_GET['categoria'] ?? 'todas');
$g_orden = trim($_GET['orden'] ?? 'asc');

// Base de datos de prueba en memoria para canciones, álbumes y merchandising de TINI
$catalogo_tini = [
    ['id' => 1, 'nombre' => 'Cupido (Álbum Completo)', 'categoria' => 'album', 'anio' => 2023, 'precio' => 18000],
    ['id' => 2, 'nombre' => 'Un Mechón de Pelo (Vinilo)', 'categoria' => 'album', 'anio' => 2024, 'precio' => 24000],
    ['id' => 3, 'nombre' => 'TINI TINI TINI (CD)', 'categoria' => 'album', 'anio' => 2020, 'precio' => 15000],
    ['id' => 4, 'nombre' => 'La Triple T (Single)', 'categoria' => 'cancion', 'anio' => 2022, 'precio' => 1200],
    ['id' => 5, 'nombre' => 'Miénteme feat. María Becerra', 'categoria' => 'cancion', 'anio' => 2021, 'precio' => 1200],
    ['id' => 6, 'nombre' => 'Pa (Single)', 'categoria' => 'cancion', 'anio' => 2024, 'precio' => 1500],
    ['id' => 7, 'nombre' => 'Remera Oficial Tour 2026', 'categoria' => 'merch', 'anio' => 2026, 'precio' => 25000],
    ['id' => 8, 'nombre' => 'Hoodie Cupido Rosa Oversize', 'categoria' => 'merch', 'anio' => 2026, 'precio' => 42000],
    ['id' => 9, 'nombre' => 'Gorra Trucker TINI Bordada', 'categoria' => 'merch', 'anio' => 2026, 'precio' => 14000],
    ['id' => 10, 'nombre' => 'Vaso Térmico Tour TINI', 'categoria' => 'merch', 'anio' => 2026, 'precio' => 9500],
];

$resultados_get = [];
$ha_buscado = isset($_GET['buscar']) || isset($_GET['categoria']);

if ($ha_buscado) {
    foreach ($catalogo_tini as $item) {
        $match_texto = empty($g_busqueda) || (stripos($item['nombre'], $g_busqueda) !== false);
        $match_cat = ($g_categoria === 'todas') || ($item['categoria'] === $g_categoria);
        if ($match_texto && $match_cat) {
            $resultados_get[] = $item;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clase 5: Formularios POST y GET - Frias Abigail</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .container-clase5 {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }
        .header-clase5 {
            background: linear-gradient(135deg, #2b0938 0%, #170420 100%);
            border: 1px solid #c084fc;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .grid-clase5 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        .card-box {
            background: rgba(30, 15, 45, 0.85);
            border: 1px solid rgba(192, 132, 252, 0.3);
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }
        .card-box h2 {
            color: #ff69b4;
            font-size: 18px;
            margin-top: 0;
            border-bottom: 1px solid rgba(255,105,180,0.3);
            padding-bottom: 8px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
        }
        .form-control-c5 {
            width: 100%;
            padding: 9px 12px;
            border-radius: 6px;
            border: 1px solid #4a3055;
            background: #180c24;
            color: #fff;
            box-sizing: border-box;
            font-size: 13.5px;
        }
        .form-control-c5:focus {
            outline: none;
            border-color: #ff69b4;
            box-shadow: 0 0 5px rgba(255,105,180,0.5);
        }
        .btn-c5 {
            background: linear-gradient(90deg, #ff69b4, #c084fc);
            color: #fff;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
            transition: 0.3s ease;
        }
        .btn-c5:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            border-left: 4px solid #ef4444;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            color: #fecaca;
            font-size: 13px;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-left: 4px solid #10b981;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            color: #a7f3d0;
            font-size: 13px;
        }
        .badge-cat {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-album { background: #3b82f6; color: #fff; }
        .badge-cancion { background: #8b5cf6; color: #fff; }
        .badge-merch { background: #ec4899; color: #fff; }
        .nav-back {
            display: inline-block;
            margin-bottom: 15px;
            color: #ff69b4;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container-clase5">

    <a href="../index.php" class="nav-back">&larr; Volver al Sitio Oficial de TINI</a>

    <div class="header-clase5">
        <h1 style="margin: 0 0 8px 0; font-size: 22px; color: #ff69b4;">TRABAJO PRÁCTICO CLASE 5: FORMULARIOS POST Y GET</h1>
        <p style="margin: 0; font-size: 14px; color: #cbd5e1;">
            Materia: <strong>Adaptación al ambiente de trabajo</strong> &bull; Estudiante: <strong>Frias Abigail</strong> &bull; Año 2026
        </p>
        <p style="margin: 6px 0 0 0; font-size: 12.5px; color: #a855f7;">
            Seguridad OWASP: Sanitización con <code>htmlspecialchars()</code>, validación de tipo de dato y persistencia de estado.
        </p>
    </div>

    <div class="grid-clase5">

        <!-- 1. FORMULARIO POST: FAN CLUB TINI -->
        <div class="card-box">
            <h2>💜 Formulario POST: Registro Fan Club</h2>
            <p style="font-size: 12.5px; color: #cbd5e1; margin-bottom: 15px;">
                Envío seguro de información confidencial sin exponer parámetros en la URL.
            </p>

            <?php if (!empty($post_errores)): ?>
                <div class="alert-error">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul style="margin: 6px 0 0 16px; padding: 0;">
                        <?php foreach ($post_errores as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($post_exito): ?>
                <div class="alert-success">
                    <?= $mensaje_confirmacion; ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST" novalidate>
                <input type="hidden" name="accion_post" value="1">

                <div class="form-group">
                    <label for="nombre">Nombre y Apellido *:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control-c5" 
                           value="<?= htmlspecialchars($p_nombre, ENT_QUOTES, 'UTF-8'); ?>" 
                           placeholder="Ej: Abigail Frías">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico *:</label>
                    <input type="email" id="email" name="email" class="form-control-c5" 
                           value="<?= htmlspecialchars($p_email, ENT_QUOTES, 'UTF-8'); ?>" 
                           placeholder="ejemplo@correo.com">
                </div>

                <div class="form-group">
                    <label for="ciudad">Ciudad / Provincia:</label>
                    <input type="text" id="ciudad" name="ciudad" class="form-control-c5" 
                           value="<?= htmlspecialchars($p_ciudad, ENT_QUOTES, 'UTF-8'); ?>" 
                           placeholder="Ej: Salta, Argentina">
                </div>

                <div class="form-group">
                    <label for="era">Era Favorita de TINI *:</label>
                    <select id="era" name="era" class="form-control-c5">
                        <option value="">-- Selecciona una era --</option>
                        <option value="Cupido" <?= ($p_era === 'Cupido') ? 'selected' : ''; ?>>Cupido (2023)</option>
                        <option value="Un Mechón de Pelo" <?= ($p_era === 'Un Mechón de Pelo') ? 'selected' : ''; ?>>Un Mechón de Pelo (2024)</option>
                        <option value="TINI TINI TINI" <?= ($p_era === 'TINI TINI TINI') ? 'selected' : ''; ?>>TINI TINI TINI (2020)</option>
                        <option value="Quiero Volver" <?= ($p_era === 'Quiero Volver') ? 'selected' : ''; ?>>Quiero Volver (2018)</option>
                        <option value="Violetta" <?= ($p_era === 'Violetta') ? 'selected' : ''; ?>>Violetta (Inicios)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="mensaje">¿Por qué quieres ser parte? *:</label>
                    <textarea id="mensaje" name="mensaje" rows="3" class="form-control-c5" 
                              placeholder="Cuéntanos tu historia con la música de Tini..."><?= htmlspecialchars($p_mensaje, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <button type="submit" class="btn-c5">Enviar Inscripción (POST) 💜</button>
            </form>
        </div>

        <!-- 2. FORMULARIO GET: BUSCADOR DE CONTENIDO -->
        <div class="card-box">
            <h2>🔍 Formulario GET: Buscador de Música & Merch</h2>
            <p style="font-size: 12.5px; color: #cbd5e1; margin-bottom: 15px;">
                Permite búsquedas compartibles e indexables transmitiendo parámetros visibles en la URL.
            </p>

            <form action="index.php" method="GET">
                <div class="form-group">
                    <label for="buscar">Buscar por nombre o palabra clave:</label>
                    <input type="text" id="buscar" name="buscar" class="form-control-c5" 
                           value="<?= htmlspecialchars($g_busqueda, ENT_QUOTES, 'UTF-8'); ?>" 
                           placeholder="Ej: Cupido, Remera, Pa, Vinilo...">
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría del Producto/Música:</label>
                    <select id="categoria" name="categoria" class="form-control-c5">
                        <option value="todas" <?= ($g_categoria === 'todas') ? 'selected' : ''; ?>>Todas las categorías</option>
                        <option value="album" <?= ($g_categoria === 'album') ? 'selected' : ''; ?>>Álbumes / Discos</option>
                        <option value="cancion" <?= ($g_categoria === 'cancion') ? 'selected' : ''; ?>>Canciones / Singles</option>
                        <option value="merch" <?= ($g_categoria === 'merch') ? 'selected' : ''; ?>>Merchandising Oficial</option>
                    </select>
                </div>

                <button type="submit" class="btn-c5" style="background: linear-gradient(90deg, #8b5cf6, #3b82f6);">
                    Ejecutar Búsqueda (GET) 🔎
                </button>
            </form>

            <div style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                <?php if ($ha_buscado): ?>
                    <h3 style="font-size: 14px; color: #38bdf8; margin: 0 0 10px 0;">
                        Resultados encontrados: <?= count($resultados_get); ?>
                    </h3>
                    <?php if (empty($resultados_get)): ?>
                        <p style="font-size: 13px; color: #f87171;">
                            No se encontraron elementos para "<?= htmlspecialchars($g_busqueda, ENT_QUOTES, 'UTF-8'); ?>".
                        </p>
                    <?php else: ?>
                        <div style="max-height: 250px; overflow-y: auto;">
                            <?php foreach ($resultados_get as $res): ?>
                                <div style="background: rgba(0,0,0,0.3); padding: 8px 12px; border-radius: 6px; margin-bottom: 8px; border-left: 3px solid #ff69b4; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <span class="badge-cat badge-<?= $res['categoria']; ?>"><?= $res['categoria']; ?></span>
                                        <strong style="margin-left: 6px; font-size: 13px;"><?= htmlspecialchars($res['nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <div style="font-size: 11px; color: #94a3b8;">Año: <?= $res['anio']; ?></div>
                                    </div>
                                    <div style="color: #4ade80; font-weight: bold; font-size: 13px;">
                                        $<?= number_format($res['precio'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="font-size: 12.5px; color: #94a3b8; font-style: italic;">
                        Ingresa un término arriba y presiona buscar para filtrar el catálogo en tiempo real.
                    </p>
                <?php endif; ?>
            </div>

        </div>

    </div>

</div>

</body>
</html>
