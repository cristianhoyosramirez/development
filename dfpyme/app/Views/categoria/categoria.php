<?= $this->extend('template/categoria') ?>
<?= $this->section('title') ?>
CATEGORIAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<input type="hidden" id="url" value="<?= base_url() ?>">


<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <a type="button" class="btn btn-warning btn-pill w-100" data-bs-toggle="modal" data-bs-target="#nueva_categoria">
                Agregar categoria
            </a>
        </div>

        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA GENERAL DE CATEGORIAS DE PRODUCTO </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>

<div class="card container">
    <div class="card-body">
        <table class="table table-borderless">
            <thead class="table-dark">
                <tr>
                    <td title="Cambiar el nombre de la impresora "> NOMBRE DE CATEGORIA <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>
                    </td>
                    <td title="Actiuvar o desactivar la categoria "> ESTADO
                        <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>

                    </td>
                    <td title="Asignar impresora ">IMPRESORA ASIGNADA <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                        <svg xmlns="http://www.w3.org/2000/svg" title="Activar o inactivar" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="12" r="9" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                            <polyline points="11 12 12 12 12 16 13 16" />
                        </svg>
                    </td>
                    <td>
                        Subcategoria
                    </td>
                </tr>
            </thead>
            <tbody id="tabla_categorias">
                <?php foreach ($categorias as $detalle) { ?>

                    <input type="hidden" name="codigo_categoria" value="<?php echo $detalle['codigocategoria'] ?>">
                    <tr>

                        <td> <input type="text" value="<?php echo $detalle['nombrecategoria'] ?>" class="form-control" onkeyup="actualizar_categoria(event, this.value,<?php echo $detalle['codigocategoria'] ?>)" placeholder="Cambiar el nombre de la categoria "> </td>
                        <td>
                            <select class="form-select" id="estado_categoria" name="estado_categoria" onchange="estado_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">
                                <option value="true" <?php if ($detalle['permitir_categoria'] == 't') : ?>selected <?php endif; ?>>
                                    <p class="text-success">ACTIVA</p>
                                </option>
                                <option value="false" <?php if ($detalle['permitir_categoria'] == 'f') : ?>selected <?php endif; ?>>
                                    <p class="text-success">INACTIVA</p>
                                </option>
                            </select>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" id="id_impresora" name="id_impresora" onchange="asociar_impresora(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">
                                        <?php foreach ($impresoras as $detalles) { ?>
                                            <?php if (empty($detalle['impresora'])) { ?>
                                                <option>CATEGORIA NO TIENE IMPRESORA ASOCIADA </option>
                                            <?php } ?>
                                            <option value="<?php echo $detalles['id'] ?>" <?php if ($detalles['id'] == $detalle['impresora']) : ?>selected <?php endif; ?>><?php echo "Cód:" . " " . $detalles['id'] . "-" . $detalles['nombre'] ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </td>
                        <td>

                            <select class="form-select" aria-label="Default select example"  onchange="sub_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">

                                <?php if ($detalle['subcategoria'] == 't') : ?>
                                    <option value="true" selected>Si </option>
                                    <option value="false">No </option>

                                <?php endif ?>
                                <?php if ($detalle['subcategoria'] == 'f') : ?>
                                    <option value="true" >Si </option>
                                    <option value="false" selected >No </option>

                                <?php endif ?>
                            </select>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->include('categoria/modal_nueva_categoria') ?>
<?= $this->endSection('content') ?>