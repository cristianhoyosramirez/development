function cambiar_mesas() {
    var url = document.getElementById("url").value;
    var id_mesa = document.getElementById("id_mesa_pedido").value;
    if(id_mesa == ""){
       sweet_alert('warning','No hay pedido ')
    }else if (id_mesa != "") {
        $.ajax({
            data: {
                id_mesa
            },
            url: url + "/" + "mesas/cambiar_de_mesa",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {
                    $("#detalle_pedido").html(resultado.detalle_pedido);
                    $("#cambio_de_mesa").html(resultado.mesas);
                    myModal = new bootstrap.Modal(
                        document.getElementById("cambiar_de_mesa"), {}
                    );
                    myModal.show();
                }

                if (resultado.resultado == 0) {
                    Swal.fire({
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        confirmButtonColor: "#2AA13D",
                        title: "La mesa no tiene pedido",
                    });
                }
            },
        });
    }
}