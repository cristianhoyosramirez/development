<!-- Modal -->
<div class="modal fade" id="componentesProducto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><span id="asignacionAtributos" class="h3"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="d-flex align-items-center gap-2">
                        <span>Atributo:</span>
                        <input type="text" class="form-control w-auto" placeholder="Buscar atributos">
                        <input type="hidden" id="idProductoAtributo">
                    </div>

                    <div class="mb-2"></div>

                    <div class="col-6">
                        <table class="table">
                            <thead class="table-dark">
                                <tr>
                                    <td>Atributo</th>
                                    <td>Accion</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php $atributos = model('atributosProductoModel')->findAll(); ?>

                                <?php foreach ($atributos as $detalle):
                                    $componentes = model('componentesAtributosProductoModel')->select('nombre')->where('id_atributo', $detalle['id'])->findAll();
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                            echo $detalle['nombre'];

                                            $componentesTexto = [];
                                            foreach ($componentes as $keyComponente) {
                                                $componentesTexto[] = $keyComponente['nombre']; // Reemplaza 'nombre' por la clave correcta si es necesario
                                            }

                                            if (!empty($componentesTexto)) {
                                                echo "<br><span style='color: blue;'>(" . implode(", ", $componentesTexto) . ").</span>";
                                                // Salto de línea antes de los componentes
                                            }
                                            ?>
                                        </td>


                                        <td><button type="button" class="btn btn-outline-success btn-icon" onclick="seleccionarAtributo(<?php echo $detalle['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="12" y1="5" x2="12" y2="19" />
                                                    <line x1="5" y1="12" x2="19" y2="12" />
                                                </svg></button></td>
                                    </tr>
                                <?php endforeach ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">

                        <table class="table">
                            <thead class="table-dark">
                                <tr>
                                    <td>Atributo</th>
                                    <td>Máx componentes </th>
                                    <td>Acción </th>
                                </tr>
                            </thead>
                            <tbody id="resComPro">




                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Aceptar</button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>