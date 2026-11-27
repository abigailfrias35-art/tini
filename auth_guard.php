<?php
/**
 * auth_guard.php
 * Guardián de Rutas y Control de Acceso por Roles mediante $_SESSION
 * Estudiante: Frias Abigail
 * Materia: Adaptación al ambiente de trabajo & Programación Web
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función para proteger páginas que requieren autenticación
 * @param string|null $rol_requerido 'admin' o 'usuario' (o null para cualquier usuario autenticado)
 */
function verificar_acceso($rol_requerido = null) {
    // Determinar si estamos dentro de un subdirectorio como /admin o en la raíz
    $script_dir = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $prefix = ($script_dir === 'admin' || $script_dir === 'clase5' || $script_dir === 'usuario') ? '../' : '';

    // 1. Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ' . $prefix . 'login.php?error=acceso_denegado');
        exit;
    }

    // 2. Control estricto de acceso por rol si se especificó uno
    if ($rol_requerido !== null) {
        $rol_actual = $_SESSION['rol'] ?? 'usuario';
        
        // Si se pide 'admin' y el usuario no lo es, redirigir a su panel de usuario
        if ($rol_requerido === 'admin' && $rol_actual !== 'admin') {
            header('Location: ' . $prefix . 'panel_usuario.php?error=rol_no_autorizado');
            exit;
        }

        // Si se pide un rol específico y no coincide
        if ($rol_requerido !== 'admin' && $rol_actual !== $rol_requerido) {
            header('Location: ' . $prefix . 'index.php?error=rol_no_autorizado');
            exit;
        }
    }
}

/**
 * Comprueba si el usuario en sesión actual es Administrador
 * @return bool
 */
function es_admin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

/**
 * Comprueba si el usuario tiene una sesión activa
 * @return bool
 */
function es_usuario_autenticado() {
    return isset($_SESSION['usuario_id']);
}
