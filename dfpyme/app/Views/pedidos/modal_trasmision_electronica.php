 <!-- Modal -->
 <div class="modal fade" id="barra_progreso" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Trasmisión de factura electrónica </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_de_factura">
                        <div class="mb-3" id="barra_de_progreso">
                            <label class="form-label" id="respuesta_de_dian">Esperando respuesta DIAN</label>
                            <div class="progress">
                                <div class="progress-bar progress-bar-indeterminate bg-green"></div>
                            </div>
                        </div>

                        <div id="respuesta_dian" style="display: none;">
                            <p class="text-primary h3 " id="texto_dian"> </p>
                        </div>
                    </div>
                    <p id="zz">zz</p>
                    <div class="modal-footer" id="opciones_dian" style="display: none;">
                        <button type="button" class="btn btn-outline-success" onclick="impresion_factura_electronica()">Imprimir </button>
                        <button type="button" class="btn btn-outline-primary" onclick="nueva_factura()">Nueva Factura </button>
                    </div>
                    <div class="modal-footer" id="error_dian" style="display: none;">
                        <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Trasmitir </button>
                        <button type="button" class="btn btn-outline-primary" onclick="imprimir_orden_pedido()">Imprimir orden de pedido </button>
                        <button type="button" class="btn btn-outline-primary" onclick="nueva_factura()">Nueva factura </button>
                    </div>
                </div>
            </div>
        </div>