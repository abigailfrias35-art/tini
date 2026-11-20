<?php
/**
 * admin/crear.php
 * Inserción segura de datos con Consultas Preparadas PDO
 * Estudiante: Frias Abigail
 */

require_once __DIR__ . '/../auth_guard.php';
verificar_acceso('admin');

require_once __DIR__ . '/../config/conexion.php';

$errores = [];
$titulo = '';
$categoria_id = 1;
$descripcion = '';
$precio = '';
$stock = '';
$imagen = 'merch.jpg';

// Cargar categorías disponibles
$stmt_cat = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
$categorias = $stmt_cat->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo       = trim($_POST['titulo'] ?? '');
    $categoria_id = (int)($_POST['categoria_id'] ?? 1);
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $precio       = floatval($_POST['precio'] ?? 0);
    $stock        = intval($_POST['stock'] ?? 0);
    $imagen       = trim($_POST['imagen'] ?? 'merch.jpg');

    if (empty($titulo)) {
        $errores[] = "El título del producto es obligatorio.";
    }

    if (empty($descripcion)) {
        $errores[] = "La descripción no puede estar vacía.";
    }

    if ($precio <= 0) {
        $errores[] = "El precio debe ser un número positivo mayor a 0.";
    }

    if ($stock < 0) {
        $errores[] = "El stock no puede ser negativo.";
    }

    if (empty($errores)) {
        try {
            // Consulta Preparada obligatoria contra Inyección SQL
            $sql = "INSERT INTO productos (categoria_id, titulo, descripcion, precio, stock, imagen) 
                    VALUES (:categoria_id, :titulo, :descripcion, :precio, :stock, :imagen)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':categoria_id' => $categoria_id,
                ':titulo'       => $titulo,
                ':descripcion'  => $descripcion,
                ':precio'       => $precio,
                ':stock'        => $stock,
                ':imagen'       => $imagen
            ]);

            header('Location: index.php?status=created');
            exit;
        } catch (\PDOException $e) {
            $errores[] = "Error al insertar en la base de datos: " . $e->getMessage();
        }
    }
}

$titulo_pagina = "Nuevo Producto - CRUD TINI";
$pagina_activa = "admin";
require_once __DIR__ . '/../includes/header.php';
?>

<main style="max-width: 650px; margin: 30px auto; padding: 0 20px;">
    <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
        <h1 style="color: var(--accent-pink, #ff69b4); margin-top: 0; font-size: 22px;">
            + Agregar Nuevo Producto / Merch TINI
        </h1>
        <p style="font-size: 13px; color: #ccc; margin-bottom: 20px;">
            Complete los campos. Se insertará mediante sentencias preparadas PDO.
        </p>

        <?php if (!empty($errores)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                <ul style="margin: 0 0 0 16px; padding: 0; color: #fecaca; font-size: 13px;">
                    <?php foreach ($errores as $err): ?>
                        <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            <div style="margin-bottom: 14px;">
                <label for="titulo" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Título del Artículo *:</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ej: Remera Tour Cupido 2026" required style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <div style="margin-bottom: 14px;">
                <label for="categoria_id" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Categoría *:</label>
                <select id="categoria_id" name="categoria_id" style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id']; ?>" <?= ($categoria_id == $cat['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom: 14px;">
                <label for="descripcion" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Descripción *:</label>
                <textarea id="descripcion" name="descripcion" rows="3" required style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;"><?= htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 14px;">
                <div>
                    <label for="precio" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Precio ($ ARS) *:</label>
                    <input type="number" step="0.01" id="precio" name="precio" value="<?= htmlspecialchars($precio, ENT_QUOTES, 'UTF-8'); ?>" placeholder="25000.00" required style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
                <div>
                    <label for="stock" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Stock Inicial *:</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($stock, ENT_QUOTES, 'UTF-8'); ?>" placeholder="50" required style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="imagen" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Nombre de Archivo de Imagen:</label>
                <select id="imagen" name="imagen" style="width: 100%; padding: 9px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                    <option value="merch.jpg">merch.jpg (Remera Tour)</option>
                    <option value="shoes.webp">shoes.webp (Hoodie)</option>
                    <option value="cd.webp">cd.webp (Disco/Vinilo)</option>
                    <option value="concierto.webp">concierto.webp (Entrada/Show)</option>
                </select>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-ticket" style="background: #10b981; border: none; cursor: pointer; flex: 1;">Guardar en Base de Datos</button>
                <a href="index.php" class="btn-ticket" style="background: #6b7280; text-decoration: none; text-align: center; line-height: 24px;">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
