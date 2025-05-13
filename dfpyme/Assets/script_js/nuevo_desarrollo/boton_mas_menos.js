
function incrementarCantidad(event) {
    event.preventDefault();
    let input = document.getElementById('input_cantidad');
    let newValue = parseInt(input.value) || 0; // Obtener el valor actual o 0 si es NaN
    newValue++; // Incrementar el valor
    input.value = newValue; // Establecer el nuevo valor en el campo de entrada
}


function decrementarCantidad(event) {
    event.preventDefault();
    let input = document.getElementById('input_cantidad');
    let newValue = parseInt(input.value) || 1; // Obtener el valor actual o 1 si es NaN
    if (newValue > 1) {
        newValue--; // Decrementar el valor si es mayor que 1
    }
    input.value = newValue; // Establecer el nuevo valor en el campo de entrada
}

function agregarTodoAlPedido(event) {
    let cantidad = event.target.closest('.elemento').querySelector('.cantidad-input').value;
    console.log('Agregar al pedido: ' + cantidad);
}
