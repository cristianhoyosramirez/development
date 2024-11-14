<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
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
      <p class="text-primary h3">CONFIGURACIÓN DE IMPRESION DE COMPROBANTE DE TRANSFERENCIA </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>


<div class=" container col-md-12">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="<?= base_url('empresa/configuracion_impresion') ?>" method="POST">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">
        <div class="col-md-4">

          <select class="form-select" aria-label="Default select example" name="impresion" id="impresion">
            <option value="1">Si</option>
            <option value="0">No</option>
          </select>
        </div>

        <div class="col-8">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>

    </div>
  </div>

  <?= $this->endSection('content') ?>