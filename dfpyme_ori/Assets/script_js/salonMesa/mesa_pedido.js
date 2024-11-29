/**
 * Consultar el estado de la mesa 0=libre , 1=ocupada,2=reservada
 * @param {*} id_mesa
 */
function mesa_pedido(id_mesa) {
  var salon = document.querySelector("#salones");
  salon.style.display = "none";
  var pedido = document.querySelector("#pedido");
  pedido.style.display = "block";
  var url = document.getElementById("url").value;

  $.ajax({
    data: {
      id_mesa,
    },
    url: url + "/" + "mesas/pedido",
    type: "post",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        // La mesa tiene pedido
        $("#nombre_de_mesa").html(resultado.nombre_mesa);
        document.getElementById("id_mesa").value = resultado.id_mesa;
        document.getElementById("numero_pedido_salvar").value = resultado.numero_pedido;
        document.getElementById("numero_pedido_imprimir_comanda").value = resultado.numero_pedido;
        document.getElementById("observacion_general_de_pedido").value = resultado.nota_pedido;
        document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;

        $("#valor_total").html(resultado.total);
        $("#cantidad_de_productos").html(resultado.cantidad_de_productos);


        $("#numero_pedido_mostrar").html(resultado.numero_pedido);
        $("#productos_pedido").html(resultado.productos_pedido);


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
          title: "La mesa:" + " " + resultado.nombre_mesa + " " + "tiene pedido cargado",
        })

      } else if (resultado.resultado == 0) {
        //la mesa esta libre sin pedido
        $("#nombre_de_mesa").html(resultado.nombre_mesa);
        document.getElementById("numero_pedido_salvar").value = "";
      /*   Swal.fire({
          icon: "success",
          confirmButtonText: "Aceptar",
          confirmButtonColor: "#2AA13D",
          title: "Mesa:" + " " + resultado.nombre_mesa + " " + "disponible",
        }); */

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
          title: "La mesa:" + " " + resultado.nombre_mesa + " " + "esta disponible",
        })

        document.getElementById("id_mesa").value = resultado.id_mesa;
        document.getElementById("id_mesa_facturacion").value = resultado.id_mesa;
        $("#nombre_mesa").html(resultado.nombre_mesa);
      }
    },
  });
}


