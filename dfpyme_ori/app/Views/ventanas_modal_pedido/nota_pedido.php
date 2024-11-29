<div class="modal modal-blur fade" id="nota_pedido" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-success"></div>
            <div class="modal-body  py-4">
                <h3>
                    <p class="text-center">NOTA PEDIDO</p>
                </h3>
                <div class="row">
                    <div class="col">
                        <textarea name="" class="form-control" id="nota_de_pedido" onkeyup="minusculas_a_mayusculas()" placeholder="Escriba aqui todas las observaciones del pedido " cols="30" rows="3" autofocus></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" onclick="nota_de_pedido()" class="btn btn-success w-100" data-bs-dismiss="modal">
                                Agregar nota
                            </a>
                        </div>
                        <div class="col"> <a href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                Cancelar
                            </a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>