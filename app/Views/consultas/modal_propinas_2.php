<!-- Modal -->
<div class="modal fade" id="modal_propinas" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Consulta de ingresos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="tabla_propinas"></div>
            </div>
            <div class="modal-footer">
                <table class="table">

                    <tbody class="table-scroll">

                        <tr>
                            <td class="table-dark">VENTAS POS </td>
                            <td class="table-dark">ELECTRÓNICA </td>
                           
                            <td class="table-dark">VALOR NETO </td>
                            <td class="table-dark">PROPINA</td>
                            <td class="table-dark">TOTAL DOCUMENTO </td>
                            <td class="table-dark">EFECTIVO </td>
                            <td class="table-dark">TRANSFERENCIA </td>
                            
                            <td class="table-dark">TOTAL INGRESOS</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p id="ventas_pos"></p>
                            </td>
                            <td>
                                <p id="ventas_electronicas"></p>
                            </td>
                            
                            <td><p class="text-primary h3" id="total_ventas" ></p> </td>
                            <td>
                                <p id="valor_total_propinas"> </p>
                            </td>
                            <td>
                                <p id="total_documento"></p>
                            </td>
                            <td>
                                <p id="efectivo"></p>
                            </td>
                            <td>
                                <p id="transferencia">
                            </td>gg
                            <!-- <td>
                                <p id="cambio">
                            </td> -->
                            <td>
                                <p id="total_de_ingresos"></p>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>