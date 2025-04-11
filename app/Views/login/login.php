<?php $session = session(); ?>
<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Bienvendo DF PYME.</title>
    <!-- CSS files -->
    <link href="<?= base_url() ?>/Assets/css/tabler.min.css" rel="stylesheet" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/Assets/img/favicon.png">
    <!-- Pin pad -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/Assets/plugin/pin_pad/css/main.css" />
    <style>
        .table-numeric {
            width: 100%;
            border-collapse: collapse;
        }

        .table-numeric td {
            vertical-align: top;
            text-align: center;
            width: 33.33333333333%;
            border: 0;
        }

        .table-numeric button {
            position: relative;
            cursor: pointer;
            display: block;
            width: 100%;
            box-sizing: border-box;
            padding: 0.6em 0.3em;
            font-size: 1em;
            border-radius: 0.1em;
            outline: none;
            user-select: none;

        }
    </style>
</head>

<body>

    <div>
        <div class="text-center">
            <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?= base_url() ?>/Assets/img/logo.png" height="36" alt=""></a>
            <h2>Inicio de sesión</h2>
        </div>
        <div class="card border border-2 border-light container">
            <div>
                <br>
                <form action="<?php echo base_url('login/login'); ?>" id="form" method="post">
                    <input type="password" class=" form-control text-center " id="code" maxlength="4" name="pin" onkeyup="pin_login(event, this.value)" onkeydown="checkPressedKey(event)" autofocus>
                    <div class="text-danger" id="error_login"><?= session('errors.pin') ?></div>
                </form>
                <br>
            </div>
            <table class="table-numeric" id="formulario">
                <tbody>
                    <tr>
                        <td><button type="button" class="btn btn-outline-success py-3 px-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '1';">1</button></td>
                        <td><button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '2';">2</button></td>
                        <td><button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '3';">3</button></td>
                    </tr>
                    <tr>
                        <td> <button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '4';">4</button></td>
                        <td><button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '5';">5</button></td>
                        <td> <button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '6';">6</button></td>
                    </tr>
                    <tr>
                        <td> <button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '7';">7</button></td>
                        <td><button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '8';">8</button></td>
                        <td><button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '9';">9</button></td>
                    </tr>

                    <tr>
                        <td> <button type="button" class="btn bg-orange-lt py-3 w-7" onclick="document.getElementById('code').value=document.getElementById('code').value.slice(0, -1);borrar_error()"><!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="10" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <line x1="5" y1="12" x2="11" y2="18" />
                                    <line x1="5" y1="12" x2="11" y2="6" />
                                </svg></button></td>
                        <td> <button type="button" class="btn btn-outline-success py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '0';">0</button></td>
                        <td> <button type="button" class="btn bg-green-lt py-3" onclick="login()"><!-- Download SVG icon from http://tabler-icons.io/i/letter-x -->
                                <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l5 5l10 -10" />
                                </svg></button></td>
                </tbody>
            </table>
        </div>
</body>
<!--jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script>
    function pin_login() {
        var pin_login = document.getElementById("code").value;
        if (pin_login.length == 4) {
            document.getElementById('form').submit();
        }
    }
</script>

<script>
    let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
    let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
    if (mensaje != "") {
        Swal.fire({
            title: mensaje,
            icon: iconoMensaje,
            confirmButtonText: 'ACEPTAR',
            confirmButtonColor: "#2AA13D",
        })
    }
</script>

<script>
    function login() {
        var pin_login = document.getElementById("code").value;
        if (pin_login == "") {
            $('#error_login').html('No hay definido pin ');
        } else if (pin_login != "") {
            if (pin_login.length == 4) {
                document.getElementById('form').submit();
            }
        }
    }
</script>

<script>
    function checkPressedKey(event) {
        // Imprimimos la tecla que se ha pulsado. Funciona con cualquier tecla, incluyendo Control, Shift...
        // Incluso funciona con la tecla de windows :
        // Si es el retroceso, mostrar alerta
        if (event.key === "Backspace")
            $('#error_login').html();
    }
</script>
<script>
    function borrar_error() {
        $('#error_login').html();
    }
</script>

</body>

</html>