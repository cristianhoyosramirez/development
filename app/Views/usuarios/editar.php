<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
PIN USUARIOS
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
            <p class="text-primary h3">CAMBIAR DATOS DE LOS USUARIOS </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<div class="card container">
    <div class="card-body">
        <form action="<?= base_url('usuarios/update') ?>" method="POST">
            <input type="hidden" value="<?php echo $idusuario_sistema ?>" name="id_usuario">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Identificación</label>
                    <div class="input-icon mb-3">
                        <input type="text" class="form-control" value="<?php echo $identificacion ?>" name="identificacion_usuario">
                        <span class="input-icon-addon text-green">
                            <!-- Download SVG icon from http://tabler-icons.io/i/id -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <rect x="3" y="4" width="18" height="16" rx="3" />
                                <circle cx="9" cy="10" r="2" />
                                <line x1="15" y1="8" x2="17" y2="8" />
                                <line x1="15" y1="12" x2="17" y2="12" />
                                <line x1="7" y1="16" x2="17" y2="16" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nombre</label>
                    <div class="input-icon mb-3">
                        <input type="text" class="form-control" value="<?php echo $nombres ?>" name="nombre_usuario">
                        <span class="input-icon-addon text-green">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="7" r="4" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usuario</label>
                    <div class="input-icon mb-3">
                        <input type="text" class="form-control" value="<?php echo $usuario ?>" name="usuario_usuario">
                        <span class="input-icon-addon text-green">
                            <!-- Download SVG icon from http://tabler-icons.io/i/user-circle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="12" cy="12" r="9" />
                                <circle cx="12" cy="10" r="3" />
                                <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                            </svg>
                        </span>
                    </div>
                    <div class="text-danger"><?= session('errors.id_usuario') ?></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pin Actual</label>
                    <div class="input-icon mb-3">
                        <input type="number" maxlength="4" class="form-control" placeholder=" 4 digitos" value="<?php echo $pin_de_usuario ?>" readonly>
                        <span class="input-icon-addon text-green">
                            <!-- Download SVG icon from http://tabler-icons.io/i/list-numbers -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11 6h9" />
                                <path d="M11 12h9" />
                                <path d="M12 18h8" />
                                <path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
                                <path d="M6 10v-6l-2 2" />
                            </svg>
                        </span>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Cambiar pin</label>
                    <div class="input-icon mb-3">
                        <input type="number" maxlength="4" class="form-control" placeholder=" 4 digitos" id="pin" name="pin" value="<?php echo $pin_de_usuario ?>">
                        <span class="input-icon-addon text-green">
                            <!-- Download SVG icon from http://tabler-icons.io/i/list-numbers -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11 6h9" />
                                <path d="M11 12h9" />
                                <path d="M12 18h8" />
                                <path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
                                <path d="M6 10v-6l-2 2" />
                            </svg>
                        </span>
                    </div>
                    <?php if (isset($errors['pin'])) { ?>
                        <p class="text-danger"><b><?php echo $errors['pin']; ?></b></p>
                    <?php } ?>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipo de usuario</label>
                    <div class="input-icon mb-3">
                        <select class="form-select" id="tipo_usuario" name="tipo_usuario">

                            <?php foreach ($tipos_usuario as $detalle) : ?>
                                <option value="<?php echo $detalle['idtipo'] ?>" <?php if ($detalle['idtipo'] == $id_tipo) : ?>selected <?php endif; ?>><?php echo $detalle['descripciontipo'] ?> </option>
                            <?php endforeach ?>



                        </select>
                    </div>

                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-md"> Actualizar registro</button>
            </div>
        </form>
    </div>
</div>




<?= $this->endSection('content') ?>