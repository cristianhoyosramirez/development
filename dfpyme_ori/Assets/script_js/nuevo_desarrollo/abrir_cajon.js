function abrir_cajon() {
    let url = document.getElementById("url").value;
    $.ajax({

        url: url + "/" + "configuracion/abrir_cajon",
        type: "get",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                sweet_alert('success','apertura cajon')


            }
        },
    });
}
