function agregar_al_pedido(id_producto) {

    let url = document.getElementById("url").value;
    let id_mesa = document.getElementById("id_mesa_pedido").value;
    let id_usuario = document.getElementById("id_usuario").value;
    let mesero = document.getElementById("mesero").value;

    if (id_mesa != "") {

        $.ajax({
            data: {
                id_producto,
                id_mesa,
                id_usuario,
                mesero
            },
            url: url + "/" + "pedidos/agregar_producto",
            type: "POST",
            success: function (resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 900,
                        timerProgressBar: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Agregado'
                    });

                    //$('#modalAtributos').modal('show')

                    $('#mesa_productos').html(resultado.productos_pedido)
                    $('#valor_pedido').html(resultado.total_pedido)
                    $('#val_pedido').html(resultado.total_pedido)

                    $('#subtotal_pedido').val(resultado.sub_total)
                    $('#id_mesa_pedido').val(resultado.id_mesa)
                    $("#propina_del_pedido").val(resultado.propina);
                    //$('#mesa_pedido').html('VENTAS DE MOSTRADOR')
                    $('#producto').focus();
                    document.getElementById("id_tabla_producto").value = resultado.id;
                    document.getElementById("asigCompo").innerHTML = resultado.atributos;


                    if (resultado.estado == 1) {
                        /*  const cardHeader = document.getElementById('myCardHeader');
                         cardHeader.classList.add('border-1', 'bg-indigo-lt');
                         const img = document.getElementById('img_ventas_directas');
                         img.style.display = 'block';
     
                         const mesa = document.getElementById('mesa_pedido');
                         mesa.style.display = 'none';
     
                         const pedido = document.getElementById('pedido_mesa');
                         const mesero = document.getElementById('nombre_mesero');
                         if (pedido) {
                             pedido.textContent = 'VENTAS DE MOSTRADOR';
                         }
     
                         if (mesero) {
                             mesero.style.display = 'none';
     
                         } */

                        header_mesa();

                    }

                    if (resultado.estado == 0) {
                        $('#pedido_mesa').html('Pedido: ' + resultado.numero_pedido)
                    }

                }
            },
        });

    }

    if (id_mesa == "") {
        $("#error_producto").html('No hay venta selecccionada ')
    }

}