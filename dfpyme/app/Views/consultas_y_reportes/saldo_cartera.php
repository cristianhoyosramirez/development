


<script>
    /**
     * Aufoco en el modal de creacion de cliente en el modulo de facturar pedido
     */
    $(function() {
        $("#abono_saldo_cartera").on("shown.bs.modal", function(e) {
            $("#abono_general_cartera").focus();
        });
    });


    const abono =
        document.querySelector("#abono_general_cartera");

    function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
    }
    abono.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });

    const efectivo =
        document.querySelector("#efectivo_abono_cartera");

    function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
    }
    efectivo.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });

    const transaccion =
        document.querySelector("#transaccion_abono_cartera");

    function formatNumber(n) {
        n = String(n).replace(/\D/g, "");
        return n === "" ? n : Number(n).toLocaleString();
    }
    transaccion.addEventListener("keyup", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });
</script>

<script>
    /**
     * Pasar de un campo a otro con la tecla enter 
     */
    function saltar(e, id) {
        // Obtenemos la tecla pulsada

        e.keyCode ? (k = e.keyCode) : (k = e.which);

        // Si la tecla pulsada es enter (codigo ascii 13)

        if (k == 13) {
            // Si la variable id contiene "submit" enviamos el formulario

            if (id == "submit") {
                document.forms[0].submit();
            } else {
                // nos posicionamos en el siguiente input

                document.getElementById(id).focus();
            }
        }
    }
</script>


<script>
      function abono_efectivo_credito() {
        var saldo_pendiente = document.getElementById("saldo_factura_credito").value;
        var saldo = parseInt(saldo_pendiente.replace(/[.]/g, ""));

        var abono_cliente = document.getElementById("abono_factura_credito").value;
        var abono = parseInt(abono_cliente.replace(/[.]/g, ""));

        if (abono > saldo) {
          $('#abono_mayor_que_saldo').html('El abono supera el saldo ')
        }



      }
</script>