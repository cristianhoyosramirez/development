function calcular_propina(propina) {

    var criterio_propina = document.getElementById("criterio_propina_final").value;
    var subtotal = document.getElementById("subtotal_pedido").value;
    var subtotalLimpio = subtotal.replace(/\$|\.+/g, "");

    temp_propina = 0;

    if (propina === "") {
        temp_propina = 0;
    } else {
        temp_propina = propina
    }

   

    if (criterio_propina == 1) {

        if (temp_propina >= 0 && temp_propina < 100) {
            let porcentaje = parseInt(temp_propina) / 100
            valor_propina = subtotalLimpio * porcentaje
            var propinaFormateada = valor_propina.toLocaleString('es-ES');

            total = parseInt(valor_propina) + parseInt(subtotalLimpio)

            $('#propina_del_pedido').val(propinaFormateada)
            $('#total_propina').val(propinaFormateada)

            if (total >= 0) {
                $('#valor_pedido').html(total.toLocaleString('es-ES'))
            }

        }

        actualizar_propina(valor_propina)


    }


    if (criterio_propina == 2) {

        const propina_en_pesos = document.querySelector("#propina_pesos");

        function formatNumber(n) {
            // Elimina cualquier carácter que no sea un número
            n = n.replace(/\D/g, "");
            // Formatea el número
            return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
        }

        propina_en_pesos.addEventListener("input", (e) => {
            const element = e.target;
            const value = element.value;
            element.value = formatNumber(value);
        });

        var propina_pesos = document.getElementById("propina_pesos").value;
        var propina_pesos_limpio = subtotal.replace(/\$|\.+/g, "");

        total = parseInt(propina_pesos_limpio) + parseInt(subtotalLimpio)

        actualizar_propina(propina_pesos_limpio)

        //$('#propina_del_pedido').val(propinaFormateada)
        $('#valor_pedido').html(total.toLocaleString('es-ES'))

    }




}