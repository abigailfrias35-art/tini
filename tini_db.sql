-- ====================================================================
-- BASE DE DATOS OFICIAL: tini_db
-- Proyecto: TINI - Plataforma Web Oficial, Fan Club y Tienda de Merchandising
-- Materia: Adaptación al ambiente de trabajo & Programación Web
-- Alumna: Frias Abigail (abigailfrias35@gmail.com)
-- Año Lectivo: 2026
-- Gestor: MySQL 8.x / MariaDB (Compatible con XAMPP, Laragon y phpMyAdmin)
-- Codificación: UTF-8 Unicode (utf8mb4) / Motor: InnoDB
-- ====================================================================

-- --------------------------------------------------------------------
-- 1. CREACIÓN Y SELECCIÓN DE LA BASE DE DATOS
-- --------------------------------------------------------------------
DROP DATABASE IF EXISTS tini_db;
CREATE DATABASE tini_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE tini_db;

-- --------------------------------------------------------------------
-- 2. TABLA: roles
-- Define los niveles de acceso del sistema (RBAC)
-- --------------------------------------------------------------------
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 3. TABLA: usuarios
-- Almacena credenciales seguras con hashing BCRYPT y rol asignado
-- --------------------------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Almacena hash seguro BCRYPT
    rol_id INT NOT NULL DEFAULT 2,   -- 1 = Administrador, 2 = Fan Club / Cliente
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles 
        FOREIGN KEY (rol_id) REFERENCES roles(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 4. TABLA: categorias
-- Clasificación de los productos de la tienda y entradas
-- --------------------------------------------------------------------
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 5. TABLA: productos
-- Catálogo oficial de Merchandising, Discografía y Entradas del Tour
-- --------------------------------------------------------------------
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) NULL DEFAULT 'merch.jpg',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_categorias 
        FOREIGN KEY (categoria_id) REFERENCES categorias(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 6. TABLA: pedidos
-- Registro de compras efectuadas por los usuarios registrados
-- --------------------------------------------------------------------
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    estado ENUM('pendiente', 'pagado', 'enviado', 'cancelado') NOT NULL DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuarios 
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 7. TABLA: detalle_pedidos
-- Relación de cardinalidad N:M entre Pedidos y Productos
-- --------------------------------------------------------------------
CREATE TABLE detalle_pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_detalle_pedidos 
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id) 
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_productos 
        FOREIGN KEY (producto_id) REFERENCES productos(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- --------------------------------------------------------------------
-- 8. ÍNDICES DE RENDIMIENTO Y OPTIMIZACIÓN
-- --------------------------------------------------------------------
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_productos_categoria_activo ON productos(categoria_id, activo);
CREATE INDEX idx_productos_precio ON productos(precio);
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);

-- ====================================================================
-- 9. CARGA DE DATOS INICIALES (SEED DATA)
-- ====================================================================

-- 9.1 Roles del Sistema
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'admin', 'Administrador general con control total del CRUD y catálogo'),
(2, 'usuario', 'Miembro registrado del Fan Club de Tini con permisos de compra');

-- 9.2 Categorías del Catálogo
INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Merchandising Oficial', 'Indumentaria oficial del Tour 2026, remeras, hoodies y gorras'),
(2, 'Discografía & Vinilos', 'Discos compactos, ediciones de lujo y vinilos de colección'),
(3, 'Tickets & Conciertos', 'Entradas oficiales para los shows del Tour Internacional');

-- 9.3 Usuarios del Sistema (Contraseña por defecto: Admin1234!)
-- Hash BCRYPT generado de forma segura: $2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy
INSERT INTO usuarios (id, nombre, email, password, rol_id) VALUES
(1, 'Abigail Frías', 'abigailfrias35@gmail.com', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 1),
(2, 'Marta Docente', 'marta@educacion.gob.ar', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 2),
(3, 'Tinista Salta', 'tinista_salta@gmail.com', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 2);

-- 9.4 Catálogo de Productos y Merchandising Oficial de TINI
INSERT INTO productos (id, categoria_id, titulo, descripcion, precio, stock, imagen, activo) VALUES
(1, 1, 'Remera Tour 2026 Oficial', 'Remera de algodón 100% con estampado oficial de la gira internacional en frente y fechas en espalda.', 25000.00, 150, 'merch.jpg', 1),
(2, 1, 'Hoodie Oficial TINI Cupido', 'Buzo frizado color rosa lavanda con capucha, bordado frontal de Cupido y logo TINI en manga.', 42000.00, 80, 'shoes.webp', 1),
(3, 2, 'CD Cupido Edición Deluxe', 'Álbum original en formato físico con booklet fotográfico exclusivo y póster oficial autografiado.', 18000.00, 200, 'cd.webp', 1),
(4, 2, 'Vinilo Un Mechón de Pelo', 'Edición limitada en vinilo color lavanda con notas manuscritas de Tini y fotos inéditas de estudio.', 24000.00, 50, 'cd.webp', 1),
(5, 3, 'Entrada General Estadio Martearena', 'Acceso a campo general para el show en Salta del 16 de Mayo de 2026.', 35000.00, 500, 'concierto.webp', 1),
(6, 1, 'Gorra TINI Tour Visera Curva', 'Gorra ajustable bordada con logo TINI oficial de la gira.', 15000.00, 120, 'merch tur.webp', 1);

-- 9.5 Pedidos y Detalles de Compra de Ejemplo
INSERT INTO pedidos (id, usuario_id, total, estado, created_at) VALUES
(1, 2, 67000.00, 'pagado', '2026-11-19 18:30:00');

INSERT INTO detalle_pedidos (id, pedido_id, producto_id, cantidad, precio_unitario) VALUES
(1, 1, 1, 1, 25000.00), -- 1 Remera Tour
(2, 1, 2, 1, 42000.00); -- 1 Hoodie Cupido

-- ====================================================================
-- 10. CONSULTAS DE COMPROBACIÓN RÁPIDA (CRUD TESTS)
-- ====================================================================
-- Consultar el catálogo con su categoría asociada:
-- SELECT p.id, p.titulo, c.nombre AS categoria, p.precio, p.stock 
-- FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE p.activo = 1;

-- Consultar usuarios y roles:
-- SELECT u.id, u.nombre, u.email, r.nombre AS rol 
-- FROM usuarios u INNER JOIN roles r ON u.rol_id = r.id;
