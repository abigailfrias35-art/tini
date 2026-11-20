# Trabajo Práctico N° 6: Normalización de Base de Datos
## Proyecto Integrador: TINI - Plataforma Web Oficial

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Ubicación:** `tp6/`

---

## Parte 1: Normalización Aplicada a la Tienda y Fan Club de TINI

### 1. Entidad / Tabla Principal sin Normalizar (Tabla Universal No Normalizada - 0FN)

En la concepción inicial no normalizada del sistema de comercio electrónico y compras del sitio web de Tini, se acumulaban en una única planilla de datos todos los atributos del cliente, los productos adquiridos, sus variantes y los datos de pago:

| ID_Pedido | Fecha | Cliente_Nombre | Cliente_Email | Ciudad_Envio | Productos_Comprados (Multivalorado) | Categorias | Precios_Unitarios | Total_Pedido |
| :---: | :---: | :--- | :--- | :--- | :--- | :--- | :--- | :---: |
| 101 | 19/11/2026 | Abigail Frías | abigail@correo.com | Salta | Remera Tour 2026 (Talle M, Negro), CD Cupido Deluxe | Merch, Música | \$25000, \$18000 | \$43000 |
| 102 | 19/11/2026 | Lucas Gómez | lucas@correo.com | Salta | Hoodie Cupido (Talle L, Rosa) | Merch | \$42000 | \$42000 |
| 103 | 20/11/2026 | Abigail Frías | abigail@correo.com | Salta | Vinilo Un Mechón de Pelo, Gorra Trucker TINI | Música, Merch | \$24000, \$14000 | \$38000 |

#### Defectos del modelo no normalizado:
1. **Campos multivalorados:** La columna `Productos_Comprados` contiene listas de elementos concatenados con comas, lo que imposibilita realizar búsquedas por producto o controlar el stock con consultas SQL directas.
2. **Redundancia severa de datos:** Los datos del cliente (nombre, email, ciudad) se repiten textualmente cada vez que realiza una compra.
3. **Anomalías de actualización y borrado:** Si Abigail cambia su correo, habría que actualizar múltiples filas. Si se elimina un pedido, se podría perder el registro de un producto o cliente.

---

### 2. Proceso de Normalización Paso a Paso

#### A. Primera Forma Normal (1FN) - Atomicidad de Atributos
* **Regla:** Eliminar los grupos repetitivos y asegurar que todos los campos contengan valores atómicos (indivisibles). Cada celda debe guardar un único dato, y la tabla debe contar con una clave primaria definida.
* **Solución aplicada:** Se descompone la lista de productos de manera que cada producto ordenado ocupe su propia fila, definiendo una clave compuesta `(ID_Pedido, Codigo_Producto)`:

```plaintext
PEDIDOS_1FN (ID_Pedido [PK], Codigo_Producto [PK], Fecha, Cliente_Nombre, Cliente_Email, Ciudad_Envio, 
             Producto_Nombre, Categoria_Nombre, Precio_Unitario, Cantidad, Subtotal)
```
* **Estado:** Cumple 1FN, pues no existen listas en celdas individuales. Sin embargo, persisten anomalías de redundancia.

---

#### B. Segunda Forma Normal (2FN) - Dependencia Funcional Completa
* **Regla:** Cumplir 1FN y garantizar que todos los atributos que no formen parte de la clave primaria dependan de manera **completa** de toda la clave primaria, y no solo de una parte de ella (eliminar dependencias parciales).
* **Análisis de dependencias:**
  * En `PEDIDOS_1FN`, la clave primaria es `(ID_Pedido, Codigo_Producto)`.
  * `Fecha`, `Cliente_Nombre`, `Cliente_Email` dependen únicamente de `ID_Pedido` (dependencia parcial).
  * `Producto_Nombre`, `Categoria_Nombre`, `Precio_Unitario` dependen únicamente de `Codigo_Producto` (dependencia parcial).
  * Únicamente `Cantidad` y `Subtotal` dependen de la combinación completa `(ID_Pedido, Codigo_Producto)`.
