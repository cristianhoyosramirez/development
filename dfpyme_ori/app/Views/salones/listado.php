<?php $session = session(); ?>
<?= $this->extend('template/salones') ?>
<?= $this->section('title') ?>
LISTADO DE SALONES
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
  </div>
</div>
<br>
<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <a href="<?php echo base_url('salones/datos_iniciales'); ?>" class="btn btn-warning btn-pill w-100">Agregar salon</a>
    </div>
    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">LISTA GENERAL DE SALONES </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>
<br>
<div class="container">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table id="salones" class="table">
            <thead class="table-dark">
              <tr>
                <td>Nombre salon</td>
                <td></td>
                <td></td>
                <td>Acciones</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($salones as $detalle) { ?>
                <tr>
                  <td><?php echo $detalle['nombre'] ?></td>
                  <td></td>
                  <td></td>
                  <td>
                    <div class="breadcrumb m-0">
                      <form action="<?= base_url('salones/edit') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['id'] ?>" name="id">
                        <button type="submit" class="btn btn-primary">Editar</button>
                      </form> &nbsp;
                    </div>
                  </td>
                </tr>
              <?php } ?>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?= $this->endSection('content') ?>
<!-- end row -->