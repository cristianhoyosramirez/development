function actualizar_de_apertura(id_apertura) {


  var url = document.getElementById("url").value;
  var valor_apertura = document.getElementById("cambiar_valor_apertura").value;
  var apertura = valor_apertura.replace(/[.]/g, "");
  $.ajax({
    data: {
      id_apertura,
      apertura
    },
    url: url + "/" + "consultas_y_reportes/valor_apertura",
    type: "POST",
    success: function(resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 1) {


        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: 'success',
          title: 'Modificación de aperura éxitoso'
        })

        $("#edicion_de_apertura_de_caja").modal("hide");
        $("#valor_modificado_apertura").html(resultado.valor_apertura);
        $("#nuevo_saldo").html(resultado.saldo);
        $("#cambiar_valor_apertura").val(resultado.val_apertura);

      }
    },
  });
}