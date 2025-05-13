<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Fecha</th>
            <td scope="col">Valor</th>
            <td scope="col">Concepto</th>
            <td scope="col">Acción</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($retiros as $valor) : ?>
            <tr>
                <td><?php echo $valor['fecha'] ?></th>
                <td><?php echo "$ " . number_format($valor['valor'], 0, ",", ".") ?></th>
                <td><?php echo $valor['concepto'] ?></td>
                <td>
                    <div class="row">

                        <div class="col text-end">
                            <a href="#" onclick="imprimir(<?php echo $valor['idretiro'] ?>)" class="btn btn-outline-success  btn-icon" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Imprimir retiro">
                                <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                                    <rect x="7" y="13" width="10" height="8" rx="2" />
                                </svg>
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-outline-info btn-icon" title="Editar retiro" onclick="editar(<?php echo $valor['idretiro'] ?>)">
                                <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </td>
            </tr>
        <?php endforeach ?>

    </tbody>
</table>

<script>
    const valor = document.querySelector("#valor");

    function formatNumber(n) {
        // Elimina cualquier carácter que no sea un número
        n = n.replace(/\D/g, "");
        // Formatea el número
        return n === "" ? n : parseFloat(n).toLocaleString('es-CO');
    }

    valor.addEventListener("input", (e) => {
        const element = e.target;
        const value = element.value;
        element.value = formatNumber(value);
    });
</script>



<script>
    function imprimir(id) {
        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id_retiro: id,
            },
            url: url +
                "/" +
                "devolucion/re_imprimir_retiro",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {


                    sweet_alert('success', 'Impreasion  correcta ')
                }
            },
        });
    }
</script>


<script>
    function editar(id) {

        var url = document.getElementById("url").value;
        $("#modal_retiros").modal("hide");

        $.ajax({
            data: {
                id_retiro: id,
            },
            url: url +
                "/" +
                "devolucion/editar_retiro",
            type: "post",
            success: function(resultado) {
                var resultado = JSON.parse(resultado);
                if (resultado.resultado == 1) {

                    $('#valor').val(resultado.valor)
                    $('#concepto').val(resultado.concepto)
                    $('#id').val(resultado.id)

                    $("#modal_editar_retiro").modal("show");

                }
            },
        });

    }
</script>

<p class="text-end h3 ">Total retiros : <?php echo "$ " . number_format($total_retiros, 0, ",", ".") ?></p>