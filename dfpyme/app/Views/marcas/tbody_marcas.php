<?php foreach ($marcas as $detalle) { ?>
  <tr>
    <td><?php echo $detalle['idmarca'] ?></td>
    <td><?php echo $detalle['nombremarca'] ?></td>
    <td></td>
    <td>
      <div class="breadcrumb m-0">
        <form action="<?= base_url('mesas/editar') ?>" method="POST">
          <input type="hidden" value="<?php echo $detalle['idmarca'] ?>" name="id">
          <button type="submit" class="btn btn-primary">Editar</button>
        </form> &nbsp;
      </div>
    </td>
  </tr>
<?php } ?>