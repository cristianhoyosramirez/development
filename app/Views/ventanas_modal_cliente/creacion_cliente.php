<!-- Modal -->
<div class="modal fade" id="crear_cliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3">

                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Cedula</label>
                        <input type="text" class="form-control" id="identificacion_cliente" onkeyup="saltar_cliente(event,'nombres_cliente_pedido');" onkeypress="return soloNumeros(event)">
                        <span id="error_identificacion"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="inputPassword4" class="form-label">Nombre y apellidos </label>
                        <input type="text" class="form-control" id="nombres_cliente_pedido" onkeyup="saltar_cliente(event,'regimen_cliente_pedido');">
                        <span id="error_nombres_cliente_pedido"></span>
                    </div>
                    <div class="col-4">
                        <label for="inputAddress" class="form-label">Régimen</label>
                        <select class="form-select select2" name="regimen_cliente_pedido" id="regimen_cliente_pedido" onkeyup="saltar_cliente(event,'tipo_cliente_pedido');">
                            <?php foreach ($regimen as $detalle) { ?>
                                <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $id_regimen) : ?>selected <?php endif; ?>><?php echo $detalle['nombreregimen'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="inputAddress2" class="form-label">Tipo</label>
                        <select class="form-select select2" name="tipo_cliente_pedido" id="tipo_cliente_pedido" onkeyup="saltar_cliente(event,'clasificacion_cliente_pedido');">
                            <?php foreach ($tipo_cliente as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Clasificación</label>
                        <select id="clasificacion_cliente_pedido" name="clasificacion_cliente_pedido" class="form-select" onkeyup="saltar_cliente(event,'telefono_cliente_pedido');">
                            <?php foreach ($clasificacion_cliente as $detalle) { ?>
                                <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['descripcion'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Teléfono</label>
                        <input type="text" name="telefono_cliente_pedido" id="telefono_cliente_pedido" data-mask="(000) 000-0000" data-mask-visible="true" placeholder="(00) 0000-0000" autocomplete="off" class="form-control" onkeyup="saltar_cliente(event,'celular_cliente_pedido');">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Célular</label>
                        <input type="text" id="celular_cliente_pedido" data-mask="(000) 000-0000" data-mask-visible="true" placeholder="(00) 0000-0000" name="celular_cliente_pedido" class="form-control" onkeyup="saltar_cliente(event,'e-mail_pedido');">
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">E-mail</label>
                        <input type="text" onkeydown="validar_email()" id="e-mail_pedido" name="e-amail_pedido" class="form-control" onkeyup="saltar_cliente(event,'departamento_pedido');">
                        <span id="error_email"></span>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Departamento</label>
                        <select id="departamento_pedido" name="departamento_pedido" class="form-select" onkeyup="saltar_cliente(event,'municipios_pedido');">
                            <?php foreach ($departamentos as $detalle) { ?>
                                <option value="0"></option>
                                <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $id_departamento) : ?>selected <?php endif; ?>  ><?php echo $detalle['nombredepartamento'] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Ciudad</label>
                        <select id="municipios_pedido" name="municipios_pedido" class="form-select" onkeyup="saltar_cliente(event,'direccion_cliente_pedido');">
                            <option value="0"></option>
                            <option value="<?php echo $id_ciudad ?>" selected><?php echo $ciudad ?></option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputState" class="form-label">Dirección</label>
                        <input type="text" id="direccion_cliente_pedido" name="direccion_cliente_pedido" class="form-control" onkeyup="saltar_cliente(event,'agregar_cliente_pedido');">
                    </div>
                </form>


            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-success" id="agregar_cliente_pedido" onclick="agregar_cliente_pedido()">Crear cliente</a>
                <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</a>
            </div>
        </div>
    </div>
</div>