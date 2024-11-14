function get_mesas() {


    let tipo_pedido = document.getElementById("tipo_pedido").value;

    let mesas = document.getElementById("todas_las_mesas");
    mesas.style.display = "block";
    if (tipo_pedido == "computador") {
        let categorias = document.getElementById("lista_categorias");
        categorias.style.display = "none";
    }
    let url = document.getElementById("url").value

    limpiar_todo();

    $.ajax({
        type: 'post',
        url: url + "/" + "pedidos/get_mesas", // Cambia esto a tu script PHP para insertar en la base de datos
        // Pasar los datos al script PHP
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
                $("#todas_las_mesas").html(resultado.mesas);

            }
        },
    });

}