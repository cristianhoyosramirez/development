<div id="resList">
    <?php foreach ($atributos as $detalle): ?>
        <div class="accordion" id="accordion<?php echo $detalle['id']; ?>">
            <div class="accordion-item">
                <h2 class="accordion-header d-flex align-items-center justify-content-between px-3" id="heading-<?php echo $detalle['id']; ?>">
                    <button class="accordion-button collapsed flex-grow-1" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-<?php echo $detalle['id']; ?>" aria-expanded="false"
                        aria-controls="collapse-<?php echo $detalle['id']; ?>">
                        <label for="" class="form-label me-2">Atributo:</label>
                        <input type="text" class="form-control" value="<?php echo $detalle['nombre']; ?>" style="width: 50%;"
                            onclick="event.stopPropagation();" oninput="actualizarAtributo(this.value, <?php echo $detalle['id']; ?>)">
                    </button>
                    <button class="btn btn-outline-success ms-2 me-3 btn-icon"
                        type="button"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Agregar un componente"
                        onclick="addComponente(<?php echo $detalle['id']; ?>, '<?php echo $detalle['nombre']; ?>')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                    </button>

                    <button class="btn btn-outline-danger ms-2 me-3 btn-icon" type="button" title="Borrar atributo"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        onclick="borrarAtributo(<?php echo $detalle['id']; ?>,'<?php echo $detalle['nombre']; ?>')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="4" y1="7" x2="20" y2="7" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </button>
                </h2>

                <div id="collapse-<?php echo $detalle['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $detalle['id']; ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div id="resComponentes<?php echo $detalle['id']; ?>">
                            <?php
                            //$componentes = model('componentesAtributosProductoModel')->findAll();
                            $componentes = model('componentesAtributosProductoModel')->where('id_atributo', $detalle['id'])->findAll();
                            ?>
                            <p class="text-primary">Componentes</p>
                            <?php if (!empty($componentes)): ?>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($componentes as $detalle): ?>
                                        <span class="badge rounded-pill btn-outline-primary d-flex align-items-center p-3 fs-5"
                                            style="min-width: 150px; max-width: 250px; justify-content: space-between;"
                                            id="badge<?php echo $detalle['id']; ?>">
                                            <?php echo $detalle['nombre']; ?>
                                            <button type="button" class="btn-close ms-2" aria-label="Close"
                                                onclick="eliminarBadge(<?php echo $detalle['id']; ?>, '<?php echo $detalle['nombre']; ?>')">
                                            </button>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (empty($componentes)): ?>
                                <p class="text-orange text-center">Noy hay components asociados </p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3"></div>
    <?php endforeach ?>
</div>