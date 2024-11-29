<!-- Modal -->
<div class="modal fade" id="pin_edicion_cantidades" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center ">
                    <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= base_url() ?>/Assets/img/logo.png" height="36" alt=""></a>

                    <h2>Inicio de sesion</h2>
                    <form id="formulario">
                        <input type="password" id="password" name="pin" class="input" maxlength="4" /></br>
                        <input type="hidden" id="id_edicion_producto_pedido" name="id_edicion_producto_pedido" class="input" maxlength="4" /></br>
                        <div class="text-danger"><?= session('errors.pin') ?></div>
                        </br>
                        <input type="button" value="1" id="1" class="pinButton calc" />
                        <input type="button" value="2" id="2" class="pinButton calc" />
                        <input type="button" value="3" id="3" class="pinButton calc" /><br>
                        <input type="button" value="4" id="4" class="pinButton calc" />
                        <input type="button" value="5" id="5" class="pinButton calc" />
                        <input type="button" value="6" id="6" class="pinButton calc" /><br>
                        <input type="button" value="7" id="7" class="pinButton calc" />
                        <input type="button" value="8" id="8" class="pinButton calc" />
                        <input type="button" value="9" id="9" class="pinButton calc" /><br>
                        <input type="button" value="RESET" id="clear" class="pinButton clear" />
                        <input type="button" value="0" id="0 " class="pinButton calc" />
                        <input type="button" value="ENTER" id="enter" class="pinButton enter" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>