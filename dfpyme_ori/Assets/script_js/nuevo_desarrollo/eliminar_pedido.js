function eliminar_pedido() {
    let url = document.getElementById("url").value
    var id_mesa = document.getElementById("id_mesa_pedido").value;
    var id_usuario = document.getElementById("id_usuario").value;

    if (id_mesa == "") {
        sweet_alert('warning', 'No hay pedido ')
    } else if (id_mesa != "") {

        Swal.fire({
            title: 'Seguro de eliminar el pedido ',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2AA13D',
            cancelButtonColor: '#D63939',
            focusConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'post',
                    url: url + "/" + "pedidos/eliminacion_de_pedido", // Cambia esto a tu script PHP para insertar en la base de datos
                    data: {
                        id_mesa, id_usuario
                    }, // Pasar los datos al script PHP
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            limpiar_todo();
                            sweet_alert('success', 'Pedido eliminado')

                            $("#todas_las_mesas").html(resultado.mesas);
                            $("#val_pedido").html(0);
                            let mesas = document.getElementById("todas_las_mesas");

                            if (mesas) {
                                mesas.style.display = "block";
                            }
                            let lista_categorias = document.getElementById("lista_categorias");

                            if (lista_categorias) {
                                lista_categorias.style.display = "none";
                            }
                            //$("#producto").attr("readonly", true);

                            /*  const cardHeader = document.getElementById('myCardHeader');
                             cardHeader.classList.remove('border-1', 'bg-indigo-lt');
                             const img = document.getElementById('img_ventas_directas');
                             img.style.display = 'none';
 
                             const mesa = document.getElementById('mesa_pedido');
                             mesa.style.display = 'block';
 
                             const pedido = document.getElementById('pedido_mesa');
                             const mesero = document.getElementById('nombre_mesero');
                             if (pedido) {
                                 pedido.textContent = 'Pedido:';
                             }
 
                             if (mesero) {
                                 mesero.style.display = 'block';
 
                             }
                             if (mesa) {
                                 mesa.textContent = 'Mesa:';
 
                             } */

                            header_pedido();


                        }
                        if (resultado.resultado == 0) {
                            sweet_alert_start('error', 'Acción requiere permisos')
                        }
                    },
                });
            }
        })
    }
}