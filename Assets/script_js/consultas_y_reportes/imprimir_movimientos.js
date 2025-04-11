function imprimir_movimientos() {
    var url = document.getElementById("url").value;
    let id_apertura = document.getElementById("id_apertura").value;
    $.ajax({
        data: {
            id_apertura
        },
        url: url + "/" + "pedidos/imprimir_movimiento_caja",
        type: "POST",
        success: function (resultado) {
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
                    title: 'Impresión de movimientos de caja correcto'
                })
            }
            if (resultado.resultado == 2) {
                alert('no se pudo imprimir')
            }
        },
    });
}