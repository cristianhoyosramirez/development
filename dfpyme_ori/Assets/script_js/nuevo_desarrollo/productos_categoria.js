function productos_categoria(id_categoria) {

    let url = document.getElementById("url").value;
    let tipo_pedido = document.getElementById("tipo_pedido").value;


    $.ajax({
        data: {
            id_categoria, tipo_pedido
        },
        url: url + "/" + "pedidos/productos_categoria",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                var categoria = document.getElementById("productos_categoria");
                categoria.style.display = "block";

                var productos = document.getElementById("canva_producto");
                productos.style.display = "none";

                let tipo_pedido = document.getElementById("tipo_pedido").value;

               

                $('#productos_categoria').html(resultado.productos)
                $('#sub_categorias').html(resultado.sub_categorias)
                $('#lista_categorias').html(resultado.lista_categoria)
            }
        },
    });


}