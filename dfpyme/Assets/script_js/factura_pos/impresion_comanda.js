function impresion_comanda(id_usuario) {
    var url = document.getElementById("url").value;
  $.ajax({
    data: {
      id_usuario,
      
    },
    url: url + "/" + "factura_directa/comanda_directa",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      console.log(resultado);
      if (resultado.resultado == 0) {
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
            icon: 'info',
            title: 'No hay productos para imprimir en comanda'
          })
      }
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
          title: 'Impresion de comanda éxitoso'
        })
      }
    },
  });
}
