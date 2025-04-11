<?php $user_session = session(); ?>
<header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="#">
                <img src="<?php echo base_url(); ?>/Assets/img/logo.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
            </a>
        </h1>
        
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(<?= base_url() ?>/Assets/img/usuario.png)"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div><?php echo $user_session->nombre_usuario; ?></div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="7" r="4" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                        Usuario:<?php echo $user_session->usuario; ?>
                    </a>
                    <a href="<?= base_url() ?>/login/closeSesion" class="dropdown-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/power -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 6a7.75 7.75 0 1 0 10 0" />
                            <line x1="12" y1="4" x2="12" y2="12" />
                        </svg> Cerrar sesion
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>