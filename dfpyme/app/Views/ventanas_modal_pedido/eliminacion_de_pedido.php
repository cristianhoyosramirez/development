<style>
    .contenedor {
        height: 55vh;
        overflow: scroll;
    }

    .contenedorProductos {
        height: 30vh;
        overflow: scroll;
    }

    .div {
        background-color: #FBD603;
    }

    .boton-subir {
        position: fixed;
        right: 5%;
        bottom: 10px;
        background-color: #17181C;
        padding: 20px;
        display: none;
        opacity: 0.7
    }

    .boton-subir a {
        font-size: 24px;
        color: #fff;

    }


    .otp-input-wrapper {
        width: 240px;
        text-align: center;
        display: inline-block;
    }

    .otp-input-wrapper input {
        padding: 0;
        width: 264px;
        font-size: 32px;
        font-weight: 600;
        color: #3e3e3e;
        background-color: transparent;
        border: 0;
        margin-left: 12px;
        letter-spacing: 48px;
        font-family: sans-serif !important;
    }

    .otp-input-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    .otp-input-wrapper svg {
        position: relative;
        display: block;
        width: 240px;
        height: 2px;
    }
</style>


<!-- Modal -->
<div class="modal fade" id="eliminacion_de_pedido"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p class="text-primary">ELIMINACIÓN DE PEDIDO CON PIN </p>
                <div class="text-center ">
                    <form id="formulario" action="<?php echo base_url('pedido/borrar_pedido'); ?>" method="post">

                       <input type="hidden" id="id_borrar_pedido" name="id_borrar_pedido">

                        <div class="otp-input-wrapper">

                            <input type="text" maxlength="4" pattern="[0-9]*" autocomplete="off" autofocus id="auth" name="pin" onkeyup="pin_login(event, this.value)">
                            <svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
                                <line x1="0" y1="0" x2="240" y2="0" stroke="#3e3e3e" stroke-width="2" stroke-dasharray="44,22" />
                            </svg>
                        </div>
                        <div class="text-danger"><?= session('errors.pin') ?></div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>