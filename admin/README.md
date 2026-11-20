# Trabajo Práctico: Persistencia Segura (PDO MySQL), CRUD y Autenticación con Sesiones

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Proyecto Integrador:** TINI - Plataforma Web Oficial

---

## 1. Conexión Segura con PDO (`config/conexion.php`)

Se implementó la conexión utilizando **PHP Data Objects (PDO)** mediante manejo de excepciones `try-catch (\PDOException $e)` y directivas de seguridad OWASP:
* `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`: Fuerza el lanzamiento de excepciones ante errores SQL.
* `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`: Asegura arreglos asociativos sin sobrecarga de memoria.
* `PDO::ATTR_EMULATE_PREPARES => false`: Desactiva la emulación para garantizar que el motor MySQL compile y ejecute consultas preparadas nativas, blindando la aplicación contra **SQL Injection**.

---

## 2. Módulo CRUD de Productos y Merchandising (`admin/`)

Se desarrolló un CRUD completo adaptado a los productos oficiales de Tini Stoessel:
* **Create (`admin/crear.php`):** Formulario de alta con validación de campos obligatorios y sentencia preparada `INSERT INTO productos (...) VALUES (:categoria_id, :titulo, :descripcion, :precio, :stock, :imagen)`.
* **Read (`admin/index.php`):** Listado tabular con información de producto, categoría, precio formateado y stock disponible.
* **Update (`admin/editar.php`):** Formulario de modificación precargado y actualización mediante `UPDATE productos SET ... WHERE id = :id`.
* **Delete (`admin/eliminar.php`):** Baja física de registros mediante `DELETE FROM productos WHERE id = :id`.
* **Sanitización XSS:** Aplicación obligatoria de `htmlspecialchars()` en todas las variables impresas en el navegador.

---

## 3. Módulo de Autenticación, Hashing BCRYPT y Sesiones

* **Registro (`registro.php`):** Valida formato de correo y duplicados en base de datos. Encripta contraseñas utilizando `password_hash($password, PASSWORD_BCRYPT)`.
* **Login (`login.php`):** Valida credenciales con `password_verify($password, $hash)`. Al autenticar exitosamente:
  1. Invoca `session_start()`.
  2. Ejecuta `session_regenerate_id(true)` para mitigar ataques de **Session Fixation**.
  3. Almacena en la superglobal `$_SESSION`: `usuario_id`, `usuario_nombre` y `rol`.
* **Control de Acceso (`auth_guard.php`):** Guardián de rutas reutilizable que verifica `isset($_SESSION['usuario_id'])` y restringe el acceso a `admin/` exclusivamente al rol `admin`.
* **Logout (`logout.php`):** Limpieza de `$_SESSION`, invalidación de cookies de sesión del navegador y `session_destroy()`.
