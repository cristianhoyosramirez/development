<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE CUENTAS RERIRO DE DINERO
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">
            <a href="<?php echo base_url('devolucion/crear_rubro'); ?>" class="btn btn-warning btn-pill w-100">Agregar rubro</a>
        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">LISTA GENERAL CUENTAS DE RUBROS DE DINERO </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="accordion" id="accordionExample">
                    <?php foreach ($rubros as $detalle) { ?>
                        <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px;">
                            <h2 class="accordion-header" id="heading<?php echo $detalle['id']; ?>">
                                <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse<?php echo $detalle['id']; ?>"
                                    aria-expanded="false"
                                    aria-controls="collapse<?php echo $detalle['id']; ?>">
                                    <?php echo $detalle['nombre_cuenta'] ?>
                                </button>
                            </h2>
                            <div id="collapse<?php echo $detalle['id']; ?>"
                                class="accordion-collapse collapse"
                                aria-labelledby="heading<?php echo $detalle['id']; ?>"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p class="text-primary h3">Rubros</p>
                                    <?php
                                    $rubrosLista = model('rubrosModel')
                                        ->where('id_cuenta_retiro', $detalle['id'])
                                        ->orderBy('nombre_rubro', 'asc')
                                        ->findAll();
                                    ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($rubrosLista as $detalleRubro): ?>
                                            <span class="badge rounded-pill btn-outline-primary d-flex align-items-center p-3 fs-5"
                                                style="min-width: 150px; max-width: 250px; justify-content: space-between;"
                                                id="badge<?php echo $detalleRubro['id']; ?>">
                                                <?php echo $detalleRubro['nombre_rubro']; ?>
                                                <button type="button" class="btn-close ms-2" aria-label="Close"
                                                    onclick="eliminarBadge(<?php echo $detalleRubro['id']; ?>, '<?php echo $detalleRubro['nombre_rubro']; ?>')">
                                                </button>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

<script>
    async function eliminarBadge(id, nombre) {
        const confirmacion = await Swal.fire({
            title: "¿Estás seguro de eliminar ? " + nombre,
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        });

        if (!confirmacion.isConfirmed) {
            return; // Si el usuario cancela, no hace nada
        }

        try {
            const response = await fetch("<?= base_url('devolucion/deleteRubro') ?>", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id
                }) // Enviar el ID en JSON
            });


            const resultado = await response.json(); // Esperar la respuesta JSON

            if (resultado.response = "success") {
                // Eliminar el elemento del DOM
                document.getElementById('badge' + resultado.id).remove();

                // Mostrar alerta de éxito
                sweet_alert_centrado('success', 'Rubro eliminando')
            } else {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo eliminar el componente.",
                    icon: "error"
                });
            }
        } catch (error) {
            console.error("Error en la petición:", error);
            Swal.fire({
                title: "Error",
                text: "Ocurrió un problema con la eliminación.",
                icon: "error"
            });
        }
    }
</script>


<?= $this->endSection('content') ?>
<!-- end row -->