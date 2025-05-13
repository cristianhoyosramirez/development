async function agregarProductoPedido(codigoproducto, nombreProducto, id, valorVenta) {

    let url = document.getElementById("url").value;

    document.getElementById('productoAddAtri').innerHTML = nombreProducto


    let boton = document.getElementById('btnAtri');

    // Remueve cualquier onclick en el HTML
    boton.removeAttribute('onclick');

    // Asigna uno nuevo
    boton.setAttribute('onclick', 'addOtherProduct()');
    try {
        const response = await fetch(`${url}/producto/validarAtributosDeProducto`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: JSON.stringify({
                id: id,

            })
        });

        const resultado = await response.json();

        if (resultado.response == 'success') {

            //document.getElementById('valUnidad').value = valorVenta
            let valorFormateado = new Intl.NumberFormat('es-CO').format(valorVenta);
            document.getElementById('valorUnidad').innerHTML = `$ ${valorFormateado}`;
            document.getElementById('totalProducto').innerHTML = `$ ${valorFormateado}`;
            document.getElementById('asigCompo').innerHTML = resultado.atributos
            document.getElementById('codigoInternoProd').value = codigoproducto
            document.getElementById('input_cantidadAtri').value = 1

            document.getElementById("divInput").style.display = "block";
            document.getElementById("inputCantidad").style.display = "none";
            document.getElementById('notaAtributo').value = "";

            $('#modalAtributos').modal('show');
        }
    } catch (error) {
        console.error("Error en la petición:", error);
    }

}
