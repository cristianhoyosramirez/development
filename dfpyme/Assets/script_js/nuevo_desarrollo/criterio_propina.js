    function criterio_propina() {

            var x = document.getElementById("descuento_propina").value;
            var tot = document.getElementById("subtotal_pedido").value;
            if (tot > 0) {
                if (x == 1 || x == 2) {
                    // var y = document.getElementById("valor_descuento%");
                    document.getElementById("valor_descuento_propina").disabled = false;
                    //var y = document.getElementById("valor_descuento%").value;
                    //console.log(y)
                }
            }
            if (tot == 0) {
                const Toast_1 = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast_1.addEventListener('mouseenter', Swal.stopTimer)
                        toast_1.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast_1.fire({
                    icon: 'info',
                    title: 'No hay venta para aplicar descuento '
                })
            }


        }