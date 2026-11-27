# TINI - Plataforma Web Oficial, Fan Club y Tienda de Merchandising

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Repositorio GitHub:** [https://github.com/abigailfrias35-art/tini](https://github.com/abigailfrias35-art/tini)
* **Demo en Vivo (GitHub Pages):** [https://abigailfrias35-art.github.io/tini/](https://abigailfrias35-art.github.io/tini/)
* **Despliegue en Vercel (1-Click Deploy):** [![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2Fabigailfrias35-art%2Ftini)

---

## 1. Descripción del Proyecto

Plataforma web integral desarrollada en torno a la artista internacional **TINI (Martina Stoessel)**. El proyecto cuenta con:
1. **Frontend Interactivo y Responsive:** Versión en vivo en producción (accesible vía GitHub Pages y Vercel) con carrusel de fotos, selector de tema oscuro/claro, reloj dinámico, sección biográfica, catálogo de discografía, venta de tickets para el Tour 2026 y tienda de merchandising.
2. **Backend PHP 8.x con Modularización (SSI/SSR):** Vistas dinámicas con inyección de títulos y plantillas reutilizables (`header.php`, `nav.php`, `footer.php`).
3. **Formularios Seguros (POST & GET):** Sanitización estricta contra ataques XSS con `htmlspecialchars()`, validaciones tipadas y filtros sticky.
4. **Base de Datos Normalizada (MySQL 3FN):** Esquema relacional `tini_db` con 6 tablas, integridad referencial con claves foráneas e índices de rendimiento.
5. **Persistencia PDO & Autenticación:** Consultas preparadas anti SQL Injection, hashing seguro con BCRYPT y control de sesiones con roles (`admin` y `usuario`).

---

## 2. Base de Datos Oficial (`tini_db.sql`)

El repositorio incluye el archivo consolidado y documentado listo para importar en phpMyAdmin:
* **Archivo SQL:** [`tini_db.sql`](tini_db.sql)
* **Motor:** InnoDB (MySQL 8.x / MariaDB)
* **Juego de Caracteres:** `utf8mb4_unicode_ci`

### Tablas Incluidas:
* `roles`: Niveles de acceso (Admin y Usuario Fan Club).
* `usuarios`: Credenciales con BCRYPT y relación con roles.
* `categorias`: Familias de productos (Merchandising, Discografía, Conciertos).
* `productos`: Catálogo con stock, precios e imágenes.
* `pedidos`: Órdenes de compra generadas.
* `detalle_pedidos`: Detalle de artículos comprados (relación N:M).

### Datos de Prueba Pre-cargados:
* **Administrador:** `abigailfrias35@gmail.com` | Contraseña: `Admin1234!`
* **Usuario Fan Club:** `marta@educacion.gob.ar` | Contraseña: `Admin1234!`

---

## 3. Despliegue en la Nube (Vercel & GitHub Pages)

### Opción A: Despliegue en Vercel
El repositorio incluye el archivo de configuración `vercel.json`:
1. Ingresar a [https://vercel.com/new](https://vercel.com/new).
2. Conectar tu cuenta de GitHub e importar el repositorio `abigailfrias35-art/tini`.
3. Hacer clic en **Deploy**. En menos de 30 segundos obtendrás tu URL pública de producción (por ejemplo: `https://tini-abigail.vercel.app`).
4. O mediante el botón de un clic: [Desplegar en Vercel](https://vercel.com/new/clone?repository-url=https%3A%2F%2Fgithub.com%2Fabigailfrias35-art%2Ftini).

### Opción B: Demo en Vivo en GitHub Pages
La página estática se encuentra publicada y activa en:
👉 **[https://abigailfrias35-art.github.io/tini/](https://abigailfrias35-art.github.io/tini/)**

---

## 4. Instrucciones para Ejecución Local (PHP + MySQL con XAMPP / Laragon)

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/abigailfrias35-art/tini.git
   ```
2. **Copiar la carpeta al directorio del servidor web local:**
   * **XAMPP:** `C:\xampp\htdocs\tini`
   * **Laragon:** `C:\laragon\www\tini`
3. **Importar la Base de Datos en phpMyAdmin:**
   * Abrir `http://localhost/phpmyadmin` en el navegador.
   * Ir a la pestaña **Importar**.
   * Seleccionar el archivo `tini_db.sql` y hacer clic en **Importar**.
4. **Configurar el archivo de entorno:**
   * Copiar `.env.example` a `.env` y verificar credenciales de MySQL (por defecto `DB_USER=root`, `DB_PASS=""`).
5. **Navegar en el navegador local:**
   * Sitio público: `http://localhost/tini/index.php`
   * Módulo de Formularios (Clase 5): `http://localhost/tini/clase5/index.php`
   * Panel de Administración CRUD: `http://localhost/tini/login.php`
