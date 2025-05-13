function mesas_salon(id_salon) {

    let mesas = document.getElementById("todas_las_mesas");
    mesas.style.display = "block";
    let tipo_pedido = document.getElementById("tipo_pedido").value;

    if (tipo_pedido == "computador") {
        let lista_categorias = document.getElementById("lista_categorias");
        lista_categorias.style.display = "none";
    }
    let url = document.getElementById("url").value

    $.ajax({
        type: 'post',
        url: url + "/" + "pedidos/mesas_salon", // Cambia esto a tu script PHP para insertar en la base de datos
        data: {
            id_salon
        }, // Pasar los datos al script PHP
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {
                limpiar_todo();
                $("#todas_las_mesas").html(resultado.mesas);
                $("#lista_categorias").html(resultado.categorias);

            }
        },
    });

}