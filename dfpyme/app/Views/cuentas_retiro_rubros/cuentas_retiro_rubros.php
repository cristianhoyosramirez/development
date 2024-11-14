<?php $user_session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
CUENTAS RETIRO DE DINERO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Salones</a></li>
                    <li class="breadcrumb-item"><a href="#">Cuentas reriro de dinero </a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">CREACIÓN DE RUBROS PARA LAS CUENTAS DE RETIRO DE DINERO </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>

<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('devolucion/agregar_rubro') ?>" method="POST">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" name="usuario_apertura">
            <div class="row">
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Cuenta</label>
                    <select class="form-select" name="cuenta_rubro">
                        <option value=""></option>
                        <?php foreach($cuentas as $detalle){ ?>
                        <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre_cuenta']?></option>
                       <?php  }?>
                    </select>
                    <div class="text-danger"><?= session('errors.cuenta_rubro') ?></div>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Nombre del rubro</label>
                    <input type="text" class="form-control" id="nombre_rubro" name="nombre_rubro" autofocus>
                    <div class="text-danger"><?= session('errors.nombre_rubro') ?></div>
                </div>

            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success w-md"> Crear rubro</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>