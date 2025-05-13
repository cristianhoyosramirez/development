function imprimir_factura_imp() {
    var url = document.getElementById("url").value;
    var numero_de_factura = document.getElementById("numero_de_factura_imp").value;
    $.ajax({
        data: {
            numero_de_factura,
        },
        url: url + "/" + "factura_pos/imprimir_factura",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            //$("#imprimir_retiro").modal("hide");
            if (resultado.resultado == 1) {
                document.getElementById("buscar_producto").focus();
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

                $('#clean_form')[0].reset();
                $("#productos_de_pedido_pos").html(resultado.tabla);
                $('#total_pedido_pos').val(0)
                $("#cerrar_venta_con_impuestos").modal("hide");

                $('#vista_lista_precios').html(resultado.lista_precios);
                document.getElementById("buscar_producto").focus();
            }
        },
    });
}