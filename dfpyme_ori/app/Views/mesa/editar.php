<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
PRODUCTOS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Salones</a></li>
          <li class="breadcrumb-item"><a href="#">Mesas</a></li>
          <li class="breadcrumb-item"><a href="#">Usuarios</a></li>
          <li class="breadcrumb-item"><a href="#">Empresa</a></li>
        </ol>
      </nav>
    </div>
    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">CAMBIAR DATOS DE LA MESAS</p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>
<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('mesas/actualizar') ?>" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Sálon</label>
                    <select class="form-select select2" name="salon">
                        <?php foreach ($salones as $detalle) { ?>
                            <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $fk_salon) : ?>selected <?php endif; ?>><?php echo $detalle['nombre'] ?> </option>
                        <?php } ?>

                    </select>
                    <div class="text-danger"><?= session('errors.salon') ?></div>
                </div>
                <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">Nombre mesa</label>
                    <input type="hidden" value="<?php echo $id_mesa ?>" name="id">
                    <input type="text" class="form-control" name="nombre" value="<?php echo $nombre_mesa ?>" autofocus>
                    <div class="text-danger"><?= session('errors.nombre') ?></div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"> Actualizar registro</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>