
let invoice = {
    id: 0,
    dian_status: "",
    order_reference: ""
}
let erroresp = {
    errors: []
};
let Error = {
    error: ""
};

//async function sendInvoice(iddoc) {
async function enviarDoc(iddoc) {



    invoice.id = iddoc;
    $("#id_de_factura").val(iddoc);
    $("#barra_progreso").modal("show");
    $("#barra_de_progreso").show();
    $("#respuesta_de_dian").html('Esperando respuesta DIAN');
    $("#texto_dian").html('')


    let url = new URL("http://localhost:5000/api/Invoice/id");
    //let url = new URL("http://localhost:3000/api");
    url.search = new URLSearchParams({
        id: iddoc
    });
    const response = await fetch(url, {
        method: "GET"
    });
    const data = await response.json();
    if (response.status === 200) {
        invoice = JSON.parse(JSON.stringify(data, null, 2));

        //alert('Fact No ' + invoice.id + ' ' + invoice.order_reference + ' ' + invoice.dian_status);
        //console.log('Fact No ' + invoice.order_reference + ' ' + invoice.dian_status);

        // $("#barra_progreso").modal("hide");
        $("#id_factura").val(invoice.id);
        $("#barra_de_progreso").hide();
        $("#respuesta_dian").show();
        $("#opciones_dian").show();
        $("#texto_dian").html(invoice.order_reference + ' ' + invoice.dian_status);
        table = $('#consulta_ventas').DataTable();
        if (table) {
            table.draw();
        }
    } else if (response.status === 400) { // Advertencia
        erroresp = JSON.parse(JSON.stringify(data, null, 2));
        //console.log(erroresp.errors[0].error);
        //alert(erroresp.errors[0].error);
        $("#barra_de_progreso").hide();
        $("#respuesta_dian").show();
        $("#error_dian").show();
        $("#texto_dian").html(erroresp.errors[0].error);
        $("#id_factura").val(invoice.id);
    } else {
        Error = JSON.parse(JSON.stringify(data, null, 2)); //Error Api 
        //alert(Error.error);
        $("#barra_de_progreso").hide();
        $("#respuesta_dian").show();
        $("#error_dian").show();
        $("#texto_dian").html('Respuesta DIAN: ' + erroresp.errors[0].error);
        $("#id_factura").val(invoice.id);
    }

}

