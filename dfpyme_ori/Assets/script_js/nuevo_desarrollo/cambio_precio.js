function cambio_precio(url, valor, id) {

    //console.log(valor)

   /*  $.ajax({
        data: {
            valor,
            id_producto_pedido:id
        },
        url: url +
            "/" +
            "eventos/editar_precio_producto",
        type: "post",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                var precioConSeparador = resultado.precio_producto.toLocaleString();

                // Asigna el valor con separador de miles al elemento con id "descuento_manual"
                $('#descuento_manual').val(precioConSeparador);


            }
        },
    }); */

}