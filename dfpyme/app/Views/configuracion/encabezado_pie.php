<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-6">
            <form method="post">
                <input type="hidden" value="<?php echo base_url() ?>" id="url">
                <label for="" class="form-label ">
                    <p class="text-primary h3">Encabezado de factura electronica</p>
                </label>
                <textarea id="texto_encabezado" class="tinymce-default form-control" rows="14">
                    <?php echo $encabezado ?>
                </textarea><br>
                <div class="text-end">
                    <a href="#" class="btn btn-outline-success " onclick="obtenerValor()">
                        Guardar
                    </a>
                </div>
            </form>
        </div>
        <div class="col-6">
            <form method="post">
                <label for="" class="form-label text-primary h3 ">
                    <p class="text-primary h3">Pie de factura electronica</p>
                </label>
                <textarea id="text_pie" rows="14" class="form-control"> <?php echo $pie ?></textarea>
                <br>
                <div class="text-end">
                    <a href="#" class="btn btn-outline-success " onclick="obtenerValorEncabezado()">
                        Guardar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url() ?>/Assets/plugin/tinymce/tinymce.min.js?1684106062" defer></script>
    <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>


    <script>
        /*     document.addEventListener("DOMContentLoaded", function() {
            let options = {
                selector: '#texto_encabezadod',
                height: 300,
                menubar: false,
                statusbar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
            }
            if (localStorage.getItem("tablerTheme") === 'dark') {
                options.skin = 'oxide-dark';
                options.content_css = 'dark';
            }
            tinyMCE.init(options);
        }); */

        function obtenerValor() {
            //let content = tinyMCE.get('texto_encabezado').getContent();
            let content = document.getElementById("texto_encabezado").value;
            let url = document.getElementById("url").value;

            $.ajax({
                data: {
                    valor: content
                },
                url: url +
                    "/" +
                    "configuracion/actualizar_encabezado",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 900,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Modificación correcta '
                        });


                    }
                },
            });

        }
    </script>

    <script>
        /*   document.addEventListener("DOMContentLoaded", function() {
            let options = {
                selector: '#text_pie',
                height: 300,
                menubar: false,
                statusbar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
            }
            if (localStorage.getItem("tablerTheme") === 'dark') {
                options.skin = 'oxide-dark';
                options.content_css = 'dark';
            }
            tinyMCE.init(options);
        }); */

        function obtenerValorEncabezado() {
            // let content = tinyMCE.get('text_pie').getContent();
            let content = document.getElementById("text_pie").value;
            let url = document.getElementById("url").value;

            $.ajax({
                data: {
                    valor: content
                },
                url: url +
                    "/" +
                    "configuracion/actualizar_pie",
                type: "post",
                success: function(resultado) {
                    var resultado = JSON.parse(resultado);
                    if (resultado.resultado == 1) {

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-start',
                            showConfirmButton: false,
                            timer: 900,
                            timerProgressBar: false,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseleave', Swal.resumeTimer);
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'modificacion correcta '
                        });


                    }
                },
            });

        }
    </script>




    <?= $this->endSection('content') ?>