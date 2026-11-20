<?php
/**
 * login.php
 * Módulo de Autenticación de Usuarios con password_verify y Regeneración de Sesión
 * Estudiante: Frias Abigail
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/conexion.php';

$error = '';
$email = '';

// Si ya está logueado, redirigir
if (isset($_SESSION['usuario_id'])) {
    if (($_SESSION['rol'] ?? '') === 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: index.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Por favor ingrese su correo electrónico y contraseña.";
    } else {
        try {
            // Consulta preparada para buscar el usuario y su rol
            $stmt = $pdo->prepare("
                SELECT u.id, u.nombre, u.email, u.password, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email 
                LIMIT 1
            ");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Mitigación de Session Fixation
                session_regenerate_id(true);

                // Almacenamiento en $_SESSION
                $_SESSION['usuario_id']     = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email']  = $usuario['email'];
                $_SESSION['rol']            = $usuario['rol_nombre'];

                // Redirección según rol
                if ($usuario['rol_nombre'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: index.php?login=success');
                }
                exit;
            } else {
                $error = "Credenciales incorrectas. Verifique su correo o contraseña.";
            }
        } catch (\PDOException $e) {
            $error = "Error al conectar con la base de datos: " . $e->getMessage();
        }
    }
}

$titulo_pagina = "Iniciar Sesión - TINI";
$pagina_activa = "login";
require_once __DIR__ . '/includes/header.php';
?>

<main style="max-width: 450px; margin: 50px auto; padding: 0 20px;">
    <div style="background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 8px 24px rgba(0,0,0,0.4);">
        <h1 style="color: var(--accent-pink, #ff69b4); text-align: center; margin-top: 0; font-size: 22px;">
            Iniciar Sesión
        </h1>
        <p style="text-align: center; font-size: 13px; color: #ccc; margin-bottom: 25px;">
            Ingresa a tu cuenta de TINI Oficial
        </p>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'acceso_denegado'): ?>
            <div style="background: rgba(245, 158, 11, 0.2); border-left: 4px solid #f59e0b; padding: 10px; border-radius: 4px; margin-bottom: 15px; color: #fde68a; font-size: 13px;">
                Debes iniciar sesión para acceder al panel de administración.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 10px; border-radius: 4px; margin-bottom: 15px; color: #fecaca; font-size: 13px;">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="email" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display:block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Contraseña:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
            </div>

            <button type="submit" class="btn-ticket" style="width: 100%; border: none; cursor: pointer; padding: 12px; font-size: 14px;">
                Entrar al Sistema 🔐
            </button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 13px; color: #aaa;">
            ¿Aún no eres miembro? <a href="registro.php" style="color: var(--accent-pink, #ff69b4); text-decoration: none;">Regístrate gratis</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
