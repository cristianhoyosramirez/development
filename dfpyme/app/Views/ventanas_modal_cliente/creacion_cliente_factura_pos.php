<!-- Modal -->
<div class="modal fade" id="creacion_cliente_factura_pos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form class="row g-3" id="formulario_creacion_cliente_factura_pos">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Cedula</label>
                        <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" onkeyup="saltar_cliente(event,'nombres_cliente_pos');">
                         <span id="error_identificacion_factura_pos"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Nombre y apellidos </label>
                        <input type="text" class="form-control" id="nombres_cliente_pos" onkeyup="saltar_cliente(event,'regimen_cliente_pos');">
                        <span id="error_nombres_cliente_factura_pos"></span>
                    </div>
                    <div class="col-4">
                        <label for="inputAddress" class="form-label">Régimen</label>
                        <select class="form-select select2" name="regimen_cliente_pos" onkeyup="saltar_cliente(event,'tipo_cliente_pos');" id="regimen_cliente_pos">
                            <?php foreach ($regimen as $detalle) { ?>
                                <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $id_regimen) : ?>selected <?php endif; ?>><?php echo $detalle['nombreregimen'] ?></option>
                                <option value="<?php echo $detalle['idregimen'] ?>"><?php echo $detalle['nombreregimen'] ?></option>
                                
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="inputAddress2" class="form-label">Tipo</label>
                        <select class="form-select select2" name="tipo_cliente_pos" id="tipo_cliente_pos" onkeyup="saltar_cliente(event,'clasificacion_cliente_pos');">
                            <?php foreach ($tipo_cliente as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Clasificación</label>
                        <select id="clasificacion_cliente_pos" name="clasificacion_cliente_pos" onkeyup="saltar_cliente(event,'telefono_cliente');" class="form-select">
                            <?php foreach ($clasificacion_cliente as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Teléfono</label>
                        <input type="text" name="telefono_cliente" data-mask="(000) 000-0000" onkeyup="saltar_cliente(event,'celular_cliente');" data-mask-visible="true" id="telefono_cliente" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Célular</label>
                        <input type="text" id="celular_cliente" data-mask="(000) 000-0000" data-mask-visible="true" onkeyup="saltar_cliente(event,'e-mail_pedido_pos');" name="celular_cliente" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">E-mail</label>
                        <input type="text" id="e-mail_pedido_pos" onkeydown="validar_email()" onkeyup="saltar_cliente(event,'departamento');" name="ee-mail_pedido_pos" class="form-control">
                        <span id="error_email_factura_pos"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Departamento<?php echo $id_departamento ?></label>
                        <select id="departamento" name="departamento" onkeyup="saltar_cliente(event,'municipios');" class="form-select">
                            <?php foreach ($departamentos as $detalle) { ?>
                                <option value="0"></option>
                                <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $id_departamento) : ?>selected <?php endif; ?>><?php echo $detalle['nombredepartamento'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Ciudad</label>
                        <select id="municipios" onkeyup="saltar_cliente(event,'direccion_cliente');" name="municipios" class="form-select">
                            <option value="<?php echo $id_ciudad ?>" selected><?php echo $ciudad ?></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Dirección</label>
                        <input type="text" id="direccion_cliente" name="direccion_cliente" onkeyup="saltar_cliente(event,'agregar_cliente_pos');" class="form-control">
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-success" onclick="agregar_cliente_pedido_pos()" id="agregar_cliente_pos" onclick="agregar_cliente()" value="Agregar">
                <button type="button" class="btn btn-danger" onclick="limpiar_creacion_cliente()" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>