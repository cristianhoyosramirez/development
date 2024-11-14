$(document).ready(function () {

    $('#search').click(function () {
        var tipo_documento = $('#tipo_documento').val();
        var fecha_inicial = $('#fecha_inicial_reporte').val();
        var fecha_final = $('#fecha_final_reporte').val();
        var cliente = $('#id_cliente_reporte').val();
        var url = $('#url').val();
        var tipo_consulta = $('#tipo_consulta_venta').val();

        if (tipo_consulta == 1) { // Búsqueda por número de documento

            document.getElementById("data_table_documeto").style.display = "none";
            //var documento = $('#data_table_documeto').val();
            var documento = $('#consulta_documento').val();
            $.ajax({
                data: {
                    documento
                },
                url: url + "/" + "consultas_y_reportes/documento",
                type: "get",
                success: function (resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 0) {
                        $('#consulta_documento').val('');
                        $('#consulta_documento').focus();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer);
                                toast.addEventListener("mouseleave", Swal.resumeTimer);
                            },
                        });

                        Toast.fire({
                            icon: "info",
                            title: "No se encontraron registros ",
                        });
                    }
                    if (resultado.resultado == 1) {
                        //document.getElementById("numero_de_documento").style.display = "block";
                        $('#consulta_documento').val('');
                        $('#consulta_documento').focus();;
                        $('#consultar_por_documento').html(resultado.documento)
                        $('#saldo_total').html(resultado.valor_factura)
                        $('#pagos_factura').html(resultado.pagos)
                        $('#pagos_cliente').html(resultado.saldo_factura)

                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer);
                                toast.addEventListener("mouseleave", Swal.resumeTimer);
                            },
                        });

                        Toast.fire({
                            icon: "success",
                            title: "Factura encontrada",
                        });
                    }
                },
            });
        }

        if (tipo_consulta == 2) {  // Búsqueda por tipo de documento

            var documento = $('#tipo_documento').val();
            var fecha_inicial = $('#fecha_inicial_reporte').val();
            var fecha_final = $('#fecha_final_reporte').val();
            var data_table = $('#data_table').val();
            if (data_table == 1) {
                //location.reload();
                $('#consulta_ventas').DataTable().destroy()
            }
            if (documento == "") {
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
                    icon: 'error',
                    title: 'Falta definir el tipo de documento'
                })

            }
            if (documento != "") {


                $.ajax({
                    data: {
                        fecha_inicial, fecha_final, documento,
                    },
                    url: url + "/" + "consultas_y_reportes/consultar_por_documento",
                    type: "POST",
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {

                            var dataTable = $('#consulta_ventas').DataTable({
                                serverSide: true,
                                processing: true,
                                searching: true,
                                "order": [
                                    [0, 'desc']
                                ],
                                "language": {
                                    "decimal": "",
                                    "emptyTable": "No hay datos",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                                    "infoFiltered": "(Filtro de _MAX_ total registros)",
                                    "infoPostFix": "",
                                    "thousands": ",",
                                    "lengthMenu": "Mostrar _MENU_ registros",
                                    "loadingRecords": "Cargando...",
                                    "processing": "Procesando...",
                                    "search": "Buscar",
                                    "zeroRecords": "No se encontraron coincidencias",
                                    "paginate": {
                                        "first": "Primero",
                                        "last": "Ultimo",
                                        "next": "Próximo",
                                        "previous": "Anterior"
                                    },
                                    "aria": {
                                        "sortAscending": ": Activar orden de columna ascendente",
                                        "sortDescending": ": Activar orden de columna desendente"
                                    }

                                },
                                ajax: {

                                    url: url + "/" + "consultas_y_reportes/tipo_documento",
                                    data: {
                                        documento, fecha_inicial, fecha_final
                                    },
                                    "dataSrc": function (json) {
                                        $('#saldo_total').html('').append(json.total)
                                        $('#saldo_cliente').html('').append(json.saldo)
                                        $('#pagos_factura').html('').append(json.pagos)
                                        var data_table = $('#data_table').val(1);
                                        return json.data;
                                    }
                                },
                                aoColumnDefs: [{
                                    "bSortable": false,
                                    "aTargets": [4]
                                }],
                            });


                        }

                        if (resultado.resultado == 0) {
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
                                icon: 'info',
                                title: 'No hay datos para la consulta  '
                            })
                        }
                    },
                });






            }
        }

        if (tipo_consulta == 3) {

            var documento = $('#tipo_documento').val();
            var fecha_inicial = $('#fecha_inicial_reporte').val();
            var fecha_final = $('#fecha_final_reporte').val();
            var cliente = $('#id_cliente_reporte').val();
            var data_table = $('#data_table').val();

            if (data_table == 1) {
                $('#consulta_ventas').DataTable().destroy()
            }
            if (cliente == "") {
                $('#error_cliente_cartera').html('Falta definir el cliente')
            } else {



                $.ajax({
                    data: {
                        documento, fecha_inicial, fecha_final, cliente
                    },
                    url: url + "/" + "consultas_y_reportes/consultar_por_cliente",
                    type: "POST",
                    success: function (resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 0) {

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
                                icon: 'info',
                                title: 'No hay datos para los criterios de busqueda  '
                            })



                        }


                        if (resultado.resultado == 1) {
                            var dataTable = $('#consulta_ventas').DataTable({
                                serverSide: true,
                                processing: true,
                                searching: true,
                                "order": [
                                    [0, 'desc']
                                ],
                                "language": {
                                    "decimal": "",
                                    "emptyTable": "No hay datos",
                                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                                    "infoFiltered": "(Filtro de _MAX_ total registros)",
                                    "infoPostFix": "",
                                    "thousands": ",",
                                    "lengthMenu": "Mostrar _MENU_ registros",
                                    "loadingRecords": "Cargando...",
                                    "processing": "Procesando...",
                                    "search": "Buscar",
                                    "zeroRecords": "No se encontraron coincidencias",
                                    "paginate": {
                                        "first": "Primero",
                                        "last": "Ultimo",
                                        "next": "Próximo",
                                        "previous": "Anterior"
                                    },
                                    "aria": {
                                        "sortAscending": ": Activar orden de columna ascendente",
                                        "sortDescending": ": Activar orden de columna desendente"
                                    }

                                },
                                ajax: {

                                    url: url + "/" + "consultas_y_reportes/cliente",
                                    data: {
                                        documento, fecha_inicial, fecha_final, cliente
                                    },
                                    "dataSrc": function (json) {
                                        $('#saldo_total').html('').append(json.saldo)
                                        $('#pagos_factura').html('').append(json.pagos)
                                        $('#saldo_cliente').html('').append(json.cartera)
                                        var data_table = $('#data_table').val(1);
                                        return json.data;
                                    }
                                },
                                aoColumnDefs: [{
                                    "bSortable": false,
                                    "aTargets": [4]
                                }],
                            });
                        }

                    },
                });







            }
        }
    });









});
