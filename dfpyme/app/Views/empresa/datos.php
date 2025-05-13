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
      <p class="text-primary h3">DATOS GENERALES DE LA EMPRESA </p>
    </div>
    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
      <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
    </div>
  </div>
</div>


<div class=" container col-md-12">
  <div class="card">
    <div class="card-body">
      <form class="row g-3" action="<?= base_url('empresa/actualizar_datos') ?>" method="POST">
        <input type="hidden" id="url" value="<?php echo base_url() ?>">
        <div class="hr-text text-green">
          <p class="text-green h3">Datos empresa</p>
        </div>
        <div class="col-md-2">
          <label for="inputEmail4" class="form-label">Tipo empresa</label>
          <select class="form-select" id="tipo_empresa" name="tipo_empresa">
            <?php foreach ($tipo_empresa as $detalle) : ?>
              <option value="<?php echo $detalle['id'] ?>" <?php if ($detalle['id'] == $datos_empresa[0]['fk_tipo_empresa']) : ?>selected <?php endif; ?>><?php echo $detalle['nombre'] ?> </option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-md-2">
          <label for="inputEmail4" class="form-label">Nit</label>
          <input type="text" class="form-control" name="nit_empresa" autofocus value="<?php echo $datos_empresa[0]['nitempresa'] ?>">
        </div>
        <div class="col-md-2">
          <label for="inputPassword4" class="form-label">Representante legal </label>
          <input type="text" class="form-control" name="representante_legal" value="<?php echo $datos_empresa[0]['representantelegalempresa'] ?>">
        </div>
        <div class="col-2">
          <label for="inputAddress" class="form-label">Régimen</label>
          <select class="form-select" aria-label="Default select example" name="id_regimen">

            <?php foreach ($regimen as $detalle) { ?>
              <option value="<?php echo $detalle['idregimen'] ?>" <?php if ($detalle['idregimen'] == $datos_empresa[0]['idregimen']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreregimen'] ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-3">
          <label for="inputAddress2" class="form-label">Razón social</label>
          <input type="text" class="form-control" name="razon_social" value="<?php echo $datos_empresa[0]['nombrejuridicoempresa'] ?>">
        </div>
        <div class="col-md-2">
          <label for="inputCity" class="form-label">Nombre comercial</label>
          <input type="text" class="form-control" name="nombre_comercial" value="<?php echo $datos_empresa[0]['nombrecomercialempresa'] ?>">
        </div>
        <div class="col-md-3">
          <label for="inputState" class="form-label">Télefono</label>
          <input type="text" class="form-control" name="telefono" value="<?php echo $datos_empresa[0]['telefonoempresa'] ?>">
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="form-label">Departamento</label>
          <select class="form-select" aria-label="Default select example" name="departamento" id="departamento" onchange="buscar_municipios(this.options[this.selectedIndex].value)">
            <?php foreach ($departamentos as $detalle) { ?>
              <option value="<?php echo $detalle['iddepartamento'] ?>" <?php if ($detalle['iddepartamento'] == $datos_empresa[0]['iddepartamento']) : ?>selected <?php endif; ?>><?php echo $detalle['nombredepartamento'] ?></option>
            <?php  } ?>
          </select>
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="form-label">Municipio</label>
          <select class="form-select" aria-label="Default select example" name="municipio" id="municipio">

            <?php foreach ($municipios as $detalle) { ?>
              <option value="<?php echo $detalle['idciudad'] ?>" <?php if ($detalle['idciudad'] == $datos_empresa[0]['idciudad']) : ?>selected <?php endif; ?>><?php echo $detalle['nombreciudad'] ?></option>
            <?php  } ?>

          </select>
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="form-label">Dirección</label>
          <input type="text" class="form-control" name="direccion" name="direccion" value="<?php echo $datos_empresa[0]['direccionempresa'] ?>">
        </div>

        <div class="col-8">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>

<?= $this->endSection('content') ?>