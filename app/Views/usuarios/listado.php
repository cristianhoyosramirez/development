<?php $session = session(); ?>
<?= $this->extend('template/template_usuario') ?>
<?= $this->section('title') ?>
LISTADO DE USUARIOS
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
    <div class="row align-items-end">
        <div class="col-3">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>

        </div>
        <div class="col-6">
            <p class="text-primary h3">LISTA GENERAL DE USUARIOS </p>
        </div>
        <div class="col-3">
            <div class="row">
                <div class="col">
                    <a type="button" class="btn btn-primary btn-pill w-100" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Usuarios inactivos
                    </a>
                </div>
                <div class="col">
                    <a type="button" href="<?php echo base_url('salones/datos_iniciales'); ?>" class="btn btn-warning btn-pill w-100" data-bs-toggle="modal" data-bs-target="#nuevo_usuario">
                        Agregar usuario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usuarios" class="table  table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>Nombre</td>
                                <td>Tipo usuario</td>
                                <td>Pin</td>
                                <td>Estado pin</td>
                                <td>Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $detalle) { ?>
                                <tr>
                                    <td><?php echo $detalle['nombresusuario_sistema'] ?></td>
                                    <td><?php echo $detalle['descripciontipo'] ?></td>
                                    <td><?php echo $detalle['pinusuario_sistema'] ?></td>
                                    <td><?php if ($detalle['pinusuario_sistema'] != null) { ?>
                                            <span class="text-green">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M5 12l5 5l10 -10" />
                                                </svg>
                                            </span>
                                        <?php } ?>
                                        <?php if ($detalle['pinusuario_sistema'] == null) { ?>
                                            <span class="text-red">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/letter-x -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="7" y1="4" x2="17" y2="20" />
                                                    <line x1="17" y1="4" x2="7" y2="20" />
                                                </svg>
                                            </span>
                                        <?php } ?>
                                    <td class="text-end">
                                        <div class="btn-list flex-nowrap">
                                            <form action="<?= base_url('usuarios/edit') ?>" method="POST">
                                                <input type="hidden" value="<?php echo $detalle['idusuario_sistema'] ?>" name="id">
                                                <button type="submit" class="btn btn-primary">Editar</button>
                                            </form>
                                            <form action="<?php echo base_url('usuarios/eliminar'); ?>" method="POST">
                                                <button class="btn btn-danger" name="id_usuario" value="<?php echo $detalle['idusuario_sistema'] ?>">Inactivar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->include('usuarios/modal_nuevo_usuario') ?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Usuario inactivos </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table id="usuarios" class="table  table-hover">
                    <thead class="table-dark">
                        <tr>
                            <td>Nombre</td>
                            <td>Tipo usuario</td>
                            <td>Pin</td>
                            <td>Estado pin</td>
                            <td>Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios_eliminados as $detalle) { ?>
                            <tr>
                                <td><?php echo $detalle['nombresusuario_sistema'] ?></td>

                                <?php if ($detalle['idtipo'] == 0) { ?>
                                    <td><?php echo "Administrador" ?></td>
                                <?php } ?>
                                <?php if ($detalle['idtipo'] == 1) { ?>
                                    <td><?php echo "General" ?></td>
                                <?php } ?>
                                <?php if ($detalle['idtipo'] == 2) { ?>
                                    <td><?php echo "Mesero" ?></td>
                                <?php } ?>

                                <td><?php echo $detalle['pinusuario_sistema'] ?></td>
                                <td><?php if ($detalle['pinusuario_sistema'] != null) { ?>
                                        <span class="text-green">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M5 12l5 5l10 -10" />
                                            </svg>
                                        </span>
                                    <?php } ?>
                                    <?php if ($detalle['pinusuario_sistema'] == null) { ?>
                                        <span class="text-red">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/letter-x -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <line x1="7" y1="4" x2="17" y2="20" />
                                                <line x1="17" y1="4" x2="7" y2="20" />
                                            </svg>
                                        </span>
                                    <?php } ?>
                                <td class="text-end">
                                    <div class="btn-list flex-nowrap">
                                        <form action="<?= base_url('usuarios/edit') ?>" method="POST">
                                            <input type="hidden" value="<?php echo $detalle['idusuario_sistema'] ?>" name="id">
                                            <button type="submit" class="btn btn-primary">Editar</button>
                                        </form>
                                        <form action="<?php echo base_url('usuarios/eliminar'); ?>" method="POST">
                                            <button class="btn btn-success" name="id_usuario" value="<?php echo $detalle['idusuario_sistema'] ?>">Activar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection('content') ?>
<!-- end row -->