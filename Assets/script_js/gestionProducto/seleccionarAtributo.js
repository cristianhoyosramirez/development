
async function seleccionarAtributo(idAtributo) {
    let idProducto = document.getElementById('idProductoAtributo').value;
    

    try {
        url = document.getElementById('url').value;
        //let response = await fetch(`${url}producto/productosAtributos`, {
            let response = await fetch(`${url}/producto/productosAtributos`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idProducto: idProducto,
                    idAtributo: idAtributo
                })
            });

            if(!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
    }

            let data = await response.json();

    if (data.response === 'success') {
        document.getElementById('resComPro').innerHTML = data.atributos;
        sweet_alert_centrado('success', 'Atributo asociado');
    } else if (data.response === 'exists') {
        sweet_alert_centrado('error', 'Atributo ya ha sido asociado');
    }
} catch (error) {
    console.error('Error en la petición:', error);
}
    }

