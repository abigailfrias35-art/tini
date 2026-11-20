# TINI - Plataforma Web Oficial y Tienda de Merchandising

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Repositorio GitHub:** [https://github.com/abigailfrias35-art/tini](https://github.com/abigailfrias35-art/tini)

---

## 1. Descripción del Proyecto

Plataforma web dinámica desarrollada en torno a la figura artística de **TINI (Martina Stoessel)**. El proyecto evolucionó de una maqueta estática inicial (HTML5/CSS3) a una aplicación web completa y modularizada con backend en **PHP 8.x** y base de datos relacional **MySQL**.

El sitio integra:
* Página principal con biografía, discografía completa, fechas de conciertos del Tour 2026 y tienda de merchandising oficial.
* Arquitectura modular con **Server Side Includes (SSI)** y renderizado del lado del servidor (**SSR**).
* Procesamiento seguro de formularios con métodos **POST** y **GET** con protección contra **Cross-Site Scripting (XSS)**.
* Modelo de datos relacional normalizado hasta **Tercera Forma Normal (3FN)**.
* Sistema de autenticación con **hashing BCRYPT**, sesiones protegidas y control de acceso por roles (`admin` y `usuario`).
* Panel de administración con módulo **CRUD completo** (Altas, Bajas, Modificaciones y Consultas) para productos y merchandising.

---

## 2. Tecnologías Utilizadas

* **Frontend:** HTML5 semántico, CSS3 personalizado (tema oscuro/claro y paleta oficial violeta/rosa de Tini), JavaScript interactivo (carrusel de imágenes y validaciones).
* **Backend:** PHP 8.x (Programación estructurada y modular con PDO).
* **Base de Datos:** MySQL 8.x / MariaDB (Motor transaccional InnoDB con claves foráneas e índices).
* **Seguridad:** OWASP Top 10 (Sentencias Preparadas anti SQL Injection, sanitización XSS con `htmlspecialchars()`, protección contra Session Fixation con `session_regenerate_id(true)` y contraseñas con BCRYPT).
* **Entorno Servidor Local:** XAMPP / Laragon (Apache + MySQL).
* **Control de Versiones:** Git y GitHub (Flujo de ramas `feature/migracion-php-ssi` integradas a `main`).

---

## 3. Estructura del Repositorio

```plaintext
tini/
├── .env.example              # Plantilla de configuración de entorno
├── .gitignore                # Exclusión de .env y archivos temporales
├── auth_guard.php            # Guardián de rutas y verificación de roles ($_SESSION)
├── contacto.php              # Centro de contacto y buscador interactivo (GET / POST)
├── index.php                 # Página de inicio modularizada (SSR)
├── proyecto.php              # Información técnica del proyecto
├── login.php                 # Autenticación segura y regeneración de sesión
├── registro.php              # Registro con hash BCRYPT en base de datos
├── logout.php                # Destrucción segura de sesión y cookies
│
├── config/
│   ├── env.php               # Carga de variables de entorno globales
│   └── conexion.php          # Instancia segura PDO con try-catch
│
├── includes/
│   ├── header.php            # <head>, metadatos y $titulo_pagina dinámico
│   ├── nav.php               # Menú de navegación con resaltado activo
│   └── footer.php            # Pie de página y cierre con scripts
│
├── admin/                    # Módulo CRUD con acceso restringido
│   ├── index.php             # Listado de productos de TINI (Read)
│   ├── crear.php             # Formulario de alta con Prepared Statements (Create)
│   ├── editar.php            # Edición de productos (Update)
│   ├── eliminar.php          # Baja física de registros (Delete)
│   └── README.md             # Documentación del módulo CRUD
│
├── clase5/                   # TP Clase 5: Formularios POST y GET
│   ├── index.php             # Formularios con sanitización y persistencia
│   └── README.md             # Documentación técnica de la Clase 5
│
├── tp6/                      # TP 6: Base de Datos y Normalización
│   ├── NORMALIZACION.md      # Desarrollo teórico de 1FN, 2FN y 3FN
│   ├── schema.sql            # Script DDL de creación de tablas e índices
│   ├── consultas.sql         # Script DML con consultas de prueba CRUD
│   └── README.md             # Documentación del esquema
│
└── css/
    └── styles.css            # Hoja de estilos del proyecto
```

---

## 4. Instrucciones de Instalación y Ejecución Local

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/abigailfrias35-art/tini.git
   ```
2. **Copiar el proyecto al servidor local:**
   * En **XAMPP**: mover la carpeta `tini` a `C:\xampp\htdocs\tini`.
   * En **Laragon**: mover la carpeta `tini` a `C:\laragon\www\tini`.
3. **Configurar el archivo de entorno:**
   * Duplicar `.env.example` y renombrarlo como `.env`.
   * Ajustar credenciales de base de datos si fuera necesario.
4. **Importar la Base de Datos:**
   * Abrir **phpMyAdmin** (`http://localhost/phpmyadmin`).
   * Importar y ejecutar el archivo [`tp6/schema.sql`](tp6/schema.sql).
   * Opcionalmente ejecutar [`tp6/consultas.sql`](tp6/consultas.sql) para cargar datos de prueba.
5. **Acceder a la aplicación:**
   * Sitio público: `http://localhost/tini/index.php`
   * Módulo Clase 5 (Formularios): `http://localhost/tini/clase5/index.php`
   * Panel Administrador: `http://localhost/tini/login.php` (Usuario: `abigailfrias35@gmail.com` | Clave: `Admin1234!`)
