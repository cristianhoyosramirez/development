
function pedido_mesa(id_mesa, nombre_mesa) {

    $('#error_producto').html('')
    $('#producto').val('')

    /*     const cardHeader = document.getElementById('myCardHeader');
        cardHeader.classList.remove('border-1', 'bg-indigo-lt');
        const img = document.getElementById('img_ventas_directas');
    
        if (img) {
            img.style.display = 'none';
        }
    
        const mesa = document.getElementById('mesa_pedido');
        mesa.style.display = 'block';
    
        const pedid = document.getElementById('pedido_mesa');
        const mesero = document.getElementById('nombre_mesero');
        if (pedid) {
            pedid.textContent = 'Pedido:';
        }
    
        if (mesero) {
            mesero.style.display = 'block';
    
        }
        if (mesa) {
            mesa.textContent = 'Mesa:';
    
        } */

    header_pedido()

    $('#mesa_pedido').html(nombre_mesa)
    $('#id_mesa_pedido').val(id_mesa)

    let tipo_pedido = document.getElementById("tipo_pedido").value;

    if (tipo_pedido == "computador") {
        let categorias = document.getElementById("lista_categorias");
        categorias.style.display = "block";
    }

    if (tipo_pedido == "computador") {
        let mesas = document.getElementById("todas_las_mesas");
        mesas.style.display = "none";
    }

   

    $("#lista_todas_las_mesas").modal("hide");
    $('#mesasOffcanvas').offcanvas('hide');

    let url = document.getElementById("url").value;
    $.ajax({
        data: {

            id_mesa,

        },
        url: url + "/" + "pedidos/pedido",
        type: "POST",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {


                $('#val_pedido').html(resultado.total_propina)
                $('#mesa_productos').html(resultado.productos_pedido)
                //$('#info_pedido').html(resultado.fecha)
                $('#nombre_mesero').html('Vendedor: ' + resultado.nombre_mesero)
                $('#id_mesa_pedido').val(resultado.id_mesa)
                $('#pedido_mesa').html('Pedido: ' + resultado.numero_pedido)
                $('#valor_pedido').html(resultado.total_pedido)
                $('#nota_pedido').val(resultado.nota_pedido)
                $('#subtotal_pedido').val(resultado.sub_total)
                $('#propina_del_pedido').val(resultado.propina)
                //$("#producto").readOnly = false;
                $("#producto").attr("readonly", false);
                $("#producto").focus();
                //$("#producto").attr("readonly", false);
            }
        },
    });

}