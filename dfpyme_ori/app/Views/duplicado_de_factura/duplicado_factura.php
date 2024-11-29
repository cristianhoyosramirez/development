<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
DUPLICADO DE FACTURA
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->

<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Ventas</a></li>
                    <li class="breadcrumb-item"><a href="#">Facturación</a></li>
                    <li class="breadcrumb-item"><a href="#">Copia de factura</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">REIMPRESIÓN DE FACTURA  </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>


<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="row " id="rango_de_fechas" action="<?= base_url('consultas_y_reportes/facturas_por_rango_de_fechas') ?>" method="POST">
                    <div class="col-md-4">
                        <label for="inputEmail4" >Fecha inicial </label>
                        <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date('Y-m-d')  ?>">
                        <input type="hidden" value="<?= base_url() ?>" id="url">
                        <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                        <div class="text-danger"><?= session('errors.fecha_inicial') ?></div>
                    </div>
                    <div class="col-md-4">
                       <label for="">Fecha final </label>
                        <input type="date" class="form-control" id="fecha_final" name="fecha_final"   value="<?php echo date('Y-m-d')  ?>" >
                        <div class="text-danger"><?= session('errors.fecha_final') ?></div>
                    </div>
                    <div class="col-4"> <br>
                        <!-- <a onclick="consultar_facturas_por_rango_de_fechas()" class="btn btn-primary">Buscar facturas</a>-->
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
</div>
<?= $this->endSection('content') ?>
<!-- end row -->