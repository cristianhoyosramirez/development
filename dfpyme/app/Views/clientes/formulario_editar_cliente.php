<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<form class="row g-3" id="edicion_cliente_electronico" method="POST" action="<?php echo base_url() ?>/clientes/actualizar_datos_cliente">

    <input type="hidden" id="id_cliente" value="<?php echo $id_cliente ?>" name="id_cliente">

    <div class="col-md-2">
        <label for="inputEmail4" class="form-label">Tipo de persona</label>
        <select class="form-select" id="tipo_of_persona" name="tipo_depersona">
            <?php foreach ($tipos_persona as $detalle) :  ?>
                <option value="<?php echo $detalle['codigo'] ?>" <?php if ($detalle['codigo'] == $datos_cliente['type_person']) : ?>selected <?php endif; ?>><?php echo $detalle['descripcion'] ?> </option>
            <?php endforeach ?>
        </select>
        <span class="text-danger error-text tipo_persona_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputPassword4" class="form-label">
            Tipo de documento
        </label>
        <?php $identificacion = model('TiposDocumento')->findALL(); ?>
        <select class="form-select" aria-label="Default select example" id="tipo_de_documento" name="tipo_documento">
            <?php foreach ($identificacion as $detalle) : ?>

                <option value="<?php echo $detalle['codigo'] ?>" <?php if ($detalle['codigo'] == $datos_cliente['type_document']) : ?>selected <?php endif; ?>><?php echo $detalle['descripcion'] ?> </option>

            <?php endforeach  ?>
        </select>
    </div>
    <div class="col-3">
        <label for="inputAddress" class="form-label">Número de identificación </label>
        <input type="text" class="form-control" id="identificacion" name="identificacion" onkeyup="saltar_factura_pos(event,'dv')" value="<?php echo  $datos_cliente['nitcliente'] ?>">
        <span class="text-danger error-text identificacion_error"></span>
    </div>
    <div class="col-1">
        <label for="inputAddress2" class="form-label">DV</label>
        <input type="text" class="form-control" id="dv" name="dv" onkeyup="saltar_factura_pos(event,'nombres')" value="<?php echo  $datos_cliente['dv'] ?>">
        <span class="text-danger error-text dv_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Régimen</label>
        <?php $regimen = model('regimenModel')->findALL(); ?>
        <select class="form-select" aria-label="Default select example" id="regimen_cliente" name="regimen">
            <?php foreach ($regimen as $detalle) : ?>

                <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $datos_cliente['idregimen']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreregimen'] ?> </option>

            <?php endforeach  ?>
        </select>

    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Tipo de cliente </label>

        <?php $tipo_cliente = model('tipoClienteModel')->findALL(); ?>
        <select class="form-select" aria-label="Default select example" name="tipo_ventas_cliente" id="tipo_ventas_cliente">
            <?php foreach ($tipo_cliente as $detalle) : ?>

                <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $datos_cliente['idtipo_cliente']) : ?>selected <?php endif; ?>><?php echo $detalle['descripcion'] ?> </option>
            <?php endforeach  ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Clasificación comercial
        </label>

        <?php $clasificacion_cliente = model('clasificacionClienteModel')->findALL(); ?>
        <select class="form-select" aria-label="Default select example" name="clasificacion" id="clasificacion_cliente">
            <?php foreach ($clasificacion_cliente as $detalle) : ?>

                <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $datos_cliente['id_clasificacion']) : ?>selected <?php endif; ?>><?php echo $detalle['descripcion'] ?> </option>

            <?php endforeach  ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Nombres
        </label>
        <input type="text" class="form-control" id="nombres" name="nombres" onkeyup="saltar_factura_pos(event,'apellidos')" value="<?php echo  $datos_cliente['name'] ?>">
        <span class="text-danger error-text nombres_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Apellidos
        </label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" onkeyup="saltar_factura_pos(event,'razon_social')" value="<?php echo  $datos_cliente['last_name'] ?>">
        <span class="text-danger error-text apellidos_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Razón social
        </label>
        <input type="text" class="form-control" id="razon_social" name="razon_social" onkeyup="saltar_factura_pos(event,'nombre_comercial')" value="<?php echo  $datos_cliente['nombrescliente'] ?>">
        <span class="text-danger error-text razon_social_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Nombre comercial
        </label>
        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" onkeyup="saltar_factura_pos(event,'direccion')" value="<?php echo  $datos_cliente['name_comercial'] ?>">
        <span class="text-danger error-text nombre_comercial_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Dirección
        </label>
        <input type="text" class="form-control" id="direccion" name="direccion" onkeyup="saltar_factura_pos(event,'departamento')" value="<?php echo  $datos_cliente['direccioncliente'] ?>">
        <span class="text-danger error-text direccion_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Departamento
        </label>
        <?php $departamento = model('departamentoModel')->where('idpais', 49)->findAll(); ?>
        <select class="form-select" name="departamento_cliente" id="departamento_cliente" onchange="departamentoCiudadEditar()">
            <?php foreach ($departamento as $detalle) : ?>

                <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $id_departamento) : ?>selected <?php endif; ?>><?php echo $detalle['nombredepartamento'] . "-" . $detalle['code']  ?> </option>
            <?php endforeach  ?>
        </select>
        <span class="text-danger error-text departamento_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Ciudad
        </label>
        <?php $ciudad = model('ciudadModel')->where('iddepartamento', $id_departamento)->orderBy('nombreciudad', 'asc')->findAll();  ?>

        <select class="form-select" id="ciudad_cliente_edicion" name="ciudad_edicion">
            <?php foreach ($ciudad as $detalle) : ?>

                <option value="<?php echo $detalle['idciudad'] ?>" <?php if ($detalle['idciudad'] == $datos_cliente['idciudad']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreciudad']   ?> </option>
            <?php endforeach ?>

        </select>
        <span class="text-danger error-text ciudad_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Confirmar ciudad
        </label>


        <select class="form-select" name="municipios" id="confirmar_municipios_cliente">
            <?php foreach ($ciudad as $detalle) : ?>

                <option value="<?php echo $detalle['idciudad'] ?>" <?php if ($detalle['idciudad'] == $datos_cliente['idciudad']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreciudad']   ?> </option>
            <?php endforeach ?>
        </select>
        <span class="text-danger error-text municipios_error"></span>
    </div>

    <div class="col-md-3">
        <label for="inputCity" class="form-label">Código postal
        </label>
        <?php $postal = model('CodigoPostalModel')->findAll();  ?>

        <select class="form-select" id="codigo_postal_cliente" name="codigo_postal_editar">

            <?php foreach ($postal as $detalle) { ?>
                <option value="<?php echo $detalle['c_postal'] ?>" <?php if ($detalle['id'] == $codigo_postal) : ?>selected <?php endif; ?>><?php echo $detalle['ciudad'] . "-" . $detalle['departamento'] . "-" . $detalle['c_postal'] ?> </option>
            <?php } ?>

        </select>
        <span class="text-danger error-text codigo_postal_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Correo electrónico
        </label>
        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo  $datos_cliente['emailcliente'] ?>">
        <span class="text-danger error-text correo_electronico_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Teléfono /celular
        </label>
        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo  $datos_cliente['celularcliente'] ?>">
        <span class="text-danger error-text telefono_error"></span>
    </div>
    <div class="col-md-3">
        <label for="inputCity" class="form-label">Detalles tributarios del cliente </label>
        <?php $impuestos = model('impuestosModel')->where('estado', 'true')->findAll();  ?>
        <select name="impuestos_cliente" id="impuestos_cliente">
            <?php foreach ($impuestos as $detalle) { ?>

                <?php if (!empty($detalles_tributarios)) : ?>
                    <option value="<?php echo $detalle['codigo'] ?>" <?php if ($detalles_tributarios['codigo'] == $detalle['codigo']) : ?>selected <?php endif; ?>><?php echo $detalle['codigo'] . "_" . $detalle['nombre'] . "_" . $detalle['descripcion'] ?> </option>
                <?php endif ?>
                <?php if (empty($detalles_tributarios)) : ?>
                    <option value="">Seleccione un valor</option>
                    <option value="<?php echo $detalle['codigo'] ?>"><?php echo $detalle['codigo'] . "_" . $detalle['nombre'] . "_" . $detalle['descripcion'] ?> </option>
                <?php endif ?>



            <?php } ?>
        </select>
        <span class="text-danger error-text impuestos_error"></span>
    </div>

    <div class="col-md-3">
        <label for="inputCity" class="form-label">Valores de la casilla 53 o 54 de RUT
        </label>
        <?php $responsabilidad_fiscal = model('responsabilidadFiscalModel')->where('estado', 'true')->findAll();  ?>

        <select name="responsabilidad_fiscal[]" id="responsabilidad_fiscal_cliente" multiple="multiple">
            <?php foreach ($responsabilidad_fiscal as $detalle) { ?>
                <option value="<?php echo $detalle['codigo'] ?>"><?php echo $detalle['descripcion'] ?></option>


                <?php foreach ($detalles_rut as $detalle_rut) : ?>

                    <option value="<?php echo $detalle['codigo'] ?>" <?php if ($detalle['codigo'] == $detalle_rut['codigo']) : ?>selected <?php endif; ?>><?php echo $detalle['codigo'] . "_" . $detalle['descripcion'] ?> </option>

                <?php endforeach ?>

            <?php } ?>
        </select>
        <span class="text-danger error-text responsabilidad_fiscal_error"></span>
    </div>

    <div class="modal-footer">
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-outline-success w-100" id="btn_editar_cliente">Actualizar </button>
            </div>
            <div class="col">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>

</form>



<!-- JQuery 
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>-->
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>
<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/select_2.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/departamentoCiudad.js"></script>

<script>
    function departamentoCiudadEditar() {

var url = document.getElementById("url").value;
var valorSelect1 = document.getElementById("departamento_cliente").value;


$.ajax({
  data: {
    valorSelect1

  },
  url: url +
    "/" +
    "eventos/municipios",
  type: "post",
  success: function(resultado) {
    var resultado = JSON.parse(resultado);
    if (resultado.resultado == 1) {

       
       $('#ciudad_cliente_edicion').html(resultado.ciudad)
       $('#confirmar_municipios_cliente').html(resultado.ciudad)
       //$('#municipios').html(resultado.municipios)

    }

    if (resultado.resultado == 0) {
      alert('No se puede actualizar ')
    }
  },
});



}
</script>


<script>
    $("#tipo_of_persona").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#tipo_de_documento").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#regimen_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#departamento_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#regimen_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#ciudad_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#confirmar_municipios_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#codigo_postal_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#impuestos_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#responsabilidad_fiscal_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#tipo_ventas_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#clasificacion_cliente").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
    $("#").select2({
        width: "100%",
        //placeholder: "Filtrar productos por categoria",
        language: "es",
        theme: "bootstrap-5",
        allowClear: true,
        dropdownParent: $("#editar_cliente"),
        closeOnSelect: true
    });
</script>



<script>
    $('#edicion_cliente_electronico').submit(function(e) {
        e.preventDefault();
        var form = this;
        let button = document.querySelector("#btn_editar_cliente");
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
                            title: 'Datos de cliente actualizado correctamente  '
                        });

                        // Recargar la página después de 3 segundos (3000 milisegundos)
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
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