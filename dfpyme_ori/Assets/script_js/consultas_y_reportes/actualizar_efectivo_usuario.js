
function actualizar_efectivo_usuario(id_apertura) {
    // alert('hola mundo ')
    var url = document.getElementById("url").value;
    var efectivo_usuario = document.getElementById("edit_efectivo_usuario").value;
    var efectivo = efectivo_usuario.replace(/[.]/g, "");
    $.ajax({
        data: {
            id_apertura,
            efectivo
        },
        url: url + "/" + "consultas_y_reportes/actualizar_efectivo_usuario",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 1) {
                $("#edicion_efectivo").modal("hide");
                $("#nuevo_saldo").html(resultado.saldo);
                $("#edit_efectivo_usuario").html(resultado.efecti);
                $("#efectivo_usuario").html(resultado.efectivo);

            }
        },
    });
}