<?php
/**
 * auth_guard.php
 * Guardián de Rutas y Control de Acceso por Roles mediante $_SESSION
 * Estudiante: Frias Abigail
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Función para proteger páginas que requieren autenticación
 * @param string|null $rol_requerido 'admin' o 'usuario' (opcional)
 */
function verificar_acceso($rol_requerido = null) {
    // 1. Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: ../login.php?error=acceso_denegado');
        exit;
    }

    // 2. Control de acceso por roles si se especificó uno
    if ($rol_requerido !== null && (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rol_requerido)) {
        header('Location: ../index.php?error=rol_no_autorizado');
        exit;
    }
}
