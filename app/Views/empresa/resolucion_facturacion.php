<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
RESOLUCIÓN DE FACTURACIÓN
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
      <a type="button" class="btn btn-warning btn-pill w-100" data-bs-toggle="modal" data-bs-target="#resolucion_facturacion">
        Agregar resolución
      </a>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">RESOLUCIÓN DE FACTURACIÓN </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>
<br>


<div class=" container col-md-12">
  <div class="card">
    <div class="card-body">
      <table class="table">
        <thead class="table-dark">
          <tr>
            <td>Id</td>
            <td>Número resolución</td>
            <td>Fecha Dian </td>
            <td>Rango inicial</td>
            <td>Rango final</td>
            <td>Vigencia</td>
            <td>Avisar cuando falten </td>
            <td>Acciones</td>
          </tr>
        </thead>
        <tbody id="resoluciones_de_facturacion">
          <input type="hidden" id="url" value="<?php echo base_url() ?>">
          <?php foreach ($resoluciones_dian as $detalle) { ?>
            <tr>
              <td><?php echo $detalle['iddian'] ?></td>
              <td><?php echo $detalle['numeroresoluciondian'] ?></td>
              <td><?php echo $detalle['fechadian'] ?></td>
              <td><?php echo $detalle['rangoinicialdian'] ?></td>
              <td><?php echo $detalle['rangofinaldian'] ?></td>
              <td><?php echo $detalle['vigencia_mes'] ?></td>
              <td><?php echo $detalle['alerta_facturacion'] ?></td>
              <td>

                <div class="breadcrumb m-0">
                  <div class="form-check form-switch">
                    <?php if ($detalle['iddian'] == $id_dian) { ?>
                      <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked onclick="estado_resolucion(<?php echo $detalle['iddian'] ?>)">
                    <?php } ?>
                    <?php if ($detalle['iddian'] != $id_dian) { ?>
                      <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"  onclick="estado_resolucion(<?php echo $detalle['iddian'] ?>)">
                    <?php } ?>
                  </div> &nbsp; &nbsp;&nbsp; <button type="button" class="btn btn-success btn-icon" onclick="editar_resolucion(<?php echo $detalle['iddian'] ?>)">Editar</button>

                </div>


              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

    </div>
  </div>
  <?= $this->include('empresa/modal_resolucion_facturacion') ?>
  <?= $this->include('empresa/modal_editar_resolucion_facturacion') ?>
  <?= $this->include('empresa/modal_activar_resolucion') ?>
  <?= $this->endSection('content') ?>