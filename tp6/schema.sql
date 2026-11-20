-- ====================================================================
-- Script DDL de Creación del Esquema en MySQL: schema.sql
-- Proyecto: TINI - Plataforma Web Oficial y Tienda de Merchandising
-- Materia: Adaptación al ambiente de trabajo & Programación Web
-- Estudiante: Frias Abigail (2026)
-- Motor: MySQL 8.x / MariaDB (XAMPP / Laragon)
-- ====================================================================

-- 1. Creación de la Base de Datos
CREATE DATABASE IF NOT EXISTS tini_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE tini_db;

-- 2. Tabla Secundaria / Maestra: Roles de Usuario
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabla de Usuarios (Autenticación y Seguridad)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Almacena hash BCRYPT de 60+ caracteres
    rol_id INT NOT NULL DEFAULT 2,   -- 1 = admin, 2 = usuario
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles 
        FOREIGN KEY (rol_id) REFERENCES roles(id) 
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 4. Tabla Maestra: Categorías de Productos y Música
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Tabla Principal de Negocio: Productos (Merchandising, Álbumes y Entradas)
CREATE TABLE IF NOT EXISTS productos (
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

-- 6. Tabla Principal: Pedidos / Órdenes de Compra
CREATE TABLE IF NOT EXISTS pedidos (
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

-- 7. Tabla de Relación / Detalle: Detalle de Pedidos (Muchos a Muchos: Pedidos <-> Productos)
CREATE TABLE IF NOT EXISTS detalle_pedidos (
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

-- ====================================================================
-- Optimización con Índices Explícitos (Requisito Obligatorio)
-- ====================================================================
-- Índice para acelerar la autenticación de usuarios por correo electrónico
CREATE INDEX idx_usuarios_email ON usuarios(email);

-- Índice para optimizar el filtrado de productos por categoría y estado activo
CREATE INDEX idx_productos_categoria_activo ON productos(categoria_id, activo);

-- Índice para optimizar búsquedas por rango de precios en el catálogo
CREATE INDEX idx_productos_precio ON productos(precio);
