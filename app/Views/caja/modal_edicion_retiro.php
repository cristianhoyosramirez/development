 <!-- Modal edicion retiro de dinero -->
 <div class="modal fade" id="edicion_retiro_de_dinero" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Editar retiro de dinero</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="input-icon">
                     <span class="input-icon-addon">
                         <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                         <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                             <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                             <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                             <path d="M12 3v3m0 12v3" />
                         </svg>
                     </span>
                     <input type="text" class="form-control form-control-rounded" id="edit_retiro_de_dinero">
                     <input type="hidden" class="form-control form-control-rounded" id="id_edicion_retiro_de_dinero">

                 </div>
                 <div class="mb-3">
                     <label for="exampleFormControlTextarea1" class="form-label">Concepto</label>
                     <textarea class="form-control" id="concepto_retiros" rows="3"></textarea>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger">Cancelar</button>
                 <button type="button" class="btn btn-success" onclick="actualizar_retiro_de_dinero()">Guardar</button>
             </div>
         </div>
     </div>
 </div>
 <script>
     ///punto en números 

     const edit_retiro_de_efectivo =
         document.querySelector("#edit_retiro_de_dinero");

     function formatNumber(n) {
         n = String(n).replace(/\D/g, "");
         return n === "" ? n : Number(n).toLocaleString();
     }
     edit_retiro_de_efectivo.addEventListener("keyup", (e) => {
         const element = e.target;
         const value = element.value;
         element.value = formatNumber(value);
     });
 </script>