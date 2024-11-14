<?= $this->extend('template/producto') ?>
<?= $this->section('title') ?>
PRODUCTO
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class=" container col-md-12">
    <div class="card">

        <div class="card-body">
            <form action="<?= base_url('producto/imagen') ?>" enctype="multipart/form-data" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="input-icon mb-3">
                                <input type="text" class="form-control" value ="<?php echo $nombre_producto ?>" id="producto_imagen" name="producto_imagen" placeholder="Buscar producto por nombre ,código interno , o código de barras " readonly>
                                <input type="hidden" id="url" name="url" value="<?= base_url() ?>">
                                <input type="hidden" name="id_producto_imagen" id="id_producto_imagen" value="<?php echo $id  ?>">
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="input-icon mb-3">
                                <input type="file" class="form-control" id="producto_imagen" name="producto_imagen" placeholder="Agrega imágen al producto ">
                                
                                <span class="input-icon-addon">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Agregar imágen</button>
            </form>
        </div>
    </div>
    <?= $this->endSection('content') ?>