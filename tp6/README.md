# Trabajo Práctico N° 6: Normalización y Base de Datos Relacional en MySQL

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Base de Datos:** `tini_db`
* **Ubicación:** `tp6/`

---

## 1. Estructura de Archivos del TP 6

```plaintext
tini/
└── tp6/
    ├── NORMALIZACION.md     # Proceso teórico y desarrollo de 1FN, 2FN y 3FN
    ├── schema.sql           # Script DDL de creación de tablas, relaciones e índices
    ├── consultas.sql        # Script DML con consultas de prueba CRUD completas
    └── README.md            # Documentación general de entrega
```

---

## 2. Resumen de la Normalización (1FN, 2FN, 3FN)

* **0FN (Tabla Universal No Normalizada):** Se identificó un registro de compras donde los productos adquiridos, sus talles y categorías se guardaban concatenados con comas en una única columna multivalorada, provocando redundancia masiva de datos de clientes y anomalías de inserción/borrado.
* **1FN (Primera Forma Normal):** Se eliminaron los grupos repetitivos asegurando la atomicidad de cada columna y definiendo una clave compuesta `(ID_Pedido, Codigo_Producto)`.
* **2FN (Segunda Forma Normal):** Se eliminaron las dependencias funcionales parciales sobre la clave primaria compuesta, separando los pedidos, los productos maestros y la tabla intermedia de detalles.
* **3FN (Tercera Forma Normal):** Se erradicaron las dependencias transitivas extrayendo `usuarios`, `roles` y `categorias` en sus propias tablas relacionales independientes.

---

## 3. Scripts SQL Implementados

### `schema.sql` (DDL)
* Crea la base de datos `tini_db` con codificación `utf8mb4_unicode_ci`.
* Define 6 tablas con motor **InnoDB**: `roles`, `usuarios`, `categorias`, `productos`, `pedidos` y `detalle_pedidos`.
* Implementa claves primarias autoincrementales y claves foráneas con restricciones referenciales (`ON UPDATE CASCADE`, `ON DELETE CASCADE/RESTRICT`).
* Incorpora campos de auditoría estándar:
  ```sql
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ```
* Crea índices explícitos (`CREATE INDEX`) para optimizar búsquedas por email de usuario, categoría de producto y precios.

### `consultas.sql` (DML)
* **CREATE:** Inserta roles (`admin`, `usuario`), categorías de merchandising/música, usuarios con contraseña segura y productos del catálogo oficial.
* **READ:** Consultas con filtrado `WHERE` y ordenamiento `ORDER BY`, e `INNER JOIN` relacionales entre productos, categorías, pedidos y clientes.
* **UPDATE:** Modificación de precios y stock con `WHERE` estricto.
* **DELETE:** Eliminación física de registros de prueba asegurando la integridad del catálogo.
