
    async function maxComponentes(valor, idProductoAtributo) {
        try {
            url =  document.getElementById('url').value;
            let response = await fetch(`${url}/producto/updateNumeroComponentes`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    idProductoAtributo: idProductoAtributo,
                    valor: valor
                }) // Enviar el id del producto
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            let data = await response.json();

            if (data.response === "true") {
                document.getElementById('resComPro').innerHTML = data.atributos;
                $('#componentesProducto').modal('show');
            } else if (data.response === "false") {
                document.getElementById('resComPro').innerHTML = "";
                $('#componentesProducto').modal('show');
            }
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    }
