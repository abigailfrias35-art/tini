-- ====================================================================
-- Script DML de Pruebas CRUD: consultas.sql
-- Proyecto: TINI - Plataforma Web Oficial y Tienda de Merchandising
-- Materia: Adaptación al ambiente de trabajo & Programación Web
-- Estudiante: Frias Abigail (2026)
-- Base de datos: tini_db
-- ====================================================================

USE tini_db;

-- ====================================================================
-- 1. CREATE (Inserciones de Datos Iniciales)
-- ====================================================================

-- 1.1 Inserciones en Tablas Maestras (roles y categorias)
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'admin', 'Administrador general con control total del sistema y CRUD'),
(2, 'usuario', 'Miembro registrado del Fan Club de Tini con permisos de compra')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Merchandising Oficial', 'Indumentaria oficial del Tour 2026, remeras, hoodies y gorras'),
(2, 'Discografía & Vinilos', 'Discos compactos, ediciones de lujo y vinilos de colección'),
(3, 'Tickets & Conciertos', 'Entradas oficiales para los shows del Tour Internacional')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 1.2 Inserciones en Tablas Principales con Claves Foráneas (usuarios y productos)
-- Hash de prueba generado con password_hash('Admin1234!', PASSWORD_BCRYPT)
INSERT INTO usuarios (nombre, email, password, rol_id) VALUES
('Abigail Frías', 'abigailfrias35@gmail.com', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 1),
('Marta Docente', 'marta@educacion.gob.ar', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 2),
('Tinista Salta', 'tinista_salta@gmail.com', '$2y$10$wBskvX76k9/t3M.fIUkOse6vA8G7k4YjO5a6z0Q5mZJgB9ZfP0Wvy', 2);

INSERT INTO productos (categoria_id, titulo, descripcion, precio, stock, imagen) VALUES
(1, 'Remera Oficial Tour 2026', 'Remera de algodón 100% con estampado oficial de la gira en frente y fechas en espalda', 25000.00, 150, 'merch.jpg'),
(1, 'Hoodie Cupido Rosa Oversize', 'Buzo frizado con capucha, bordado frontal de Cupido y logo TINI en manga', 42000.00, 80, 'shoes.webp'),
(2, 'CD Cupido Edición Deluxe', 'Álbum original en formato físico con booklet fotográfico y póster exclusivo', 18000.00, 200, 'cd.webp'),
(2, 'Vinilo Un Mechón de Pelo', 'Edición limitada en vinilo color lavanda con notas manuscritas de Tini', 24000.00, 50, 'cd.webp'),
(3, 'Entrada General Estadio Martearena', 'Acceso a campo general para el show en Salta del 16 de Mayo de 2026', 35000.00, 500, 'concierto.webp');

-- Inserción de un pedido de prueba y su detalle
INSERT INTO pedidos (usuario_id, total, estado) VALUES
(2, 67000.00, 'pagado');

SET @id_pedido_creado = LAST_INSERT_ID();

INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES
(@id_pedido_creado, 1, 1, 25000.00), -- 1 Remera
(@id_pedido_creado, 2, 1, 42000.00); -- 1 Hoodie


-- ====================================================================
-- 2. READ (Consultas SELECT con Filtros, Ordenamiento y Joins)
-- ====================================================================

-- 2.1 Consulta con WHERE y ORDER BY: Listar productos con stock disponible ordenados por precio descendente
SELECT id, titulo, precio, stock 
FROM productos 
WHERE activo = 1 AND stock > 0 
ORDER BY precio DESC;

-- 2.2 Consulta con INNER JOIN: Listar el catálogo completo junto con el nombre de su categoría
SELECT 
    p.id AS producto_id,
    p.titulo AS producto,
    c.nombre AS categoria,
    p.precio,
    p.stock
FROM productos p
INNER JOIN categorias c ON p.categoria_id = c.id
WHERE p.activo = 1
ORDER BY c.nombre ASC, p.precio ASC;

-- 2.3 Consulta avanzada con MULTI-JOIN: Consultar el historial detallado de compras con clientes y productos
SELECT 
    ped.id AS nro_pedido,
    u.nombre AS cliente,
    u.email AS correo_cliente,
    prod.titulo AS articulo,
    dp.cantidad,
    dp.precio_unitario,
    (dp.cantidad * dp.precio_unitario) AS subtotal,
    ped.total AS total_orden,
    ped.estado,
    ped.created_at AS fecha_compra
FROM pedidos ped
INNER JOIN usuarios u ON ped.usuario_id = u.id
INNER JOIN detalle_pedidos dp ON ped.id = dp.pedido_id
INNER JOIN productos prod ON dp.producto_id = prod.id
WHERE ped.id = @id_pedido_creado;


-- ====================================================================
-- 3. UPDATE (Actualización de Registros con Cláusula WHERE)
-- ====================================================================

-- Modificar el precio y aumentar el stock de un producto específico (Remera Tour 2026)
UPDATE productos 
SET precio = 27500.00, stock = 140, updated_at = NOW() 
WHERE id = 1;

-- Actualizar el estado del pedido de prueba
UPDATE pedidos 
SET estado = 'enviado', updated_at = NOW() 
WHERE id = @id_pedido_creado;


-- ====================================================================
-- 4. DELETE (Eliminación de Registros con Cláusula WHERE)
-- ====================================================================

-- Inserción de un registro temporal para validar la baja lógica o física
INSERT INTO productos (categoria_id, titulo, descripcion, precio, stock, activo) 
VALUES (1, 'Producto Descontinuado Prueba', 'Elemento temporal para pruebas de borrado', 1000.00, 0, 0);

SET @id_producto_borrar = LAST_INSERT_ID();

-- Borrado físico con cláusula WHERE estricta
DELETE FROM productos 
WHERE id = @id_producto_borrar;
