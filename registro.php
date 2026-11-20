<?php
/**
 * registro.php
 * Módulo de Registro de Usuarios con Hashing BCRYPT y Consultas Preparadas PDO
 * Estudiante: Frias Abigail
 */

$titulo_pagina = "Registro de Usuario - Fan Club Oficial TINI";
$pagina_activa = "fanclub";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/conexion.php';

$errores = [];
$exito = false;

$nombre = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validaciones estrictas
    if (empty($nombre)) {
        $errores[] = "El nombre y apellido son obligatorios.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Debe ingresar un correo electrónico válido.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if ($password !== $password_confirm) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Verificar si el correo ya existe en la base de datos
    if (empty($errores)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $errores[] = "El correo electrónico ya se encuentra registrado. Intenta iniciar sesión.";
            } else {
                // Encriptación con BCRYPT según estándares OWASP
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                
                // Inserción segura con Consulta Preparada
                $insert = $pdo->prepare("
                    INSERT INTO usuarios (nombre, email, password, rol_id) 
                    VALUES (:nombre, :email, :password, 2)
                ");
                $insert->execute([
                    ':nombre'   => $nombre,
                    ':email'    => $email,
                    ':password' => $password_hash,
                ]);

                $exito = true;
                $nombre = $email = '';
            }
        } catch (\PDOException $e) {
            $errores[] = "Error al registrar en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<main style="max-width: 500px; margin: 40px auto; padding: 0 20px;">
    <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 8px 24px rgba(0,0,0,0.4);">
        <h1 style="color: var(--accent-pink, #ff69b4); text-align: center; margin-top: 0; font-size: 22px;">
            Crear Cuenta en TINI Fan Club
        </h1>
        <p style="text-align: center; font-size: 13px; color: #ccc; margin-bottom: 25px;">
            Accede a la compra de entradas y promociones en merchandising oficial.
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

        <?php if ($exito): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #a7f3d0; font-size: 13px;">
                ¡Tu cuenta fue creada con éxito! Ya puedes <a href="login.php" style="color: #fff; font-weight: bold; text-decoration: underline;">Iniciar Sesión aquí</a>.
            </div>
        <?php endif; ?>

        <form action="registro.php" method="POST" novalidate>
            <div style="margin-bottom: 15px;">
                <label for="nombre" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Nombre Completo *:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="email" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Correo Electrónico *:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="password" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Contraseña * (Mínimo 6 caracteres):</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password_confirm" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Confirmar Contraseña *:</label>
                <input type="password" id="password_confirm" name="password_confirm" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <button type="submit" class="btn-ticket" style="width: 100%; border: none; cursor: pointer; padding: 12px; font-size: 14px;">
                Registrarme en el Fan Club 💜
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 13px; color: #aaa;">
            ¿Ya tienes una cuenta? <a href="login.php" style="color: var(--accent-pink, #ff69b4); text-decoration: none;">Inicia Sesión</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
