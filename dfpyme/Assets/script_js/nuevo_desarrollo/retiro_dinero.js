function retiro_dinero() {

    // Agrega un event listener para cuando se muestra el modal
    document.getElementById('modal_retiro_dinero').addEventListener('shown.bs.modal', function () {
        // Selecciona el input y pon el foco en él
        document.getElementById('valor_retiro').focus();
    })
    $("#modal_retiro_dinero").modal("show");
}