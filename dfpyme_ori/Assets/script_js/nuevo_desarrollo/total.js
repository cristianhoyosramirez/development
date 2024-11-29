function total() {
    var precio = document.getElementById("precio_devolucion").value;
    var cantidad = document.getElementById("cantidad_devolucion").value;

    // Reemplazar puntos en el precio para formatearlo correctamente
    var precioFormat = precio.replace(/[.]/g, "");

    // Verificar si cantidad es 0 o está vacío
    if (cantidad === "" || parseInt(cantidad) === 0) {
        res = 0;
        precioFormat = 0;
    } else {
        res = parseInt(precioFormat) * parseInt(cantidad);
    }

    // Formatear el resultado para mostrar separadores de miles
    var resultado = res.toLocaleString();

    // Asignar el resultado formateado al input correspondiente
    document.getElementById("total_devolucion").value = resultado;
}
