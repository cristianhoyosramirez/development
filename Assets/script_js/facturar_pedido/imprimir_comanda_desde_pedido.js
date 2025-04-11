function imprimir_comanda_desde_pedido() {
  var url = document.getElementById("url").value;
  var numero_pedido = document.getElementById(
    "numero_pedido_imprimir_comanda"
  ).value;

  $.ajax({
    data: {
      numero_pedido,
    },
    url: url + "/" + "comanda/imprimir_comanda_desde_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "success",
          title: "Impresión de comanda",
        });
      }
      if (resultado.resultado == 0) {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });

        Toast.fire({
          icon: "info",
          title: "No hay productos para imprimir en comanda",
        });
      }
    },
  });
}
