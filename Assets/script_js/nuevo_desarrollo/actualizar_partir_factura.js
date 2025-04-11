
/**
 * 
 * @param {*} event Evento onclick para detener la propagagacion
 * @param {*} cantidad Cantidad que esta en la tabla partir_factura 
 * @param {*} id_tabla_producto id de la tabla partir_factura
 */
function actualizar_partir_factura(event, cantidad, id_tabla_partir_factura) {  
    event.stopPropagation()

    let url = document.getElementById("url").value;

    
    $.ajax({
         data: {
            id_tabla_partir_factura, cantidad
         },
         url: url + "/" + "pedidos/actualizar_cantidad_pago_parcial",
         type: "POST",
         success: function (resultado) {
             var resultado = JSON.parse(resultado);
             if (resultado.resultado == 1) {
 
                 $('#total_pago_parcial').html(resultado.total)
                 $("#productos_pago_parcial").html(resultado.productos);
 
             }
         },
     });

}