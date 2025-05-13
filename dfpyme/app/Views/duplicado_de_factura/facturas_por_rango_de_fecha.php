<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/duplicadoFactura') ?>
<?= $this->section('title') ?>
LISTADO DE FACTURAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<br>

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
      <p class="text-primary h3">REIMPRESIÓN DE FACTURA </p>
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

        <form class="row g-3" id="rango_de_fechas" action="<?= base_url('consultas_y_reportes/facturas_por_rango_de_fechas') ?>" method="POST">
          <div class="col-md-4">
            <label for="inputEmail4">Fecha inicial </label>
            <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date('Y-m-d')  ?>">
            <input type="hidden" value="<?= base_url() ?>" id="url">
            <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
            <div class="text-danger"><?= session('errors.fecha_inicial') ?></div>
          </div>
          <div class="col-md-4">
            <label for="inputPassword4">Fecha final </label>
            <input type="date" class="form-control" id="fecha_final" name="fecha_final" value="<?php echo date('Y-m-d')  ?>">
            <div class="text-danger"><?= session('errors.fecha_final') ?></div>
          </div>
          <div class="col-4">
            <!-- <a onclick="consultar_facturas_por_rango_de_fechas()" class="btn btn-primary">Buscar facturas</a>-->
            <br>
            <button type="submit" class="btn btn-primary">Buscar</button>
          </div>
        </form>



        <div class="table-responsive">
          <table id="duplicadoFactura" class="table  table-hover">
            <thead class="table-dark">
              <tr>
                <td>Fecha factura </td>
                <td width="18%">Nombre Cliente</td>
                <td>Nit cliente</td>
                <td>N°/pedido</td>
                <td>Mesa</td>
                <td>Numero factura</td>
                <td>Hora factura </td>
                <td>Valor factura</td>
                <td>Acccion </td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($facturas as $detalle) { ?>
                <tr>
                  <td><?php echo $detalle['fecha_factura'] ?></td>
                  <td>
                    <?php $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
                    echo  $nombre_cliente['nombrescliente'];
                    ?>
                  </td>

                  <td><?php echo number_format($detalle['nit_cliente'], 0, ",", ".") ?></td>
                  <td><?php if (empty($detalle['numero_pedido'])) {
                        echo "Mostrador"; ?><?php } ?>

                    <?php if (!empty($detalle['numero_pedido'])) {
                      echo $detalle['numero_pedido']; ?><?php } ?>

                  </td>
                  <td><?php if ($detalle['fk_mesa'] == 0) { ?>
                      <?php echo "Mostrador" ?>
                    <?php } ?>
                    <?php if ($detalle['fk_mesa'] != 0) { ?>
                      <?php $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $detalle['fk_mesa'])->first();
                      echo $nombre_mesa['nombre']; ?>
                    <?php } ?>
                  </td>
                  <td><?php echo $detalle['numero_factura'] ?></td>




                  <td><?php echo  date("g:i a", strtotime($detalle['horafactura_venta'])) ?></td>
                  <td><?php echo "$" . number_format($detalle['valor_factura'], 0, ",", ".") ?></td>
                  <td>
                    <div class="row g-2 align-items-center mb-n3">
                      <div class="col-6 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <form action="<?= base_url('consultas_y_reportes/imprimir_duplicado_factura') ?>" method="post">
                          <input type="hidden" name="id_de_factura" value="<?php echo $detalle['id_factura'] ?>">
                          <button type="button" onclick="imprimir_duplicado_factura(<?php echo $detalle['id_factura'] ?>)" class="btn btn-success btn-icon" title="Imprimir factura">
                            Imprimir
                          </button>
                        </form>
                      </div>
                      <div class="col-6 col-sm-4 col-md-2 col-xl-auto mb-3">
                        <button onclick="detalle_de_factura(<?php echo $detalle['id_factura'] ?>)" type="button" class="btn btn-secondary btn-icon" title="Ver productos de la factura">
                          <input type="hidden" value="<?= base_url() ?>" id="url">
                          Detalle
                        </button>
                      </div>
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
<?= $this->endSection('content') ?>
<!-- end row -->