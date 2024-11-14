function header_mesa() {
    const cardHeader = document.getElementById('myCardHeader');
    cardHeader.classList.add('border-1', 'bg-indigo-lt');
    const img = document.getElementById('img_ventas_directas');
    img.style.display = 'block';

    const mesa = document.getElementById('mesa_pedido');
    mesa.style.display = 'none';

    const pedido = document.getElementById('pedido_mesa');
    const mesero = document.getElementById('nombre_mesero');
    if (pedido) {
        pedido.textContent = 'VENTAS DE MOSTRADOR';
    }

    if (mesero) {
        mesero.style.display = 'none';

    }

}