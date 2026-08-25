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

    // Manejo del envío del formulario del Fan Club
    const fanClubForm = document.getElementById('fanClubForm');
    if (fanClubForm) {
        fanClubForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Evita que la página se recargue
            const nombre = document.getElementById('nombre').value;
            alert(`¡Gracias por unirte al Fan Club oficial, ${nombre}! 💜`);
            fanClubForm.reset(); // Limpia los campos
        });
    }

});