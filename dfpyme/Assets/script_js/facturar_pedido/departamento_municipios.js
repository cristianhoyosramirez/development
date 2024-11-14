
$(document).ready(function() {
    $('#departamento_pedido').val(0);
    recargarLista();
    $('#departamento_pedido').change(function() {
        recargarLista();
    });
})

function recargarLista() {
    var url = document.getElementById("url").value;
    $.ajax({
        type: "POST",
        //url: "<?php echo base_url(); ?>/factura_pos/municipios",
        url: url + "/" + "factura_pos/municipios",
        data: "id_departamento=" + $('#departamento_pedido').val(),
        success: function(r) {
            $('#municipios_pedido').html(r);
        }
    });
}
