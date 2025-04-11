<div class="card container">
    <div class="card-body">

        <div class="row ">
            <div class="col">
                <p class="text-success  h3">ESTADO DE LA CAJA: <?php echo $estado; ?> </p>
            </div>
        </div>
        <div class="row ">
            <div class="col">
                <p class=" h3">FECHA APERTURA : <?php echo $fecha_apertura ?> </p>
            </div>
            <div class="col">
                <p class="  h3">FECHA CIERRE : <?php echo $fecha_cierre ?> </p>
            </div>
        </div>
        <div class="container">
            <input type="hidden" value="<?php echo base_url(); ?>" id="url">
            <div class="row g-1">
                <div class="col-4">
                    <div class="row mb-3 ">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Apertura</label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $valor_apertura ?>" readonly>

                                <button type="button" title="Editar apertura" onclick="editar_apertura(<?php echo $id_apertura ?>)" class="btn bg-azure-lt btn-icon"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                    </svg></button>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Ingresos en efectivo</label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="colFormLabel" value="<?php echo $ingresos_efectivo ?>" readonly>
                                <button type="button" class="btn bg-green-lt btn-icon" title="Ver todos los ingresos" onclick="total_ingresos_efectivo(<?php echo $id_apertura ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Ingresos transferencias </label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="colFormLabel" value="<?php echo $ingresos_transaccion ?>" readonly>
                                <button type="button" class="btn bg-green-lt btn-icon" title="Ver todos los ingresos" onclick="total_ingresos_transaccion(<?php echo $id_apertura ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Total ingresos </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $total_ingresos ?>" readonly>
                        </div>
                    </div>

                </div>
                <div class="col-4">
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-4 col-form-label">Retiros</label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $retiros ?>" readonly>
                                <button type="button" class="btn bg-green-lt btn-icon" onclick="ver_retiros(<?php echo $id_apertura ?>)" title="Ver todos los ingresos" title="Ver todos los retiros realizados"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <circle cx="10" cy="10" r="7" />
                                        <line x1="21" y1="21" x2="15" y2="15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-4 col-form-label">Devoluciones</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="colFormLabel" value="<?php echo $devoluciones ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Retiros+Devoluciones</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="colFormLabel" value="<?php echo $retirosmasdevoluciones ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-6 col-form-label">Debe tener en caja </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="colFormLabel" value="<?php echo $saldo_caja ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-4 col-form-label">Cierre efectivo</label>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $efectivo_cierre ?>" readonly>
                                <button type="button" class="btn bg-blue-lt btn-icon" onclick="editar_valor_de_cierre(<?php echo $id_apertura ?>)" title="Ver todos los ingresos" title="Ver todos los retiros realizados"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-5 col-form-label">Cierre transferencias</label>
                        <div class="col-sm-5">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="colFormLabel" value="<?php echo $transaccion_cierre ?>" readonly>
                                <button type="button" class="btn bg-blue-lt btn-icon" onclick="editar_valor_cierre_transferencias(<?php #echo $id_apertura 
                                                                                                                                    ?>)" title="Ver todos los ingresos" title="Ver todos los retiros realizados"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                    </svg></button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-4 col-form-label">total cierre</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="colFormLabel" value="<?php echo $total_cierre ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-4 col-form-label">Diferencia</label>
                        <div class="col-sm-6">
                            <?php if ($diferencia < 0) { ?>
                                <input type="text" class="form-control" id="colFormLabel" style="color: red;" value="<?php echo $diferencia ?>" readonly>
                            <?php } ?>
                            <?php if ($diferencia > 1) { ?>
                                <input type="text" class="form-control" id="colFormLabel" style="color: green;" value="<?php echo $diferencia ?>" readonly>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <table>
                <tr>
                    <td> <button type="button" class="btn bg-azure-lt btn-icon" onclick="imprimir_movimientos(<?php echo $id_apertura ?>)">Imprimir</button></td>
                    <td>
                        <form action="<?= base_url('consultas_y_reportes/reporte_de_ventas') ?>" method="POST">
                            <input type="hidden" name="id_apertura" value="<?php echo $id_apertura ?>">
                            <button type="submit" class="btn bg-azure-lt btn-icon">Reporte ventas</button>
                        </form>
                    </td>
                    <td>

                        <button type="button" class="btn bg-orange-lt btn-icon" data-bs-toggle="modal" data-bs-target="#modal_consulta_cierres">Consultar otros cierres</button>
                    </td>
                    <td>
                        <form action="<?= base_url('consultas_y_reportes/informe_fiscal_desde_caja') ?>" method="POST">
                            <input type="hidden" name="id_apertura" value="<?php echo $id_apertura ?>">
                            <button type="submit" class="btn bg-lime-lt btn-icon">Informe fiscal de ventas </button>
                        </form>
                    </td>

                </tr>
            </table>
        </div>
    </div>
</div>
</div>