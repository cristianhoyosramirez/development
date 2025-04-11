function impresion_de_factura() {
    var url = document.getElementById("url").value;
    var id_de_factura = document.getElementById("numero_de_factura_sin_imp").value;
    $.ajax({
        data: {
            id_de_factura
        },
         url: url + "/" + "factura_pos/imprimir_factura_sin_impuestos_directa",
        //url: "<?php echo base_url(); ?>/factura_pos/imprimir_factura_sin_impuestos_directa",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Error en la cantidad",
                    confirmButtonText: "Aceptar",
                    confirmButtonColor: "#2AA13D",
                });
            }
            if (resultado.resultado == 1) {



                $("#productos_de_pedido_pos").html(resultado.tabla);
                $('#total_pedido_pos').val(0)
                $('#pago_con_efectivo').val(0)
                $('#pago_con_transaccion').val(0)
                $('#cambio_del_pago').val(0)
                $("#cerrar_venta_sin_impuestos").modal("hide");
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
                    title: 'Impresión de factura correcta'
                })
                document.getElementById("buscar_producto").focus();
            }
        },
    });



}