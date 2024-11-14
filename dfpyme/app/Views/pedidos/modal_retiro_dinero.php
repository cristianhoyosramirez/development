<!-- Modal -->
<div class="modal fade" id="modal_retiro_dinero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Retiro de dinero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <input type="hidden" id="url" value="<?php echo base_url() ?>">
                    <?php $cuentas = model('cuentaRetiroModel')->orderBy('id', 'asc')->findAll(); ?>
                    <div class="col-md-6">
                        <label for="inputState" class="form-label">Cuenta de gasto </label>
                        <select id="cuenta_retiro" class="form-select" name="cuenta_retiro" id="cuenta_retiro" onchange="rubros_retiro()" onkeyup="saltar_factura_pos(event,'cuenta_rubro')">

                            <?php foreach ($cuentas as $detalle) { ?>

                                <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == 1) : ?>selected <?php endif; ?>><?php echo $detalle['nombre_cuenta'] ?> </option>

                            <?php } ?>
                        </select>
                        <span id="error_cuenta_rubro" style="color:#FF0000" ;></span>
                    </div>
                    <?php $rubros = model('rubrosModel')->findAll(); ?>
                    <div class="col-md-6">
                        <label for="inputState" class="form-label">Rubro</label>
                        <select id="cuenta_rubro" class="form-select" name="cuenta_rubro">
                            <?php foreach ($rubros as $detalle) : ?>
                                <?php if ($detalle['nombre_rubro'] == "GENERAL") : ?>
                                    <option value="<?php echo $detalle['id_cuenta_retiro'] ?> " selected> <?php echo $detalle['nombre_rubro'] ?></option>
                                <?php endif ?>



                            <?php endforeach ?>
                        </select>
                        <span id="error_cuenta_rubro" style="color:#FF0000" ;></span>
                    </div>

                    <div class="col-md-6">
                        <label for="formGroupExampleInput" class="form-label">Valor</label>
                        <input type="text" class="form-control" id="valor_retiro" onkeyup="saltar_factura_pos(event,'concepto_retiro')">
                        <span id="error_valor_retiro" style="color:#FF0000" ;></span>
                    </div>



                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Concepto</label>
                        <textarea class="form-control" id="concepto_retiro" rows="1"></textarea>
                        <span id="error_concepto_retiro" style="color:#FF0000" ;></span>
                    </div>
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-success" onclick="retiro_efectivo()" id="btn_retiro">Guardar</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function rubros_retiro() {
        var cuenta_retiro = document.getElementById("cuenta_retiro").value;
        var url = document.getElementById("url").value;
        console.log(cuenta_retiro)
        $.ajax({
            data: {
                cuenta_retiro,
            },
            url: url + "/" + "devolucion/cuenta_rubro",
            type: "POST",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);


                if (resultado.resultado == 1) {
                    $('#cuenta_rubro').html(resultado.rubros)
                }
            },
        });


    }
</script>
<!--jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>

<!-- <script>
    $(document).ready(function() {
        // Agregamos un listener de cambio a select1

        var cueta_retiro = document.getElementById("cuenta_retiro").value;

        console.log(cueta_retiro)

        $('#cuenta_retiro').on('change', function() {
            var selectedValue = $(this).val();
            console.log(selectedValue)
            // Realizamos una solicitud AJAX para obtener las opciones
            $.ajax({
                url: 'obtener_opciones.php', // Cambia esto a la URL de tu servidor
                type: 'GET',
                data: {
                    opcion: selectedValue
                },
                dataType: 'json',
                success: function(data) {
                    // Limpiamos las opciones actuales en select2
                    $('#select2').empty();

                    // Llenamos select2 con las opciones recibidas
                    $.each(data, function(key, value) {
                        $('#select2').append($('<option>', {
                            value: value,
                            text: value
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX: ' + status);
                }
            });
        });

        // Disparamos el evento de cambio inicialmente para llenar select2
        $('#select1').trigger('change');
    });
</script> -->