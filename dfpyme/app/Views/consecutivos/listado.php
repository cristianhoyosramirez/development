<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
TABLA CONSECUTIVOS 
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
      
    </div>

    <div class="col-lg-auto ms-lg-auto">
      <p class="text-primary h3">TABLA DE CONSECUTIVOS   </p>
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
          <table id="mesas" class="table  table-hover table-borderless">
            <thead class="table-dark">
              <tr>
                <td>Nombre de campo</td>
                <td>consecutivo</td>
              </tr>
            </thead>
            <tbody>
             <?php foreach($consecutivos as $detalle){ ?>
              <tr>
                <td><?php echo $detalle['conceptoconsecutivo'] ?> </td>
                <td><input type="text" class="form-control" value="<?php echo $detalle['numeroconsecutivo'] ?>"  onkeyup="actualizar_consecutivos(event, this.value,<?php echo $detalle['idconsecutivos']?>)"    ></td>
              </tr>
              <?php }?>
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