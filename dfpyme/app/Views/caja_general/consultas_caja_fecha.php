<div class="card container">
    <div class="card-body">
        <div class="container">
            <div id="datos_consulta_de_caja_general">

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
                        <div class="col-6">
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
                                    <input type="text" class="form-control" id="colFormLabel" value="<?php echo $ingresos_efectivo ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-6 col-form-label">Ingresos por transferencias </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="colFormLabel" value="<?php echo $ingresos_transaccion ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-6 col-form-label">Total ingresos </label>
                                <div class="col-sm-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $total_ingresos ?>" readonly>

                                        <button type="button" class="btn bg-green-lt btn-icon" title="Ver todos los ingresos" onclick="total_ingresos(<?php echo $id_apertura ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                            <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                                <path d="M12 3v3m0 12v3" />
                                            </svg></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-6 col-form-label">Cierre efectivo </label>
                                <div class="col-sm-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $cierre ?>" id="valor_cierre" readonly>
                                        <button type="button" class="btn bg-blue-lt btn-icon" title="Editar el valor del cierre" onclick="editar_cierre(<?php echo $id_apertura ?>) "><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                            <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                            </svg></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-4 col-form-label">Retiros</label>
                                <div class="col-sm-6">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php echo $retiros ?>" readonly>
                                        <button type="button" class="btn bg-blue-lt btn-icon" onclick="ver_retiros(<?php echo $id_apertura ?>)" title="Ver todos los ingresos" title="Ver todos los retiros realizados"><!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                            <!-- Download SVG icon from http://tabler-icons.io/i/door-exit -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M13 12v.01" />
                                                <path d="M3 21h18" />
                                                <path d="M5 21v-16a2 2 0 0 1 2 -2h7.5m2.5 10.5v7.5" />
                                                <path d="M14 7h7m-3 -3l3 3l-3 3" />
                                            </svg></button>
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
                                <label for="colFormLabel" class="col-sm-4 col-form-label">Retiros + Devoluciones</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="colFormLabel" value="<?php echo $retiros_devoluciones ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-4 col-form-label">Debe tener en caja </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="colFormLabel" value="<?php echo $saldo_caja ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="colFormLabel" class="col-sm-4 col-form-label">Diferencia </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="colFormLabel" readonly value="<?php echo $diferencia ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <table>
                        <tr>
                            <td> <button type="button" class="btn bg-azure-lt btn-icon" onclick="imprimir_reporte(<?php echo $id_apertura ?>)">Imprimir</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>