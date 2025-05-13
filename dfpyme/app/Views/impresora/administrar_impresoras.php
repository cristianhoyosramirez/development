<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
IMPRESORAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="card container">
    <div class="card-body">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">ENTIDAD</td>
                    <td scope="col">IMPRESORA</th>
                    <td scope="col">ACCION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <form action="">
                        <th scope="row">PRECUENTA</th>
                        <td><select class="form-select" id="id_impresora" name="id_impresora">
                                <?php foreach ($impresoras as $detalles) { ?>
                                    <option value="<?php echo $detalles['id'] ?>"><?php echo "Cód:" . " " . $detalles['id'] . "-" . $detalles['nombre'] ?></option>
                                <?php } ?>
                            </select></td>
                        <td><button type="submit" class="btn btn-success">Asignar impresora</button></td>
                    </form>
                </tr>
                <tr>
                    <th scope="row">APERTURA DE CAJON MONEDERO</th>
                    <td><select class="form-select" id="id_impresora" name="id_impresora">
                            <?php foreach ($impresoras as $detalles) { ?>
                                <option value="<?php echo $detalles['id'] ?>"><?php echo "Cód:" . " " . $detalles['id'] . "-" . $detalles['nombre'] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><button type="button" class="btn btn-success">Asignar impresora</button></td>
                </tr>
                <tr>
                    <th scope="row">IMPRPESIÓN FACTURAS</th>
                    <td><select class="form-select" id="id_impresora" name="id_impresora">
                            <?php foreach ($impresoras as $detalles) { ?>
                                <option value="<?php echo $detalles['id'] ?>"><?php echo "Cód:" . " " . $detalles['id'] . "-" . $detalles['nombre'] ?></option>
                            <?php } ?>
                        </select></td>
                    <td><button type="button" class="btn btn-success">Asignar impresora</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>




<?= $this->endSection('content') ?>