<form class="row g-2" action="<?= base_url('empresa/actualizar_resolucion_facturacion') ?>" method="POST" id="edicion_de_datos_resolucion_facturacion">
    <input type="hidden" name="id_resolucion" id="id_resolucion" value="<?php echo $id_resolucion ?>">
    <div class="col-md-3">
        <label for="inputEmail4" class="form-label">Número de resolución</label>
        <input type="text" class="form-control" id="numero_dian" name="numero_dian" value="<?php echo $datos_resolucion['numeroresoluciondian'] ?>">
        <span class="text-danger error-text numero_dian_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputPassword4" class="form-label">Fecha de expedición</label>
        <input type="date" class="form-control" id="fecha_dian" name="fecha_dian" value="<?php echo $datos_resolucion['fechadian'] ?>">
        <span class="text-danger error-text fecha_dian_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputPassword4" class="form-label">Fecha de caducidad</label>
        <input type="date" class="form-control" id="fecha_caducidad" name="fecha_caducidad" value="<?php echo $datos_resolucion['vigencia'] ?>">
        <span class="text-danger error-text fecha_caducidad_error"></span>
    </div>
    <div class="col-3">
        <label for="inputAddress" class="form-label">Modalidad</label>
        <select class="form-select" aria-label="Default select example" id="modalidad_dian" name="modalidad_dian">
            <?php if ($datos_resolucion['id_modalidad'] == 1) { ?>
                <option value="1" selected>Computador</option>
                <option value="2">Electrónica</option>
            <?php } ?>
            <?php if ($datos_resolucion['id_modalidad'] == 2) { ?>
                <option value="1">Computador</option>
                <option value="2" selected>Electrónica</option>

            <?php } ?>
        </select>
        <span class="text-danger error-text modalidad_dian_error"></span>
    </div>

    <div class="col-3">
        <label for="inputAddress2" class="form-label">Prefijo </label>
        <input type="text" class="form-control" id="prefijo_dian" name="prefijo_dian"  value="<?php  echo $datos_resolucion['inicialestatica']?>">
        <span class="text-danger error-text prefijo_dian_error"></span>
    </div> 

    <div class="col-md-3">
        <label for="inputCity" class="form-label">Desde el número </label>
        <input type="text" class="form-control" id="numero_inicial" name="numero_inicial" value="<?php  echo $datos_resolucion['rangoinicialdian']?>">
        <span class="text-danger error-text numero_inicial_error"></span>
    </div>

    <div class="col-3">
        <label for="inputAddress2" class="form-label">Hasta el núemro </label>
        <input type="text" class="form-control" id="numero_final" name="numero_final" value="<?php  echo $datos_resolucion['rangofinaldian']?>">
        <span class="text-danger error-text numero_final_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Vigencia</label>
        <input type="text" class="form-control" id="vigencia" name="vigencia" value="<?php  echo $datos_resolucion['vigencia_mes']?>">
        <span class="text-danger error-text vigencia_error"></span>
    </div>

    <div class="col-md-4">
        <label for="inputCity" class="form-label">Texto inicial </label>
        <input type="text" class="form-control" id="texto_inicial" name="texto_inicial" value="<?php  echo $datos_resolucion['texto_inicial']?>">
        <span class="text-danger error-text texto_inicial_error"></span>
    </div>
    <div class="col-md-4">
        <label for="inputCity" class="form-label">Tipo de solicitud </label>
        <input type="text" class="form-control" id="tipo_de_solicitud" name="tipo_de_solicitud" value="<?php  echo $datos_resolucion['texto_final']?>">
        <span class="text-danger error-text tipo_de_solicitud_error"></span>
    </div>
    <div class="col-md-4">
        <label for="inputCity" class="form-label">Alertar cuando falten </label>
        <input type="text" class="form-control" id="alerta" name="alerta" value="<?php  echo $datos_resolucion['alerta_facturacion']?>">
        <span class="text-danger error-text alerta_error"></span>
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success" id="btncambiar_resolucion_facturacion">Guardar </button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
    </div>
</form>

<!-- actualizar la resolucion de facturación -->
<script>
    $('#edicion_de_datos_resolucion_facturacion').submit(function(e) {
        e.preventDefault();
        var form = this;
        let button = document.querySelector("#btncambiar_resolucion_facturacion");
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
                        $("#editar_resolucion_facturacion").modal("hide");
                        $("#resoluciones_de_facturacion").html(data.resoluciones)
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
                            title: 'Resolución de facturación actualizada exitosamente '
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
<!--/actualizar la resolucion de facturación-->