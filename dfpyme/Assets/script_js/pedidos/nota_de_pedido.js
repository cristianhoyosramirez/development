function nota_de_pedido() {
  var url = document.getElementById("url").value;
  var numero_pedido = document.getElementById("numero_pedido_salvar").value;
  var nota_de_pedido = document.getElementById("nota_de_pedido").value;

  $.ajax({
    data: { numero_pedido,nota_de_pedido },
    url: url + "/" + "pedido/nota_de_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);

      if (resultado.resultado == 1) {
       /*  myModal = new bootstrap.Modal(
          document.getElementById("nota_pedido"),
          {}
        );
        myModal.show(); */
        document.getElementById("observacion_general_de_pedido").value=resultado.nota
        Swal.fire({
          icon: "success",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "Observacion general",
        }); 
      }
    
    },
  });
}

$("#nota_pedido").on("shown.bs.modal", function () {
  $(this).find("#nota_de_pedido_pedido").focus();
});
