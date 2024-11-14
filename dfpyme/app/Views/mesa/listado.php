<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE MESAS
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
      <a href="<?php echo base_url('mesas/add'); ?>" class="btn btn-warning btn-pill w-100">Agregar mesa</a>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">LISTA GENERAL DE MESAS  </p>
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
          <table id="mesas" class="table  table-hover">
            <thead class="table-dark">
              <tr>
                <td>Salon</td>
                <td>Nombre mesa</td>
                <td></td>
                <td>Acciones</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($mesas as $detalle) { ?>
                <tr>
                  <td><?php echo $detalle['nombre_salon'] ?></td>
                  <td><?php echo $detalle['nombre_mesas'] ?></td>
                  <td></td>
                  <td>
                    <div class="breadcrumb m-0">
                      <form action="<?= base_url('mesas/editar') ?>" method="POST">
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