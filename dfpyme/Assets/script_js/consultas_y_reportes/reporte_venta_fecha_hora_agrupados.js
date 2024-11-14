function reporte_venta_fecha_hora_agrupados() {
    var fecha_inicial = document.getElementById("fecha_inicial_agrupado").value;
    var hora_inicial = document.getElementById("hora_inicial_agrupado").value;
    var fecha_final = document.getElementById("fecha_final_agrupado").value;
    var hora_final = document.getElementById("hora_final_agrupado").value;
    var url = document.getElementById("url").value;

    if (fecha_inicial == "") {
        $("#error_fecha_inicial_agrupado").html("Error en la hora de inicio  ");
    }
    if (fecha_final == "") {
        $("#error_fecha_final_agrupado").html("Error en la hora de finalización  ");
    }


    if(fecha_inicial != ""  && fecha_final != "" ){
        $.ajax({
            type: "POST",
            url: url + "/" + "consultas_y_reportes/consultar_producto_agrupado",
            data: { fecha_inicial,hora_inicial,fecha_final,fecha_final,hora_final },
            success: function (resultado) {
              var resultado = JSON.parse(resultado);
        
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
                    title: 'No hay registros para las fechas solicitadas'
                })
              }
              if (resultado.resultado == 2) {
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
                    icon: 'error',
                    title: 'Falta definir la fecha final'
                })
              }
        
              if (resultado.resultado == 1) {
                document.getElementById("consulta_de_producto_agrupado").submit();
                
              }
              if (resultado.resultado == 3) {
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
                    title: 'Registros encontrados'
                })
                $('#datos').html(resultado.datos);
                
              }
            },
          });
    }


}