* **Solución aplicada:** Se divide la estructura en tres tablas independientes:
  1. `PEDIDOS (ID_Pedido [PK], Fecha, Cliente_Nombre, Cliente_Email, Ciudad_Envio)`
  2. `PRODUCTOS (ID_Producto [PK], Nombre, Categoria_Nombre, Precio)`
  3. `DETALLE_PEDIDOS (ID_Pedido [FK], ID_Producto [FK], Cantidad, Precio_Unitario)`
* **Estado:** Cumple 2FN, habiendo eliminado todas las dependencias funcionales parciales.

---

#### C. Tercera Forma Normal (3FN) - Eliminación de Dependencias Transitivas
* **Regla:** Cumplir 2FN y asegurar que **ningún atributo no-clave dependa funcionalmente de otro atributo no-clave** (es decir, $X \rightarrow Y$ donde $Y$ no es clave). Todos los atributos deben depender única y exclusivamente de la clave primaria (*The key, the whole key, and nothing but the key*).
* **Análisis de dependencias transitivas:**
  1. En la tabla de pedidos, `Cliente_Nombre`, `Cliente_Email` dependen del cliente, no del pedido. Se extrae la entidad `USUARIOS`.
  2. En la tabla de productos, `Categoria_Nombre` depende de una categoría maestra. Se extrae la entidad `CATEGORIAS`.
  3. Se añade la entidad `ROLES` para la gestión de permisos (Administrador vs. Usuario/Cliente Fan Club).
* **Solución aplicada:** Modelo relacional totalmente normalizado en 3FN.

---

### 3. Diagrama / Listado de Tablas Resultante (3FN)

1. **`roles`:**
   * `id` **(PK)**: Identificador único del rol.
   * `nombre`: Nombre del rol (`admin`, `usuario_fanclub`).
2. **`usuarios`:**
   * `id` **(PK)**: Identificador único del usuario.
   * `nombre`: Nombre y apellido.
   * `email`: Correo electrónico único (Index).
   * `password`: Hash seguro de la contraseña.
   * `rol_id` **(FK)** $\rightarrow$ Referencia a `roles(id)`.
   * `created_at`, `updated_at`: Campos de auditoría temporal.
3. **`categorias`:**
   * `id` **(PK)**: Identificador de la categoría.
   * `nombre`: Nombre de categoría (`Merchandising`, `Música / Álbumes`, `Entradas Tour`).
   * `descripcion`: Detalle de la categoría.
4. **`productos`:**
   * `id` **(PK)**: Identificador único del producto o ítem.
   * `categoria_id` **(FK)** $\rightarrow$ Referencia a `categorias(id)`.
   * `titulo`: Nombre del artículo (ej. Remera Tour 2026).
   * `descripcion`: Reseña y especificaciones.
   * `precio`: Precio en moneda local (DECIMAL).
   * `stock`: Cantidad disponible en almacén.
   * `imagen`: Ruta de la imagen del producto.
   * `created_at`, `updated_at`: Timestamps de auditoría.
5. **`pedidos`:**
   * `id` **(PK)**: Identificador único del pedido.
   * `usuario_id` **(FK)** $\rightarrow$ Referencia a `usuarios(id)`.
   * `total`: Importe consolidado del pedido.
   * `estado`: Estado del pedido (`pendiente`, `pagado`, `enviado`, `cancelado`).
   * `created_at`: Fecha y hora de compra.
6. **`detalle_pedidos`:**
   * `id` **(PK)**: Identificador de la línea de detalle.
   * `pedido_id` **(FK)** $\rightarrow$ Referencia a `pedidos(id)` (ON DELETE CASCADE).
   * `producto_id` **(FK)** $\rightarrow$ Referencia a `productos(id)`.
   * `cantidad`: Unidades adquiridas.
   * `precio_unitario`: Precio congelado al momento de la transacción.
