<?php
/**
 * login.php
 * Módulo de Autenticación de Usuarios con password_verify y Redirección Inteligente por Roles
 * Estudiante: Frias Abigail
 * Materia: Adaptación al ambiente de trabajo
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/conexion.php';

$error = '';
$email = '';

// Si ya tiene sesión activa, redirigir a su panel correspondiente
if (isset($_SESSION['usuario_id'])) {
    if (($_SESSION['rol'] ?? '') === 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: panel_usuario.php');
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
            // Consulta preparada para buscar el usuario y su rol asociado
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

                // Almacenamiento seguro en $_SESSION
                $_SESSION['usuario_id']     = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email']  = $usuario['email'];
                $_SESSION['rol']            = $usuario['rol_nombre'];

                // Redirección inteligente según el rol obtenido de la base de datos
                if ($usuario['rol_nombre'] === 'admin') {
                    header('Location: admin/index.php');
                } else {
                    header('Location: panel_usuario.php');
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

<main style="max-width: 480px; margin: 45px auto; padding: 0 20px;">
    <div style="background: rgba(255,255,255,0.05); padding: 35px 30px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="font-size: 38px; margin-bottom: 5px;">💜</div>
            <h1 style="color: var(--accent-pink, #ff69b4); margin: 0; font-size: 24px;">Iniciar Sesión</h1>
            <p style="font-size: 13.5px; color: #cbd5e1; margin-top: 6px;">
                Ingresa para acceder a tu Panel de Fan Club o Administración
            </p>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'acceso_denegado'): ?>
            <div style="background: rgba(245, 158, 11, 0.2); border-left: 4px solid #f59e0b; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #fde68a; font-size: 13px;">
                ⚠️ Debes iniciar sesión para acceder a este panel.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito'): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #a7f3d0; font-size: 13px;">
                ✓ ¡Cuenta creada con éxito! Ya puedes iniciar sesión.
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px; color: #fecaca; font-size: 13px;">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 16px;">
                <label for="email" style="display:block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px;">Correo Electrónico:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="tu@email.com" required style="width: 100%; padding: 11px; border-radius: 6px; border: 1px solid #444; background: #1a0f24; color: #fff; font-size: 14px;">
            </div>

            <div style="margin-bottom: 22px;">
                <label for="password" style="display:block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px;">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required style="width: 100%; padding: 11px; border-radius: 6px; border: 1px solid #444; background: #1a0f24; color: #fff; font-size: 14px;">
            </div>

            <button type="submit" class="btn-ticket" style="width: 100%; border: none; cursor: pointer; padding: 13px; font-size: 15px; font-weight: bold;">
                Ingresar al Sistema 🔐
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); font-size: 13px; color: #aaa; text-align: center;">
            <p style="margin: 0 0 10px 0;">¿Aún no eres miembro? <a href="registro.php" style="color: var(--accent-pink, #ff69b4); text-decoration: none; font-weight: bold;">Regístrate gratis aquí</a></p>
            <div style="background: rgba(0,0,0,0.3); padding: 10px; border-radius: 6px; font-size: 12px; color: #cbd5e1; text-align: left; margin-top: 15px;">
                <strong>💡 Cuentas de demostración disponibles:</strong><br>
                • <strong>Admin:</strong> <code>abigailfrias35@gmail.com</code> | Clave: <code>Admin1234!</code><br>
                • <strong>Usuario:</strong> <code>marta@educacion.gob.ar</code> | Clave: <code>Admin1234!</code>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
