function agregar_nota_al_pedido() {

  var url = document.getElementById("url").value;
  var numero_pedido = document.getElementById("numero_pedido_salvar").value;
 
    $.ajax({
      data: {numero_pedido },
      url: url + "/" + "pedido/agregar_nota_al_pedido",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);

        if (resultado.resultado == 1) {
          myModal = new bootstrap.Modal(document.getElementById("nota_pedido"),{});
          myModal.show();
        }
        if (resultado.resultado == 2) {
          document.getElementById("nota_de_pedido").value=resultado.nota_pedido
            myModal = new bootstrap.Modal(document.getElementById("nota_pedido"),{});
            myModal.show(); 
         }
      },
    }); 
}
/**
 * Autofocus
 */
$("#modal-success").on("shown.bs.modal", function () {
  $(this).find("#nota_pedido").focus();
});


function minusculas_a_mayusculas() {
  var x = document.getElementById("nota_de_pedido");
  x.value = x.value.toUpperCase();
}