function sendInvoice(iddoc) {

    var url = document.getElementById("url").value;
    $.ajax({
        data: {
            iddoc
        },
        url: url +
            "/" +
            "reportes/comprobar_fechas",
        type: "post",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 0) {


                $('#tittle_up').html('Trasmision de facturas ');
                $('#texto_fact').html('');
                $('#titulo_actualizacion_fechas').html('¿Desea retrasmitir la  factura ' + resultado.numero + '?')
                $('#id_doc').val(resultado.id_doc)

                //document.getElementById('btnSenDian').setAttribute('onclick', 'actualizar_fechas(' + resultado.id_doc + ')');
                document.getElementById('btnSenDian').setAttribute('onclick', 'enviarDoc(' + resultado.id_doc + ')');
                myModal = new bootstrap.Modal(
                    document.getElementById("modal_actualizacion_fechas"), {}
                );
                myModal.show();
            }

            if (resultado.resultado == 1) { //la factura tiene transaccion id y tiene fecha actual 
                //enviarDoc(resultado.id_doc)

                $('#tittle_up').html('Trasmision de facturas ');
                $('#texto_fact').html('');
                $('#titulo_actualizacion_fechas').html('¿Desea retrasmitir la  factura ' + resultado.numero + '?')
                $('#id_doc').val(resultado.id_doc)

                //document.getElementById('btnSenDian').setAttribute('onclick', 'actualizar_fechas(' + resultado.id_doc + ')');
                document.getElementById('btnSenDian').setAttribute('onclick', 'sendInvoiceDian(' + resultado.id_doc + ')');
                document.getElementById('btnSenDian').innerHTML = "Trasmitir";
                myModal = new bootstrap.Modal(
                    document.getElementById("modal_actualizacion_fechas"), {}
                );
                myModal.show();
            }
            if (resultado.resultado == 2) { //la factura tiene transaccion id y tiene fecha menor a la fecha actual 
                //enviarDoc(resultado.id_doc)

                $('#tittle_up').html('Trasmision de facturas ');
                $('#texto_fact').html('');
                $('#titulo_actualizacion_fechas').html(
                    '!La fecha de generación para la factura ' + resultado.numero + ' es diferente a la de validación¡<br>' +
                    '¿Actualizar  fecha y transmitir?'
                );

                $('#id_doc').val(resultado.id_doc)

                //document.getElementById('btnSenDian').setAttribute('onclick', 'actualizar_fechas(' + resultado.id_doc + ')');
                document.getElementById('btnSenDian').setAttribute('onclick', 'actualizar_fechas(' + resultado.id_doc + ')');
                document.getElementById('btnSenDian').innerHTML = "Actualizar fecha y trasmitir ";
                myModal = new bootstrap.Modal(
                    document.getElementById("modal_actualizacion_fechas"), {}
                );
                myModal.show();
            }
            if (resultado.resultado == 3) { //la factura no tiene transaccion id y tiene fecha actual 
                //enviarDoc(resultado.id_doc)

                $('#tittle_up').html('Trasmision de facturas ');
                $('#texto_fact').html('');
                $('#titulo_actualizacion_fechas').html(
                    '!La fecha de generación para la factura ' + resultado.numero + ' es diferente a la de validación¡<br>' +
                    '¿Actualizar la fecha y transmitirla?'
                );

                $('#id_doc').val(resultado.id_doc)

                document.getElementById('btnSenDian').setAttribute('onclick', 'actualizar_fechas(' + resultado.id_doc + ')');
                //document.getElementById('btnSenDian').setAttribute('onclick', 'enviarDoc(' + resultado.id_doc + ')');
                myModal = new bootstrap.Modal(
                    document.getElementById("modal_actualizacion_fechas"), {}
                );
                myModal.show();
            }



        },
    });

}


 function sendInvoiceDian(id_fact) {

    var url = document.getElementById("url").value;

    $("#id_de_factura").val(id_fact);
    $("#barra_progreso").modal("show");
    $("#barra_de_progreso").show();
    $("#respuesta_de_dian").html('Esperando respuesta DIAN');
    $("#texto_dian").html('')

    $.ajax({
        data: {
            id_fact
        },
        url: url +
            "/" +
            "reportes/retrasmistir",
        type: "post",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);
            if (resultado.resultado == 1) {

                table = $('#consulta_ventas').DataTable();
                if (table) {
                    table.draw();
                }

                $("#barra_progreso").modal("hide");

                sweet_alert_start('success','Factura firmada por la DIAN')


            }
            if (resultado.resultado == 2) {

                //$("#barra_progreso").modal("hide");

                console.log('jjj')
                $('#zz').html('No hay internet')
                //sweet_alert_start('error','No hay internet ')


            }
        },
    });

} 


/* function sendInvoiceDian(id_fact) {
    var url = document.getElementById("url").value;

    $("#id_de_factura").val(id_fact);
    $("#barra_progreso").modal("show");
    $("#barra_de_progreso").show();
    $("#respuesta_de_dian").html('Esperando respuesta DIAN');
    $("#texto_dian").html('');

    $.ajax({
        data: { id_fact },
        url: url + "/" + "reportes/retrasmistir",
        type: "post",
        success: function (resultado) {
            var resultado = JSON.parse(resultado);

            if (resultado.resultado == 1) {
                // Actualiza la tabla si existe
                table = $('#consulta_ventas').DataTable();
                if (table) {
                    table.draw();
                }

                // Cierra el modal
                console.log("Cerrando modal para resultado 1");
                $("#barra_progreso").modal('hide');

                // Muestra el mensaje de éxito
                sweet_alert_start('success', 'Factura firmada por la DIAN');
            }

            if (resultado.resultado == 0) {
                             
                // Muestra el mensaje de éxito
                sweet_alert_start('success', 'Factura firmada por la DIAN');
                $("#barra_progreso").modal('show');
            }
        },
    });
}
 */

