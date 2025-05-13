function insertarDatos(data) {
    let url = document.getElementById("url").value
    let id_mesa = document.getElementById("id_mesa_pedido").value;
    $.ajax({
        type: 'POST',
        url: url + "/" + "pedidos/nota", // Cambia esto a tu script PHP para insertar en la base de datos
        data: {
            data: data,
            id_mesa
        }, // Pasar los datos al script PHP
        success: function(response) {
            console.log('Datos insertados:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error al insertar datos:', error);
        }
    });
}
