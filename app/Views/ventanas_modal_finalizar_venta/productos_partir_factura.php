<!-- Modal -->
<div class="modal fade" id="items_partir_factura" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body">
                <table class="table">

                    <tr>
                        <th scope="col">Facturacion parcial de la mesa </th>
                        <td scope="col">
                            <p>1

                            </p>
                        </td>
                    </tr>

                </table>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Código</td>
                            <td scope="col">Descripcion</td>
                            <td scope="col">Valor_unitario</td>
                            <td scope="col">Cantidad</td>
                            <td scope="col">Total</td>
                        </tr>
                    </thead>
                    <tbody id="items_facturar_partir">

                    </tbody>
                </table>
               

                <div class="container">
                    <div class="row">
                        <div class="col">
                            
                        </div>
                        <div class="col">
                            <p>TOTAL</p>
                        </div>
                        <div class="col">
                        <input type="hidden" class="form-control" id="total_partir_factura">
                        <input type="hidden" class="form-control" id="numero_pedido_partir_factura">
                            
                            <P id="total_factura_mostrar"></P>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" onclick="finalizar_partir_factura()" class="btn btn-success">Finalizar Venta</button>
                <button type="button"  onclick="cancelar_partir_factura()"    class="btn btn-danger" data-bs-dismiss="modal">Cancelar </button>
            </div>
        </div>
    </div>
</div>