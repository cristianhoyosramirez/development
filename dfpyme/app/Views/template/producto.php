<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $this->renderSection('title') ?>&nbsp;-&nbsp;DF PYME</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Jquery-ui -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/jquery-ui/jquery-ui.css" rel="stylesheet">
    <!-- Data tables -->
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
    <link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />
    <!-- Select 2 -->
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Jquery date picker  -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
</head>
<?php $session = session(); ?>

<body>
    <div class="wrapper">
        <?= $this->include('layout/header_mesas') ?>


        <div class="page-wrapper">
            <div class="container-xl">
            </div>
            <div class="page-body">
                <?= $this->renderSection('content') ?>

            </div>
            <?= $this->include('layout/footer') ?>
        </div>
        <!-- Libs JS -->
        <!-- Tabler Core -->
        <script src="<?= base_url() ?>/Assets/js/tabler.min.js"></script>
        <!-- JQuery -->
        <script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
        <!-- jQuery-ui -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
        <!-- Sweet alert -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
        <!-- Data tables -->
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.js"></script>
        <!-- Locales-->
        <script src="<?= base_url() ?>/Assets/script_js/consultas_y_reportes/reporte_venta_fecha_hora_agrupados.js"></script>
        <script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_start.js"></script>
        <!--select2 -->
        <script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>


        <!-- <script>
            function actualizarPrecios(valor) {
                document.getElementById('precio_2').value = valor;
                document.getElementById('precio_3').value = valor;
            }
        </script>  -->


        <script>
            // Función para eliminar cualquier formato (como separadores de miles) y dejar solo el número
            function limpiarNumero(valor) {
                return valor.replace(/\./g, ''); // Elimina los puntos (separadores de miles)
            }

            // Función para formatear el número con separadores de miles
            function formatNumber(n) {
                n = n.replace(/\D/g, ""); // Elimina cualquier carácter que no sea un número
                return n === "" ? n : parseFloat(n).toLocaleString('es-CO'); // Formatear el número con separador de miles
            }

            // Función para actualizar los precios
            function actualizarPrecios(valor) {
                // Limpiar el número antes de asignarlo
                let valorLimpio = limpiarNumero(valor);

                // Formatear el número con separadores de miles antes de mostrarlo
                let valorFormateado = formatNumber(valorLimpio);

                // Asignar el valor formateado a los campos
                document.getElementById('precio_2').value = valorFormateado;
                document.getElementById('precio_3').value = valorFormateado;
            }

            // Evento para formatear el campo 'precio_2' al ingresar un número
            const precio_2 = document.querySelector("#precio_2");
            precio_2.addEventListener("input", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = formatNumber(value); // Formatea el número con separador de miles
            });

            // Evento para formatear el campo 'precio_3' al ingresar un número
            const precio_3 = document.querySelector("#precio_3");
            precio_3.addEventListener("input", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = formatNumber(value); // Formatea el número con separador de miles
            });
        </script>







        <!-- 
        <script>
            function actualizarPrecios(valor) {
                // Eliminar los puntos decimales del valor
                let valorSinPuntos = valor.replace(/\./g, '');

                // Asignar el valor sin puntos a los campos de precios
                document.getElementById('precio_2').value = valorSinPuntos;
                document.getElementById('precio_3').value = valorSinPuntos;
            }
        </script> -->


        <script>
            $(function() {
                // Configuración regional en español para datepicker
                $.datepicker.regional['es'] = {
                    closeText: 'Cerrar',
                    prevText: '< Ant',
                    nextText: 'Sig >',
                    currentText: 'Hoy',
                    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
                    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                    weekHeader: 'Sm',
                    dateFormat: 'dd/mm/yy',
                    firstDay: 1,
                    isRTL: false,
                    showMonthAfterYear: false,
                    yearSuffix: ''
                };

                // Establecer los ajustes regionales por defecto para datepicker en español
                $.datepicker.setDefaults($.datepicker.regional['es']);

                // Inicializar el datepicker en el elemento con el id "fecha"
                $("#fecha").datepicker();

                // Configuración de datepicker para el rango de fechas
                var dateFormat = "yy-mm-dd"; // Cambia el formato de fecha a "yy-mm-dd"

                var desde = $("#fecha_inicial").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    changeYear: true,
                    dateFormat: dateFormat, // Establece el nuevo formato de fecha
                    onClose: function(selectedDate) {
                        hasta.datepicker("option", "minDate", selectedDate);
                    }
                });

                var hasta = $("#fecha_final").datepicker({
                    changeMonth: true,
                    numberOfMonths: 1,
                    changeYear: true,
                    dateFormat: dateFormat, // Establece el nuevo formato de fecha
                    onClose: function(selectedDate) {
                        desde.datepicker("option", "maxDate", selectedDate);
                    }
                });

                // Funcionalidad para exportar a Excel y PDF
                $('#exportarExcelBtn').click(function() {
                    // Agrega tu código para exportar a Excel aquí
                });

                $('#exportarPdfBtn').click(function() {
                    // Agrega tu código para exportar a PDF aquí
                });
            });
        </script>



        <script>
            $("#periodo").select2({
                width: "100%",
                language: "es",
                theme: "bootstrap-5",
                allowClear: false,
                placeholder: "Seleccionar un rango ",
                minimumResultsForSearch: -1,
                language: {
                    noResults: function() {
                        return "No hay resultado";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                },

            });
        </script>

        <script>
            $("#imprimir_categoria").select2({
                width: "100%",
                language: "es",
                theme: "bootstrap-5",
                allowClear: false,
                dropdownParent: $('#staticBackdrop'),
                placeholder: "Seleccionar categoria ",

                language: {
                    noResults: function() {
                        return "No hay resultado";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                },

            });
        </script>



        <script>
            $("#producto_imagen").autocomplete({
                source: function(request, response) {
                    var url = document.getElementById("url").value;
                    $.ajax({
                        type: "POST",
                        url: url + "/" + "producto/pedido_pos",
                        data: request,
                        success: response,
                        dataType: "json",
                    });
                },
            }, {
                minLength: 1,
            }, {
                select: function(event, ui) {
                    $("#producto_imagen").val(ui.item.value);
                    $("#id_producto_imagen").val(ui.item.id_producto);
                },
            })
        </script>
        <script>
            function saltar(e, id) {
                // Obtenemos la tecla pulsada
                (e.keyCode) ? k = e.keyCode: k = e.which;

                // Si la tecla pulsada es enter (codigo ascii 13)
                if (k == 13) {
                    // Si la variable id contiene "submit" enviamos el formulario
                    if (id == "submit") {
                        document.forms[0].submit();
                    } else {
                        // nos posicionamos en el siguiente input
                        document.getElementById(id).focus();
                    }
                }
            }
        </script>
        <script>
            function busqueda_producto() {
                var fecha_inicial = document.getElementById("fecha_inicial").value;
                var hora_inicial = document.getElementById("hora_inicial").value;
                var fecha_final = document.getElementById("fecha_final").value;
                var hora_final = document.getElementById("hora_final").value;
                var url = document.getElementById("url").value;

                $.ajax({
                    data: {
                        hora_inicial,
                        hora_final,
                        fecha_inicial,
                        fecha_final,
                    },
                    url: url + "/" + "consultas_y_reportes/datos_consultar_producto",

                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            document.getElementById("consultar_producto").submit();

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
                                icon: 'error',
                                title: 'No hay resultados para las fechas especificadas '
                            })
                        }
                    },
                });


            }
        </script>

        <script>
            $(document).ready(function() {
                $('#consulta_producto_por_fecha').DataTable({
                        "ordering": false,
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
                            "search": "Buscar:",
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
                        "order": [
                            [3, "asc"]
                        ]
                    }

                );
            });
        </script>

        <!-- Data table consulta_producto_por_fecha_devolucion -->
        <script>
            $(document).ready(function() {
                $('#consulta_producto_por_fecha_devolucion').DataTable({
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
                            "search": "Buscar:",
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
                        "order": [

                        ]
                    }

                );
            });
        </script>


        <!-- Código interno  -->
        <script>
            function agregar_producto() {
                var url = document.getElementById("url").value;
                $.ajax({
                    url: url + "/" + "producto/get_codigo_interno",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $(function() {
                                $("#crear_producto").on("shown.bs.modal", function(e) {
                                    $("#crear_producto_codigo_de_barras").focus();
                                });
                            });
                            $("#crear_producto").modal("show");
                            $("#crear_producto_codigo_interno").val(resultado.codigo_interno_producto);

                        }
                    },
                });
            }
        </script>

        <!-- Selects -->
        <script>
            $(document).ready(function() {
                var url = document.getElementById("url").value;
                $("#categoria_producto").select2({
                    width: "100%",
                    //placeholder: "Buscar el tipo de documento",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    //minimumResultsForSearch: -1,
                    //minimumResultsForSearch: Infinity,
                    dropdownParent: $('#crear_producto'),
                    language: {
                        noResults: function() {
                            return "No hay resultado";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },
                    /*       ajax: {
                              //url: "tipos_formulacion",
                              url: url + "/producto/categorias",
                              type: "post",
                              dataType: 'json',
                              delay: 200,
                              data: function(params) {
                                  return {
                                      palabraClave: params.term // search term
                                  };
                              },
                              processResults: function(response) {
                                  return {
                                      results: response
                                  };
                              },
                              cache: true
                          } */
                });

                $("#marca_producto").select2({
                    width: "100%",
                    //placeholder: "Buscar el tipo de documento",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $('#crear_producto'),
                    language: {
                        noResults: function() {
                            return "No hay resultado";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },


                });
                $("#select_imp").select2({
                    width: "100%",
                    placeholder: "Selecionar impuesto ",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $('#crear_producto'),
                    minimumResultsForSearch: Infinity // Esto quita el buscador


                });
                $("#opci_imp").select2({
                    width: "100%",
                    placeholder: "Impuesto ",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $('#crear_producto'),
                    minimumResultsForSearch: Infinity, // Esto quita el buscador
                    language: {
                        noResults: function() {
                            return "No hay seleccionado tipo de impuesto ";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },


                });

                $("#categoria_product").select2({
                    width: "50%",
                    placeholder: "Buscar categoria  ",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $('#crear_producto'),

                    language: {
                        noResults: function() {
                            return "No hay seleccionado tipo de impuesto ";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },


                });



                $("#valor_iva").select2({
                    width: "100%",
                    placeholder: "Porcentaje de iva ",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: true,
                    dropdownParent: $('#crear_producto'),
                    //minimumResultsForSearch: -1,
                    language: {
                        noResults: function() {
                            return "No hay resultado";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },
                    ajax: {
                        //url: "tipos_formulacion",
                        url: url + "/producto/iva",
                        type: "post",
                        dataType: 'json',
                        delay: 200,
                        data: function(params) {
                            return {
                                palabraClave: params.term // search term
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });



                $("#valor_ico").select2({
                    width: "100%",
                    placeholder: "% De impuesto al consumo",
                    language: "es",
                    theme: "bootstrap-5",
                    allowClear: false,
                    dropdownParent: $('#crear_producto'),
                    minimumResultsForSearch: -1,
                    language: {
                        noResults: function() {
                            return "No hay resultado";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    },
                    ajax: {
                        //url: "tipos_formulacion",
                        url: url + "/producto/ico",
                        type: "post",
                        dataType: 'json',
                        delay: 200,
                        data: function(params) {
                            return {
                                palabraClave: params.term // search term
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });




            });
        </script>

        <!-- Data table listado de productos -->
        <script>
            $(document).ready(function() {
                var url = document.getElementById("url").value;
                $('#example').DataTable({
                    serverSide: true,
                    processing: true,
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
                        "search": "Buscar:",
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
                    "order": [
                        [0, 'desc']
                    ],
                    ajax: {
                        url: url + '/producto/index',
                        type: 'post',
                    },
                    "aoColumnDefs": [{
                            "bSortable": false,
                            "aTargets": [4]
                        },

                    ]
                });
            });
        </script>

        <!-- Formato de valor costo -->
        <script>
            const valor_costo = document.querySelector("#valor_costo_producto");

            function formatNumber(n) {
                n = String(n).replace(/\D/g, "");
                return n === "" ? n : Number(n).toLocaleString('es-CO')
            }
            valor_costo.addEventListener("keyup", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = formatNumber(value);
            });

            /**
             * Formato valor venta 
             */
            const valor_venta = document.querySelector("#valor_venta_producto");

            function formatNumber(n) {
                n = String(n).replace(/\D/g, "");
                return n === "" ? n : Number(n).toLocaleString('es-CO')
            }
            valor_venta.addEventListener("keyup", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = formatNumber(value);
            });
        </script>

        <!--    <script>
            const precio_3 = document.querySelector("#precio_3");

            function formatNumber(n) {
                n = String(n).replace(/\D/g, "");
                return n === "" ? n : Number(n).toLocaleString('es-CO')
            }
            precio_3.addEventListener("keyup", (e) => {
                const element = e.target;
                const value = element.value;
                element.value = formatNumber(value);
            });
        </script>
 -->



        <!-- Pasar con enter  -->
        <script>
            function saltar_creacion_producto(e, id) {
                // Obtenemos la tecla pulsada

                e.keyCode ? (k = e.keyCode) : (k = e.which);

                // Si la tecla pulsada es enter (codigo ascii 13)

                if (k == 13) {
                    // Si la variable id contiene "submit" enviamos el formulario

                    if (id == "submit") {
                        document.forms[0].submit();
                    } else {
                        // nos posicionamos en el siguiente input

                        document.getElementById(id).focus();
                    }
                }
            }
        </script>

        <!--Crear producto -->
        <script>
            $('#producto_agregar').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btn_crear_producto");
                button.disabled = false;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        button.disabled = false;
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            if (data.code == 1) {
                                $("#crear_producto").modal("hide");
                                $("#div_sub_categoria").hide();

                                $(".crear_producto_nombre_error").html("");
                                $(".categoria_producto_error").html("");
                                $(".marca_producto_error").html("");
                                $(".informacion_tributaria_error").html("");
                                $(".valor_costo_producto_error").html("");
                                $(".valor_venta_producto_error").html("");
                                $('#categoria_producto').val(null).trigger('change');
                                $('#marca_producto').val(null).trigger('change');
                                $("#favorito").val("false");
                                $("#conf_fav").html(data.favorito);
                                $("#select_imp").html(data.select_info_tri);
                                $("#opc_imp").html(data.select_info_tri);
                                $("#tributo_producto").html("Tipo impuesto");
                                $("#categoria_product").html(data.categorias);



                                var favorito = document.getElementById("favorito");
                                var favoritoBtn = document.getElementById("favorito-btn");

                                if (favoritoBtn.classList.contains("btn-warning")) {
                                    favoritoBtn.classList.remove("btn-warning");
                                    favoritoBtn.classList.add("btn-outline-warning");
                                    favorito.value = "false";
                                } else if (favoritoBtn.classList.contains("btn-outline-warning")) {
                                    favoritoBtn.classList.remove("btn-outline-warning");
                                    favoritoBtn.classList.add("btn-warning");
                                    favorito.value = "true";
                                }


                                /*  var informacion_tribuitaria = document.getElementById("informacion_tributaria").value;
                                 if (informacion_tribuitaria == 2) {
                                     ico.style.display = 'block';
                                     iva.style.display = 'none';
                                 } */
                                $(form)[0].reset();

                                var buttonElement = document.getElementById("btn_crear_producto");
                                // Cambia el tipo del botón a "submit"
                                buttonElement.type = "button";



                                table = $('#example').DataTable();
                                table.draw();
                                //$('#countries-table').DataTable().ajax.reload(null, false);
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
                                    title: 'Producto creado exitosamente '
                                })
                            } else {
                                alert(data.msg);
                            }
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val);
                            });
                        }
                    }
                });
            });
        </script>

        <script>
            function minusculasAmayusculas() {
                var x = document.getElementById("crear_producto_nombre");
                x.value = x.value.toUpperCase();
            }
        </script>



        <!--Editar producto -->
        <script>
            function editar_producto(id_producto) {
                var url = document.getElementById("url").value;
                $.ajax({
                    url: url + "/" + "producto/editar_precios",
                    type: "POST",
                    data: {
                        id_producto
                    },
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $(function() {
                                $("#edicion_de_producto").on("shown.bs.modal", function(e) {
                                    $("#crear_producto_codigo_de_barras").focus();
                                });
                            });
                            $("#edicion_de_producto").modal("show");
                            $("#vista_edicion_precio").html(resultado.edicion_producto);
                        }
                    },
                });

            }
        </script>

        <!--Eliminar producto -->
        <script>
            function eliminar_producto(id_producto) {
                var url = document.getElementById("url").value;
                $.ajax({
                    url: url + "/" + "producto/eliminar_producto_inventario",
                    type: "POST",
                    data: {
                        id_producto
                    },
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) { //El producto no  tiene movimientos 
                            Swal.fire({
                                text: "¿Realmente desea eliminar " + resultado.nombre_producto + "?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#2AA13D',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Si, borrar!',
                                cancelButtonText: 'Cancelar',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    var codigo_interno_producto = resultado.codigo_interno_producto
                                    $.ajax({
                                        data: {
                                            codigo_interno_producto,
                                        },
                                        url: url + "/" + "producto/borrar_producto_inventario",
                                        type: "POST",
                                        success: function(resultado) {
                                            var resultado = JSON.parse(resultado);

                                            if (resultado.resultado == 1) {

                                                table = $('#example').DataTable();
                                                table.draw();


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
                                                    title: 'Borrado de producto éxitoso'
                                                })
                                            }
                                        },
                                    });
                                }
                            })

                        }

                        if (resultado.resultado == 0) {

                        }
                    },
                });

            }
        </script>




        <script>
            let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
            let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
            if (mensaje != "") {

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
                    icon: iconoMensaje,
                    title: mensaje
                })

            }
        </script>
        <!--Activar o desactivar la resolucion de facturación -->
        <script>
            function estado_resolucion(id_resolucion) {


                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_resolucion,
                    },
                    url: url + "/" + "empresa/activacion_resolucion_facturacion",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            $("#datos_resolucion").html(resultado.texto)
                            $("#ultima_factura").val(resultado.numero_factura)
                            $("#prefijo_factura_dian").val(resultado.prefijo)
                            $("#id_dian_guardar").val(resultado.id_dian)

                            $("#activar_resolucion_facturacion").modal("show");


                        }

                    },
                });
            }
        </script>

        <!--Cargar resolución -->
        <script>
            $('#guardar_resolucion_facturacion').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btnguardar_resolucion_facturacion");
                button.disabled = true;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        button.disabled = false;
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            if (data.code == 1) {
                                $("#resolucion_facturacion").modal("hide");
                                $('#resoluciones_de_facturacion').html(data.resoluciones)
                                $(form)[0].reset();

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
                                    title: 'Resolución de facturación creada exitosamente '
                                })
                            } else {
                                alert(data.msg);
                            }
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val);
                            });
                        }
                    }
                });
            });
        </script>


        <!--Llenado de select de muncipios en el formulario de edicion de empresa  -->
        <script>
            function buscar_municipios(id_departamento) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_departamento
                    },
                    url: url + "/" + "empresa/municipios",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);
                        if (resultado.resultado == 1) {
                            $("#municipio").html(resultado.municipios);

                        }
                    },
                });
            }
        </script>
        <!--/Llenado de select de muncipios en el formulario de edicion de empresa  -->

        <!--Foco en el primer campo del modal de resolucion de facturacion-->
        <script>
            $("#resolucion_facturacion").on("shown.bs.modal", function() {
                $(this).find("#numero_dian").focus();
            });
        </script>
        <!-- /Foco en el primer campo del modal de resolucion de facturacion-->



        <!--Editar resolucion trae el formulario con los datos de la resolución -->
        <script>
            function editar_resolucion(id_resolucion) {
                var url = document.getElementById("url").value;
                $.ajax({
                    data: {
                        id_resolucion
                    },
                    url: url + "/" + "empresa/datos_resolucion_facturacion",
                    type: "POST",
                    success: function(resultado) {
                        var resultado = JSON.parse(resultado);

                        if (resultado.resultado == 1) {
                            $('#datos_de_la_resolucion').html(resultado.resolucion)
                            myModal = new bootstrap.Modal(
                                document.getElementById("editar_resolucion_facturacion"), {}
                            );
                            myModal.show();

                        }

                    },
                });


            }
        </script>

        <!-- /Editar resolucion trae el formulario con los datos de la resolución -->




        <!--Actualizar los datos de las resolucion de facturacion -->
        <script>
            $('#edicion_de_resolucion_facturacion').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btneditar_resolucion_facturacion");
                button.disabled = true;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        button.disabled = false;
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            if (data.code == 1) {
                                $("#edicion_de_producto").modal("hide");
                                // $('#tipo_de_identificacion').val(null).trigger('change');
                                $(form)[0].reset();
                                table = $('#consulta_producto').DataTable();
                                table.draw();
                                //$('#countries-table').DataTable().ajax.reload(null, false);
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
                                    title: 'Producto actualizado exitosamente '
                                })
                            } else {
                                alert(data.msg);
                            }
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val);
                            });
                        }
                    }
                });
            });
        </script>

        <script>
            function cambiar_informacion_tributaria() {
                var estado = $("#editar_informacion_tributaria").val();

                var iva = document.getElementById("editar_impuesto_al_valor_agregado");
                var ico = document.getElementById("editar_impuesto_al_conusmo_nacional");

                if (estado == 1) {
                    iva.style.display = "none";
                    ico.style.display = "block";
                }
                if (estado == 2) {
                    iva.style.display = "block";
                    ico.style.display = "none";
                }
            }
        </script>

        <!--Crear categoria -->
        <script>
            $('#crear_categoria').submit(function(e) {
                e.preventDefault();
                var form = this;
                let button = document.querySelector("#btn_crear_categoria");
                button.disabled = true;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                        button.disabled = false;
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            if (data.code == 1) {

                                $("#categoria_product").html(data.categorias)
                                $("#agregar_categoria").modal("hide");
                                $("#crear_producto").modal("show");

                                $(form)[0].reset();
                                // table = $('#example').DataTable();
                                // table.draw();
                                //$('#countries-table').DataTable().ajax.reload(null, false);
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
                                    title: 'Categoria creada exitosamente '
                                })
                            } else {
                                alert(data.msg);
                            }
                        } else {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val);
                            });
                        }
                    }
                });
            });
        </script>

        <script>
            function cambiar_estado() {
                var estado = document.getElementById("editar_impresion_en_comanda").value;

                if (estado == "false") {
                    document.getElementById("editar_impresion_en_comanda").value = 'true'
                }


            }
        </script>


        <script>
            $(function() {
                $("#activar_resolucion_facturacion").on("shown.bs.modal", function(e) {
                    $("#continuacion_factura").focus();
                });
            });
        </script>

</body>

</html>