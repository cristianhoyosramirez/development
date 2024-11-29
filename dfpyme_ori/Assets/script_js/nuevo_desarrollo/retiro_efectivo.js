function retiro_efectivo() {
    var usuario = document.getElementById("id_usuario").value;
    var retiro = document.getElementById("valor_retiro").value;
    var retiroFormat = retiro.replace(/[.]/g, "");
    var valor_retiro = parseInt(retiroFormat);
    var concepto_retiro = document.getElementById("concepto_retiro").value;
    var id_cuenta_rubro = document.getElementById("cuenta_rubro").value;
    var url = document.getElementById("url").value;

    if (valor_retiro < 1 || valor_retiro == "") {
        $("#error_valor_retiro").html("Valor del retiro debe ser mayor a cero ");
    } else {
        if (concepto_retiro == "") {
            $("#error_concepto_retiro").html("Falta el concepto ");
        }
        if (id_cuenta_rubro == "") {
            $("#error_cuenta_rubro").html("Falta la cuenta y el rubro  ");
        } else if (valor_retiro > 0 && concepto_retiro != "" && id_cuenta_rubro != "") {
            $.ajax({
                data: {
                    usuario,
                    valor_retiro,
                    concepto_retiro,
                    id_cuenta_rubro
                },
                url: url + "/" + "devolucion/retiro",
                type: "POST",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    console.log(resultado);
                    if (resultado.resultado == 1) {
                        $('#valor_retiro').val('');
                        $('#concepto_retiro').val('');
                        $("#modal_retiro_dinero").modal("hide");
                        /* Swal.fire({
                          icon: "success",
                          title: "Haz retirado de caja " + " " + resultado.retiro,
                          confirmButtonText: "Aceptar",
                          confirmButtonColor: "#2AA13D",
                        }); */
                        $("#valor_retirado").html('Haz retirado de caja' + ' ' + '$' + resultado.retiro);
                        document.getElementById("id_retiro").value = resultado.id_retiro
                        document.getElementById("id_usuario_retiro").value = resultado.id_usuario
                        myModal = new bootstrap.Modal(
                            document.getElementById("imprimir_retiro"), {}
                        );
                        myModal.show();
                    }

                    if (resultado.resultado==0){
                        sweet_alert_start('warning','No hay apertura de caja ')
                    }
                },
            });
        }
    }
}