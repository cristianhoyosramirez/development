function saltar_factura_pos(e, id) {
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