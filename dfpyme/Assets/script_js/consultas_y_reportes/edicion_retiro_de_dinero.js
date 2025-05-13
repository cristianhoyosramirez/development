function edicion_retiro_de_dinero(id_retiro) {
    // alert('hola mundo ')
    var url = document.getElementById("url").value;
    var id_retiro = id_retiro;
    var id_usuario = id_usuario;
    $.ajax({
        data: {
            id_usuario,
            id_retiro
        },
        url: url + "/" + "devolucion/edicion_retiro_de_dinero",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 1) {
                $('#edit_retiro_de_dinero').val(resultado.valor_retiro)
                $('#concepto_retiros').val(resultado.concepto)
                $('#id_edicion_retiro_de_dinero').val(resultado.id_retiro)
                //document.getElementById('edit_retiro_de_dinero').value='hola mudo';
                $("#modal_consulta_retiros").modal("hide");
                myModal = new bootstrap.Modal(
                    document.getElementById("edicion_retiro_de_dinero"), {}
                );
                myModal.show()

            }
        },
    });
}
