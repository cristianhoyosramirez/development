function imprimir_comanda() {
    let url = document.getElementById("url").value;
    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let id_usuario = document.getElementById("id_usuario").value;
    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido ')
    } else if (id_mesa != "") {

        $.ajax({
            data: {

                id_mesa,id_usuario

            },
            url: url + "/" + "pedidos/imprimirComanda",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    sweet_alert_start('success', 'Impresión de comanda éxitoso')
                }
                if (resultado.resultado == 0) {

                    sweet_alert_start('warning', 'No hay productos para imprimir ')
                }
            },
        });
    }
}