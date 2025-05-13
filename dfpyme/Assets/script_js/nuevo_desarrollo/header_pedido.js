function header_pedido() {
    /*  const cardHeader = document.getElementById('myCardHeader');
     cardHeader.classList.remove('border-1', 'bg-indigo-lt');
     const img = document.getElementById('img_ventas_directas');
     img.style.display = 'none';
 
     const mesa = document.getElementById('mesa_pedido');
     mesa.style.display = 'block';
 
     const pedido = document.getElementById('pedido_mesa');
     const mesero = document.getElementById('nombre_mesero');
 
     if (mesa) {
         mesa.textContent = 'Mesa:';
 
     }
 
     if (pedido) {
         pedido.textContent = 'Pedido:';
     }
 
     if (mesero) {
         mesero.style.display = 'block';
         mesero.textContent = 'Mesero:';
 
     }
     */

    const cardHeader = document.getElementById('myCardHeader');

    if (cardHeader) {
        cardHeader.classList.remove('border-1', 'bg-indigo-lt');
    }

    const img = document.getElementById('img_ventas_directas');

    if (img) {
        img.style.display = 'none';
    }
    const mesa = document.getElementById('mesa_pedido');

    if (mesa) {
        mesa.style.display = 'block';
    }
    const pedid = document.getElementById('pedido_mesa');
    const mesero = document.getElementById('nombre_mesero');
    if (pedid) {
        pedid.textContent = 'Pedido:';
    }

    if (mesero) {
        mesero.style.display = 'block';

    }
    if (mesa) {
        mesa.textContent = 'Mesa:';

    }

}