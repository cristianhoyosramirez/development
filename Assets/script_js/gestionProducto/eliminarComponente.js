
    function eliminaComponente(id, idProducto) {
        Swal.fire({
            title: '¿Está seguro de eliminar el componente?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarComponente(id, idProducto);
            }
        });
    }

    async function eliminarComponente(id, idProducto) {
        url =  document.getElementById('url').value;
        try {
            //let response = await fetch("<?= base_url('producto/eliminarComponente') ?>", {
                let response = await fetch(`${url}/producto/eliminarComponente`, { 
                method: "POST", // Cambiado de DELETE a POST para evitar problemas con body
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    idAtributo: id,
                    idProducto: idProducto
                })
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            let data = await response.json();

            if (data.response === "success") {
               sweet_alert_centrado('success', 'Atributo eliminado correctamente');

                // Eliminar el componente de la UI
                //document.getElementById(`componente-${id}`).remove();
                document.getElementById('resComPro').innerHTML = data.atributos
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Hubo un problema al eliminar el componente.',
                    icon: 'error'
                });
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            Swal.fire({
                title: 'Error!',
                text: 'No se pudo eliminar el componente.',
                icon: 'error'
            });
        }
    }

