$('#creacion_cliente_electronico').submit(function (e) {
    e.preventDefault();
    var form = this;
    let button = document.querySelector("#btn_crear_cliente");
    button.disabled = false;
    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function () {
            $(form).find('span.error-text').text('');
            button.disabled = false;
        },
        success: function (data) {
            if ($.isEmptyObject(data.error)) {
                if (data.code == 1) {
                    $("#crear_cliente").modal("hide");
                    $("#finalizar_venta").modal("show");
                    $("#nit_cliente").val(data.nit_cliente);
                    $("#nombre_cliente").val(data.cliente);


                    $(form)[0].reset();

                    //$('#listado_de_clientes').html(resultado.clientes);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-start',
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
                        title: 'Cliente agregado a la base de datosdd '
                    })
                } else {
                    alert(data.msg);
                }
            } else {
                $.each(data.error, function (prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val);
                });
            }
        }
    });
});
