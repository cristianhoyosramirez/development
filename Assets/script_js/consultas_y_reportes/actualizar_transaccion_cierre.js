function actualizar_transaccion_cierre(id_apertura) {
    // alert('hola mundo ')
    var url = document.getElementById("url").value;
    var transaccion_usuario = document.getElementById("edit_transaccion_cierre").value;
    var transaccion = transaccion_usuario.replace(/[.]/g, "");
    $.ajax({
      data: {
        id_apertura,
        transaccion
      },
      url: url + "/" + "consultas_y_reportes/actualizar_transaccion_usuario",
      type: "POST",
      success: function(resultado) {
        var resultado = JSON.parse(resultado);

        if (resultado.resultado == 1) {
          $("#edicion_de_transaccion").modal("hide");
          $("#nuevo_saldo").html(resultado.saldo);
          $("#edit_transaccion_cierre").html(resultado.transacc);
          $("#transaccion_usuario").html(resultado.transaccion);

        }
      },
    });
  }