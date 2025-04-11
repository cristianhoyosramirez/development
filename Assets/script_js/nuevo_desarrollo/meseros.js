function meseros(id_mesero) {
    var url = document.getElementById("url").value;
    let mesa = document.getElementById("id_mesa_pedido").value;
    let tipo_usuario = document.getElementById("tipo_usuario").value;



    $('#mesero').val(id_mesero)
    $("#modal_meseros").modal("hide");
    //sweet_alert('success', 'Mesero asignado ')

    if (mesa == "") {

        $.ajax({
            data: {
                id_mesero,
                id_mesa,
                tipo_usuario
            },
            url: url +
                "/" +
                "pedidos/actualizar_mesero",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#modal_meseros').modal('hide')
                    $('#nombre_mesero').html('Mesero: ' + resultado.nombre_mesero)
                    sweet_alert('success', 'Mesero asignado')



                }
                if (resultado.resultado == 0) {
                    sweet_alert('warning', 'Acción requiere permisos')
                }

            },
        });

    }

    if (mesa != "") {
        id_mesa = mesa


        $.ajax({
            data: {
                id_mesero,
                id_mesa,
                tipo_usuario
            },
            url: url +
                "/" +
                "pedidos/actualizar_mesero",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#modal_meseros').modal('hide')
                    $('#nombre_mesero').html('Mesero: ' + resultado.nombre_mesero)
                    sweet_alert('success', 'Mesero asignado')

                }
                if (resultado.resultado == 0) {
                    sweet_alert('warning', 'Acción requiere permisos')
                }
            },
        });
    }
}