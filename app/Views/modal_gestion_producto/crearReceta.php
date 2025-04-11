<!-- Modal -->
<div class="modal fade" id="crearReceta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creacion de recetas </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-8">

                        <p class="text-primary">Productos Recetas</p>

                        <div style="max-height: 80vh; overflow-y: auto;">
                            <?php foreach ($recetas as $index => $detalle): ?>
                                <div class="accordion" id="accordion-<?php echo $index; ?>">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading-<?php echo $index; ?>">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $index; ?>">
                                                <?php echo $detalle['nombreproducto']; ?>
                                            </button>
                                        </h2>
                                        <div id="collapse-<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $index; ?>" data-bs-parent="#accordion-<?php echo $index; ?>">
                                            <div class="accordion-body">
                                               
                                                <div class="mb-3"></div>
                                                <table class="table">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <td scope="col">Código</th>
                                                            <td scope="col">Producto </th>
                                                            <td scope="col">Cantidad </th>
                                                            <td scope="col">Valor costo unidad </th>
                                                            <td scope="col">Valor costo total </th>
                                                            <td scope="col">Accion </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="ingredientes">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3 text-primary">
                            Insumos
                        </div>


                        <div style="max-height: 80vh; overflow-y: auto;">
                            <?php foreach ($ingredientes as $detalleInsumos): ?>

                                <a href="#" class="card card-link" onclick="adicionarIngrediente(<?php echo $detalleInsumos['id']; ?> )">
                                    <div class="card-body"><?php echo $detalleInsumos['nombreproducto']; ?></div>
                                </a>
                                <div class="mb-3"></div>
                            <?php endforeach; ?>
                        </div>


                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Aceptar </button>

            </div>
        </div>
    </div>
</div>