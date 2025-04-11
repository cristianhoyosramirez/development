<?= $this->extend('template/clientes') ?>
<?= $this->section('title') ?>
LISTADO DE CLIENTES
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>


<!--Sart row-->
<div class="container">
  <div class="row text-center align-items-center flex-row-reverse">
    <div class="col-lg-auto ms-lg-auto">

      <button type="button" class="btn btn-warning btn-pill w-100" onclick="nuevo_cliente()">
        Agregar cliente
      </button>
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">LISTA GENERAL DE CLIENTES </p>
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
          <input type="hidden" id="url" value="<?php echo base_url() ?>">

          <table id="clientes" class="table">
            <thead class="table-dark">
              <td>Nit</td>
              <td>Cliente</td>
              <td>Celular</td>
              <td>Dirección</td>
              <td>Régimen</td>
              <td>Acción</td>
            </thead>
            <tbody id="listado_de_clientes">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?= $this->include('pedidos/crear_cliente') ?>
<?= $this->include('clientes/modal_editar_cliente') ?>


<!-- Modal -->
<div class="modal fade" id="edicion_cliente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- <div id="modal_editar_cliente"></div> -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>


<?= $this->endSection('content') ?>
<!-- end row -->