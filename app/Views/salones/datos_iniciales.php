<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
SALONES
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
            <p class="text-primary h3">Creación de salones</p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>


<div class=" container col-md-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('salones/save') ?>" method="POST">
                <div class="row">
                    <div class="col-sm-4">
                        <label class="form-label required"><b>Nombre</b></label>
                        <div class="input-icon">
                            <input type="text" class="number form-control form-control" name="nombre" value="<?= old('nombre') ?>" autofocus>
                            <span class="input-icon-addon">
                                <!-- Download SVG icon from http://tabler-icons.io/i/building-warehouse -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21v-13l9 -4l9 4v13" />
                                    <path d="M13 13h4v8h-10v-6h6" />
                                    <path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3" />
                                </svg>
                            </span>
                        </div>
                        </br>
                        <div class="text-danger"><?= session('errors.nombre') ?></div>
                    </div>
                    
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Crear salon</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection('content') ?>