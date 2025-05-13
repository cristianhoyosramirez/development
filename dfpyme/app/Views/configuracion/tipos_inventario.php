<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
TIPOS DE INVENTARIO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

  <div class="card ">
    <div class="card-body">
      <p class="text-primary text-center h3 mb-3">Tipos de inventario</p>

      <div class="d-flex justify-content-end mb-3">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-outline-orange" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          Nuevo tipo de inventario
        </button>
      </div>

      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <td>Nombre </th>
            <td>Descripción </th>
            <td>Estado </th>

          </tr>
        </thead>
        <tbody>
          <?php foreach ($tiposInventario as $detalle): ?>
            <tr>
              <td scope="row"><?php echo $detalle['nombre']; ?></th>
              <td scope="row"><?php echo $detalle['descripcion']; ?></th>
              <td scope="row">
                <select class="form-select" onchange="cambiarEstado(<?php echo $detalle['id']; ?>,this.value)">
                  <option value='true' <?= ($detalle['estado'] === 't') ? 'selected' : '' ?>>Activo</option>
                  <option value='false' <?= ($detalle['estado'] === 'f') ? 'selected' : '' ?>>No permitido</option>
                </select>
              </td>
              </th>

            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nuevo tipo de inventario </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <label for="" class="form-label">Nombre </label>
            <input type="text" class="form-control">
          </div>
          <div class="col">
            <label for="" class="form-label">Descripción </label>
            <input type="text" class="form-control">
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Guardar </button>
        <button type="button" class="btn btn-outline-danger">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<script>
  async function cambiarEstado(id, valor) {
    
    try {
      let response = await fetch('<?= base_url('configuracion/updateInventario') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id: id,
          valor:valor
        })
      });

      const data = await response.json();

      if (data.response=="true"){
        alert('Hola mundo ')
      }

      if (response.ok) {
        console.log('Estado actualizado correctamente:', data);
        // puedes mostrar una notificación o actualizar el DOM aquí
      } else {
        console.error('Error en la respuesta del servidor:', data);
      }
    } catch (error) {
      console.error('Error en la petición:', error);
    }
  }
</script>




<?= $this->endSection('content') ?>