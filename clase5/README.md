# Trabajo Práctico Clase 5: Procesamiento de Formularios POST y GET

* **Materia:** Adaptación al ambiente de trabajo &bull; Programación Web
* **Estudiante:** Frias Abigail
* **Fecha de Entrega:** 2026
* **Proyecto:** TINI - Sitio Oficial & Fan Club
* **Ubicación:** `clase5/`

---

## 1. Descripción de los Formularios Implementados

En el marco de la evolución del proyecto web sobre la artista **TINI**, se implementó en `clase5/index.php` el procesamiento dinámico de dos interacciones fundamentales:

### A. Formulario con Método POST (Registro al Fan Club Oficial)
* **Objetivo:** Capturar datos confidenciales y solicitudes de membresía para el Fan Club de Tini Stoessel.
* **Campos:** Nombre y Apellido, Correo Electrónico, Ciudad/Provincia, Era Musical Favorita y Mensaje/Motivación.
* **Justificación técnica de POST:** Los datos enviados no viajan visibles en la URL del navegador, sino encapsulados en el cuerpo (*body*) de la petición HTTP, garantizando privacidad, permitiendo el envío de textos extensos y evitando que queden registrados en el historial o logs de servidores proxy.

### B. Formulario con Método GET (Buscador de Discografía y Merchandising)
* **Objetivo:** Filtrar y consultar en tiempo real el catálogo de música (álbumes, sencillos) y productos de merchandising (remeras, hoodies, vinilos).
* **Campos:** Término de búsqueda textual (`buscar`) y filtro selector de categoría (`categoria`).
* **Justificación técnica de GET:** Los parámetros se transmiten en la cadena de consulta (*query string*) de la URL (ej. `index.php?buscar=Cupido&categoria=album`). Esto permite que los usuarios puedan guardar en marcadores (*bookmarks*) sus búsquedas y compartir enlaces directos a resultados específicos.

---

## 2. Sanitización Estricta y Seguridad (OWASP Top 10 - XSS)

Para impedir vulnerabilidades de **Cross-Site Scripting (XSS)** e inyecciones de código malicioso:

1. **`htmlspecialchars($variable, ENT_QUOTES, 'UTF-8')`:** Se aplica obligatoriamente antes de imprimir cualquier dato proveniente de `$_POST` o `$_GET` en el HTML. Transforma caracteres especiales como `<`, `>`, `&`, `"`, `'` en sus entidades HTML equivalentes (`&lt;`, `&gt;`, etc.), impidiendo la inyección y ejecución involuntaria de scripts JavaScript (`<script>alert(1)</script>`).
2. **`trim()`:** Remueve espacios en blanco accidentales o malintencionados al inicio y final de las cadenas.
3. **`filter_var($email, FILTER_VALIDATE_EMAIL)`:** Comprueba a nivel de protocolo que la dirección de correo posea una estructura sintáctica válida antes de ser aceptada.
4. **Validación de campos obligatorios:** Uso riguroso de `empty()` y conteo de caracteres con `mb_strlen()`.

---

## 3. Retroalimentación en la Interfaz y Persistencia de Estado

* **Mensajes de Validación y Éxito:** Si el usuario no completa un campo requerido o ingresa un email inválido, se despliega una caja de alerta destacada en rojo listando los errores exactos. Al completarse con éxito, se muestra un mensaje de bienvenida personalizado en verde.
* **Persistencia de Datos (*Sticky Forms*):** Cuando ocurre un error de validación, los valores ya ingresados por el usuario se conservan dentro del atributo `value="<?= htmlspecialchars(...); ?>"` de los inputs, evitando la frustración de tener que reescribir todo el formulario desde cero.
