<?php
/**
 * index.php
 * Página principal modularizada con renderizado Server-Side (SSR)
 * Materia: Adaptación al ambiente de trabajo
 * Estudiante: Frias Abigail
 */

$titulo_pagina = "Inicio - Tour 2026 y Fan Club Oficial";
$pagina_activa = "inicio";

require_once __DIR__ . '/includes/header.php';
?>

    <!-- 1. INICIO (HERO) -->
    <section id="inicio">
        <img src="inicio.webp" alt="Tini en el escenario" class="hero-img">
        <h1 class="hero-title">TINI STOESSEL</h1>
        <p class="hero-subtitle">Pop • Trap • Latin • Internacional</p>
        <br>
        <a href="https://open.spotify.com/artist/7vXDAI8JlvVDMNnIQOqw59" target="_blank" class="btn-ticket">🎧 Escuchar en Spotify</a>
    </section>

    <!-- 2. BIOGRAFÍA -->
    <section id="bio">
        <h2 class="section-title">Biografía</h2>
        <div class="bio-container">
            <div class="bio-text">
                <p><span style="color:var(--accent-pink); font-weight:bold;">Martina Stoessel</span>, conocida artísticamente como TINI, es una cantante, actriz y compositora argentina que ha conquistado el corazón de millones alrededor del mundo.</p>
                <p>Desde su inicio en <span style="color:#c084fc;">Violetta</span> hasta convertirse en una de las artistas latinas más influyentes, TINI ha demostrado una evolución artística excepcional, fusionando pop, trap y ritmos urbanos con su estilo único.</p>
                <p>Con colaboraciones junto a artistas internacionales como Sebastián Yatra, Karol G, y Alejandro Sanz, TINI ha consolidado su carrera en la escena musical global, acumulando millones de reproducciones y premios internacionales.</p>
            </div>
            <div class="bio-img-container">
                <img src="concierto.webp" alt="Show de Tini" class="bio-img">
            </div>
        </div>

        <div class="galeria-contenedor">
            <div class="caja-principal">
                <button id="btn-anterior">◀</button>
                <img id="imagen-ampliada" src="inicio.webp" alt="Tini Ampliada">
                <button id="btn-siguiente">▶</button>
            </div>
            <div class="caja-miniaturas">
                <img class="miniatura" src="inicio.webp" alt="Inicio" onclick="abrirMiniatura(this.src, 0)">
                <img class="miniatura" src="concierto.webp" alt="Concierto" onclick="abrirMiniatura(this.src, 1)">
                <img class="miniatura" src="cd.webp" alt="CD" onclick="abrirMiniatura(this.src, 2)">
            </div>
        </div>
    </section>

    <!-- 3. DISCOGRAFÍA -->
    <section id="musica">
        <h2 class="section-title">Discografía</h2>
        <div class="albums-grid">
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">TINI</div>
                <div class="album-year">2016</div>
            </div>
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">Quiero Volver</div>
                <div class="album-year">2018</div>
            </div>
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">TINI TINI TINI</div>
                <div class="album-year">2020</div>
            </div>
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">Cupido</div>
                <div class="album-year">2023</div>
            </div>
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">Un Mechón de Pelo</div>
                <div class="album-year">2024</div>
            </div>
            <div class="album-card">
                <div class="album-cover">TINI</div>
                <div class="album-title">Singles & Features</div>
                <div class="album-year">2025/2026</div>
            </div>
        </div>
    </section>

    <!-- 4. TOUR DATES -->
    <section id="tour">
        <h2 class="section-title">Tour Dates 2026</h2>
        <div class="tour-item">
            <div class="tour-date">16 MAY 2026</div>
            <div class="tour-location">
                <div class="tour-city">Salta, Argentina</div>
                <div class="tour-venue">Estadio Padre Martearena</div>
            </div>
            <div class="tour-action">
                <a href="https://www.allaccess.com.ar/" target="_blank" class="btn-ticket">Comprar Tickets</a>
            </div>
        </div>

        <div class="tour-item">
            <div class="tour-date">18 AGO 2026</div>
            <div class="tour-location">
                <div class="tour-city">Santiago, Chile</div>
                <div class="tour-venue">Movistar Arena</div>
            </div>
            <div class="tour-action">
                <button class="btn-ticket" onclick="alert('Redirigiendo a compra de entradas...')">Comprar Tickets</button>
            </div>
        </div>

        <div class="tour-item">
            <div class="tour-date">25 AGO 2026</div>
            <div class="tour-location">
                <div class="tour-city">Lima, Perú</div>
                <div class="tour-venue">Arena Perú</div>
            </div>
            <div class="tour-action">
                <button class="btn-ticket" onclick="alert('Redirigiendo a compra de entradas...')">Comprar Tickets</button>
            </div>
        </div>

        <div class="tour-item">
            <div class="tour-date">05 SEP 2026</div>
            <div class="tour-location">
                <div class="tour-city">Ciudad de México, México</div>
                <div class="tour-venue">Foro Sol</div>
            </div>
            <div class="tour-action">
                <button class="btn-ticket" onclick="alert('Redirigiendo a compra de entradas...')">Comprar Tickets</button>
            </div>
        </div>
    </section>

    <!-- 5. TIENDA OFICIAL -->
    <section id="tienda">
        <h2 class="section-title">Tienda Oficial</h2>
        <div class="shop-grid">
            <div class="shop-item">
                <img src="merch.jpg" alt="Merch Tour 2026" class="shop-img">
                <div class="shop-info">
                    <div class="shop-title">Remera Tour 2026 Oficial</div>
                    <p style="color:var(--accent-pink); font-weight:bold;">$25.000 ARS</p>
                    <button class="btn-shop" onclick="alert('Producto agregado al carrito')">Comprar Ahora</button>
                </div>
            </div>
            <div class="shop-item">
                <img src="shoes.webp" alt="Hoodie TINI" class="shop-img">
                <div class="shop-info">
                    <div class="shop-title">Hoodie Oficial TINI Cupido</div>
                    <p style="color:var(--accent-pink); font-weight:bold;">$42.000 ARS</p>
                    <button class="btn-shop" onclick="alert('Producto agregado al carrito')">Comprar Ahora</button>
                </div>
            </div>
            <div class="shop-item">
                <img src="cd.webp" alt="CD de Tini" class="shop-img">
                <div class="shop-info">
                    <div class="shop-title">CD Cupido Edición Deluxe</div>
                    <p style="color:var(--accent-pink); font-weight:bold;">$18.000 ARS</p>
                    <button class="btn-shop" onclick="alert('Producto agregado al carrito')">Comprar Ahora</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FAN CLUB Y REGISTRO -->
    <section id="fanclub">
        <h2 class="section-title">Registro al Fan Club Oficial</h2>
        <p class="fanclub-desc" style="text-align: center;">Únete al club oficial completando tus datos. Podrás acceder a preventas exclusivas y sorteos de Meet & Greet.</p>
        
        <div class="form-card" style="max-width: 600px; margin: 0 auto; background: rgba(255,255,255,0.05); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
            <form action="contacto.php" method="POST">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="usuario">Nombre de Usuario *:</label>
                    <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ej: Tinista2026" required minlength="4" maxlength="20" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="correo">Correo Electrónico *:</label>
                    <input type="email" id="correo" name="correo" class="form-control" placeholder="tu@email.com" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="era">Era Favorita de Tini *:</label>
                    <select id="era" name="era" class="form-control" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;">
                        <option value="">Selecciona tu era...</option>
                        <option value="violetta">Violetta</option>
                        <option value="quierovolver">Quiero Volver</option>
                        <option value="tinitinitini">TINI TINI TINI</option>
                        <option value="cupido">Cupido</option>
                        <option value="mechon">Un Mechón de Pelo</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="mensaje">¿Por qué quieres unirte? *</label>
                    <textarea id="mensaje" name="mensaje" rows="3" class="form-control" placeholder="Escribe tu mensaje..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #444; background: #222; color: #fff;"></textarea>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="submit" class="btn-ticket" style="cursor: pointer; border: none;">Enviar Registro 💜</button>
                    <button type="reset" class="btn-ticket" style="background: #444; cursor: pointer; border: none;">Limpiar</button>
                </div>
            </form>
        </div>
    </section>

    <!-- 7. SECCIONES DE COMUNIDAD Y ACORDEONES -->
    <section id="comunidad" style="margin-top: 40px;">
        <h2 class="section-title">Comunidad & Novedades</h2>
        <div style="max-width: 800px; margin: 0 auto;">
            <details class="acordeon">
                <summary>Blog Oficial: El fenómeno internacional de Cupido y Tour 2026 👇</summary>
                <div class="contenido-oculto" style="padding: 15px; background: rgba(255,255,255,0.03);">
                    <h3>Hitos de la Gira</h3>
                    <p>Con múltiples sold-outs en Latinoamérica y Europa, TINI continúa batiendo récords de streaming.</p>
                </div>
            </details>
            <details class="acordeon">
                <summary>Preguntas Frecuentes (FAQ) 👇</summary>
                <div class="contenido-oculto" style="padding: 15px; background: rgba(255,255,255,0.03);">
                    <h3>¿Cómo acceder a las preventas del tour?</h3>
                    <p>Los códigos de preventa se envían por correo electrónico a los suscriptores registrados en el Fan Club.</p>
                </div>
            </details>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
