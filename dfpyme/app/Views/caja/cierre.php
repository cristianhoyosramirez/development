<?php $user_session = session(); ?>
<?= $this->extend('template/caja') ?>
<?= $this->section('title') ?>
CAJA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3 text-center">CIERRE DE CAJA  </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<div class="card container">
    <div class="card-body">

        <form id="formulario_cierre" class="row g-3">
            <div class="col-md-2">
                <label for="inputEmail4" class="form-label">Fecha cierre</label>
                <input type="text" value="<?php echo date('Y-m-d'); ?>" class="form-control" readonly id="inputEmail4">
            </div>
            <input type="hidden" class="form-control" id="inputPassword4" readonly value="1">
            
            <div class="col-1">
                <label for="inputAddress" class="form-label">Caja</label>
                <select name="numero_caja" class="form-select" id="numero_caja">
                    <?php foreach ($caja as $detalle) { ?>
                        <option value="<?php echo $detalle['idcaja']; ?>"><?php echo $detalle['numerocaja']; ?> </option>
                    <?php } ?>
                </select>
            </div>



            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="usuario_cierre" id="usuario_cierre">
            <input type="hidden" id="url" value="<?= base_url('') ?>">
            <div class="col-md-3">
                <label for="inputEmail4" class="form-label">Efectivo</label>
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control" name="efectivo_de_cierre" id="efectivo_de_cierre" value=0 onkeyup="saltar_apertura(event,'transaccion_cierre')" autofocus>
                </div>
            </div>
            <div class="col-md-3">
                <label for="inputPassword4" class="form-label">Transacción</label>
                <div class="input-icon mb-3">
                    <span class="input-icon-addon">
                        <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                            <path d="M12 3v3m0 12v3" />
                        </svg>
                    </span>
                    <input type="text" class="form-control" name="transaccion_cierre" id="transaccion_cierre" value=0 onkeyup="saltar_apertura(event,'cerrar_caja')" autofocus>
                </div>
            </div>
            <div class="col-10">
            </div>
            <div class="col-2">
                <button type="button" id="cerrar_caja" onclick="cierre_caja()" class="btn btn-primary">Cerrar caja </button>
            </div>
        </form>

    </div>
</div>




<?= $this->endSection('content') ?>