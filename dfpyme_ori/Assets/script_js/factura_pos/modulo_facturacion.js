function modulo_facturacion() {

    var url = document.getElementById("url").value;
    var id_factura = document.getElementById("numero_de_factura_imp").value;

    $.ajax({
        url: url + "/" + "factura_pos/modulo_facturacion",
        type: "GET",
        data: {
            id_factura,
        },
        success: function(resultado) {
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
                    title: 'Proceso de facturación correcto'
                })

                $('#clean_form')[0].reset();
                $("#productos_de_pedido_pos").html(resultado.tabla);
                $('#total_pedido_pos').val(0)
                $("#valor_venta_producto").val('0');
                $("#cerrar_venta_con_impuestos").modal("hide");

                $('#vista_lista_precios').html(resultado.lista_precios);
                document.getElementById("buscar_producto").focus();
            }
        },
    });
}
