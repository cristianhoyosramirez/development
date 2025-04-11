<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE MARCAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>


<br>
<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">
      <a type="button" class="btn btn-warning btn-pill w-100" data-bs-toggle="modal" data-bs-target="#agregar_marca">
        Agregar marca
      </a>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">LISTA GENERAL DE MARCAS </p>
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
                <td>Marca</td>
                <td></td>
                <td></td>
                <td>Acciones</td>
              </tr>
            </thead>
            <tbody id="todas_las_marcas">
              <?php foreach ($marcas as $detalle) { ?>
                <tr>
                  <td><?php echo $detalle['idmarca'] ?></td>
                  <td><?php echo $detalle['nombremarca'] ?></td>
                  <td></td>
                  <td>
                    <div class="breadcrumb m-0">
                      <form action="<?= base_url('categoria/editar_marca') ?>" method="POST">
                        <input type="hidden" value="<?php echo $detalle['idmarca'] ?>" name="id">
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
<?= $this->include('producto/modal_marcas') ?>
<?= $this->endSection('content') ?>
<!-- end row -->