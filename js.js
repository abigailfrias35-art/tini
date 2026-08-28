// 1. Saludo al cargar la página (¡Esto hará saltar el alert de inmediato!)
window.onload = function() {
    alert("¡Bienvenido al Fan Club Oficial de Tini Stoessel!");
};

document.addEventListener('DOMContentLoaded', () => {

    // Manejo de botones de tickets (Tour)
    const ticketButtons = document.querySelectorAll('.btn-ticket');
    ticketButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Redirigiendo a la plataforma oficial de compra de entradas...');
        });
    });

    // Manejo de botones de compra (Tienda)
    const shopButtons = document.querySelectorAll('.btn-shop');
    shopButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const productTitle = e.target.parentElement.querySelector('.shop-title').textContent;
            alert(`¡Has añadido "${productTitle}" al carrito!`);
        });
    });

    // Manejo del envío del formulario del Fan Club y validación de correo
    const fanClubForm = document.getElementById('fanClubForm');
    if (fanClubForm) {
        fanClubForm.addEventListener('submit', (e) => {
            let email = document.getElementById('correo').value;
            
            // Validar formato de correo electrónico
            if (!email.includes("@")) {
                alert("Por favor, introduce un correo electrónico válido.");
                e.preventDefault(); // Evita que se envíe
                return;
            }

            e.preventDefault(); // Evita que la página se recargue para mostrar el mensaje
            const nombre = document.getElementById('nombre').value;
            alert(`¡Gracias por unirte al Fan Club oficial, ${nombre}! 💜`);
            fanClubForm.reset(); // Limpia los campos
        });
    }

    // --- PUNTO 4: MENÚ INTERACTIVO / ACORDEÓN ---
    let botonesAcordeon = document.querySelectorAll(".acordeon-btn");
    botonesAcordeon.forEach(function(boton) {
        boton.addEventListener("click", function() {
            let contenido = this.nextElementSibling;
            contenido.classList.toggle("activo");
        });
    });

});

// 3. Fecha y hora en tiempo real
// 3. Fecha y hora en tiempo real (Autocreada)
function mostrarHora() {
    let fecha = new Date();
    let reloj = document.getElementById("reloj");
    
    // Si olvidaste poner el div en el HTML, lo creamos automáticamente arriba de todo
    if (!reloj) {
        reloj = document.createElement("div");
        reloj.id = "reloj";
        reloj.style.cssText = "font-size: 18px; font-weight: bold; color: #ff007f; text-align: center; padding: 10px;";
        document.body.insertBefore(reloj, document.body.firstChild);
    }
    
    reloj.innerHTML = "🕒 " + fecha.toLocaleTimeString();
}
setInterval(mostrarHora, 1000);

function cambiarImagen(direccion) {
    indiceActual += direccion;
    if (indiceActual >= imagenes.length) indiceActual = 0;
    if (indiceActual < 0) indiceActual = imagenes.length - 1;
    
    let imgCarrusel = document.getElementById("imagen-carrusel");
    if (imgCarrusel) {
        imgCarrusel.src = imagenes[indiceActual];
    }
}

// 6. Cambiar el tema de la página (Modo Oscuro / Claro)
function toggleDarkMode() {
    document.body.classList.toggle("modo-claro");
}

// 7. Formulario con resumen
function mostrarResumen(event) {
    event.preventDefault(); 
    let usuario = document.getElementById("usuario").value;
    let correo = document.getElementById("correo").value;
    
    let confirmar = confirm(`¿Son correctos tus datos?\nUsuario: ${usuario}\nCorreo: ${correo}`);
    if (confirmar) {
        alert("¡Formulario enviado con éxito!");
    }
}