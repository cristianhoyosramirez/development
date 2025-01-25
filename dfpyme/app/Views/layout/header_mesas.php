<?php $user_session = session(); ?>
<header class="navbar navbar-expand-md navbar-light d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 d-none d-sm-block">
      <a href="<?php echo base_url(); ?>/pedidos/mesas" class="d-none d-sm-block">
        <img src="<?php echo base_url(); ?>/Assets/img/logo.png" width="110" height="32" alt="Macondo" class="navbar-brand-image">
      </a>

    </h1>
    <div class="d-block d-sm-block d-md-block d-lg-none ">
      <?php echo $user_session->usuario; ?>
    </div>
    <div class="navbar-nav flex-row order-md-last">
      <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Habilitar modo oscuro " data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-toggle="tooltip" data-bs-placement="bottom">
        <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
        </svg>
      </a>
      <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Habilitar modo claro" data-bs-toggle="tooltip" data-bs-placement="bottom">
        <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <circle cx="12" cy="12" r="4" />
          <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
        </svg>
      </a>
      <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" title="Ocultar menú" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="ocultar_menu()">
          <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <circle cx="12" cy="12" r="2" />
            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
          </svg>

        </a>

      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm" style="background-image: url(<?= base_url() ?>/Assets/img/usuario.png)"></span>
          <div class="d-none d-xl-block ps-2">
            <div><input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
              <?php echo $user_session->usuario; ?></div>

          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
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
    <?php $id_tipo = model('empresaModel')->select('fk_tipo_empresa')->first() ?>


    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
        <ul class="navbar-nav">
          <li class="nav-item active dropdown  <?= service('request')->uri->getPath() == '' ? 'is_active' : '' ?>">
            <?php if ($user_session->tipo == 0) { ?>
              <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="text-yellow">
                  <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Administración
                </span>
              </a>
              <div class="dropdown-menu">

                <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>

                  <a class="dropdown-item" href="<?= base_url() ?>/salones/list">
                    <!-- Download SVG icon from http://tabler-icons.io/i/building-pavilon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                      <line x1="6" y1="21" x2="6" y2="12" />
                      <line x1="18" y1="21" x2="18" y2="12" />
                      <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                    </svg> Salones
                  </a>
                <?php endif ?>
                <?php if ($id_tipo['fk_tipo_empresa'] == 2 || $id_tipo['fk_tipo_empresa'] == 3) : ?>

                  <a class="dropdown-item" href="<?= base_url() ?>/salones/list">
                    <!-- Download SVG icon from http://tabler-icons.io/i/building-pavilon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                      <line x1="6" y1="21" x2="6" y2="12" />
                      <line x1="18" y1="21" x2="18" y2="12" />
                      <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                    </svg> Zonas
                  </a>
                <?php endif ?>

                <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>
                  <a class="dropdown-item" href="<?= base_url() ?>/mesas/list">
                    <!-- Download SVG icon from http://tabler-icons.io/i/cup -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M5 11h14v-3h-14z" />
                      <path d="M17.5 11l-1.5 10h-8l-1.5 -10" />
                      <path d="M6 8v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
                      <path d="M15 5v-2" />
                    </svg> Mesas
                  </a>
                <?php endif ?>
                <?php if ($id_tipo['fk_tipo_empresa'] == 2) : ?>
                  <a class="dropdown-item" href="<?= base_url() ?>/mesas/list">
                    <!-- Download SVG icon from http://tabler-icons.io/i/cup -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M5 11h14v-3h-14z" />
                      <path d="M17.5 11l-1.5 10h-8l-1.5 -10" />
                      <path d="M6 8v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
                      <path d="M15 5v-2" />
                    </svg> Zonas facturación
                  </a>
                <?php endif ?>
                <a class="dropdown-item" href="<?= base_url() ?>/usuarios/list">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user-circle -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="9" />
                    <circle cx="12" cy="10" r="3" />
                    <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
                  </svg> Usuarios
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/impresora/listado">
                  <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <rect x="7" y="13" width="10" height="8" rx="2" />
                  </svg> Impresoras
                </a>
                <!-- <a class="dropdown-item" href="<?= base_url() ?>/configuracion/estacion_trabajo">
                  
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="9" y1="14" x2="15" y2="8" />
                    <circle cx="9.5" cy="8.5" r=".5" fill="currentColor" />
                    <circle cx="14.5" cy="13.5" r=".5" fill="currentColor" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                  </svg> Estación de trabajo
                </a> -->

                <!--
                 <a class="dropdown-item" href="<?= base_url() ?>/pre_factura/impresora">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                    <path d="M12 3v3m0 12v3" />
                  </svg> Precuenta
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/cajon_monedero">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M13 12v.01" />
                    <path d="M3 21h18" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h7.5m2.5 10.5v7.5" />
                    <path d="M14 7h7m-3 -3l3 3l-3 3" />
                  </svg> Apertura cajon monedero
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/impresion_factura">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <rect x="7" y="13" width="10" height="8" rx="2" />
                  </svg> Impresora facturación
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/impresion_factura">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <rect x="7" y="13" width="10" height="8" rx="2" />
                  </svg> Impresora facturación
                </a> -->
                <!--  <a class="dropdown-item" href="<?= base_url() ?>/caja/lista_precios">
               Download SVG icon from http://tabler-icons.io/i/checkup-list 
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M9 14h.01" />
                    <path d="M9 17h.01" />
                    <path d="M12 16l1 1l3 -3" />
                  </svg>Lista precios
                </a> -->

                <!--  <a class="dropdown-item" href="<?= base_url() ?>/devolucion/listado">
                  
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                  </svg>Cuentas retiro de dinero
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/devolucion/rubros_listado">
                  
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                  </svg>
                  Rubros cuenta retiro
                </a> -->

                <a class="dropdown-item" href="<?= base_url() ?>/empresa/datos">
                  <!-- Download SVG icon from http://tabler-icons.io/i/businessplan -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <ellipse cx="16" cy="6" rx="5" ry="3" />
                    <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                    <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                    <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" />
                    <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                    <path d="M5 15v1m0 -8v1" />
                  </svg>
                  Empresa
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/empresa/resolucion_facturacion">
                  <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <line x1="9" y1="7" x2="10" y2="7" />
                    <line x1="9" y1="13" x2="15" y2="13" />
                    <line x1="13" y1="17" x2="15" y2="17" />
                  </svg>
                  Resolución de facturación POS
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/empresa/resolucion_electronica">
                  <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <line x1="9" y1="7" x2="10" y2="7" />
                    <line x1="9" y1="13" x2="15" y2="13" />
                    <line x1="13" y1="17" x2="15" y2="17" />
                  </svg>
                  Resolución de facturación electrónica
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/empresa/consecutivos">
                  <!-- Download SVG icon from http://tabler-icons.io/i/arrow-narrow-right -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <line x1="15" y1="16" x2="19" y2="12" />
                    <line x1="15" y1="8" x2="19" y2="12" />
                  </svg>
                  Consecutivos
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/empresa/comprobante_transaccion">
                  <!-- Download SVG icon from http://tabler-icons.io/i/file -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                  </svg>
                  Comprobante de transferencia electrónica
                </a>
                <!-- <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/configuracion_pedido">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                  </svg>
                  Configuración de toma pedido
                </a>-->
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/mesero">
                  <!-- Download SVG icon from http://tabler-icons.io/i/hand-click -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" />
                    <path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" />
                    <path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" />
                    <path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" />
                    <path d="M5 3l-1 -1" />
                    <path d="M4 7h-1" />
                    <path d="M14 3l1 -1" />
                    <path d="M15 6h1" />
                  </svg>
                  Asignación de usuario a venta
                </a>
                <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>
                  <a class="dropdown-item" href="<?= base_url() ?>/configuracion/propina">
                    <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                      <path d="M12 3v3m0 12v3" />
                    </svg>
                    Calculo propina
                  </a>
                <?php endif ?>
                <!--  <a class="dropdown-item" href="<?= base_url() ?>/configuracion/sub_categoria">
                 Download SVG icon from http://tabler-icons.io/i/check 
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l5 5l10 -10" />
                  </svg>
                  Configuración sub categoria
                </a>-->
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/tipos_de_factura">
                  <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                    <line x1="9" y1="7" x2="10" y2="7" />
                    <line x1="9" y1="13" x2="15" y2="13" />
                    <line x1="13" y1="17" x2="15" y2="17" />
                  </svg>
                  Tipos de factura
                </a>
                <!--  <a class="dropdown-item" href="<?= base_url() ?>/configuracion/borrar_remisiones">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M19 19h-11l-4 -4a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9 9" />
                    <line x1="18" y1="12.3" x2="11.7" y2="6" />
                  </svg>
                  Borrar remisiones
                </a> -->
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/borrado_masivo">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M19 19h-11l-4 -4a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9 9" />
                    <line x1="18" y1="12.3" x2="11.7" y2="6" />
                  </svg>
                  Gestión
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/actualizacion/parametrizacion">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M19 19h-11l-4 -4a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9 9" />
                    <line x1="18" y1="12.3" x2="11.7" y2="6" />
                  </svg>
                  Gestión de pedidos
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/comanda">
                  <!-- Download SVG icon from http://tabler-icons.io/i/list-details -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M13 5h8" />
                    <path d="M13 9h5" />
                    <path d="M13 15h8" />
                    <path d="M13 19h5" />
                    <rect x="3" y="4" width="6" height="6" rx="1" />
                    <rect x="3" y="14" width="6" height="6" rx="1" />
                  </svg>
                  Comanda
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/productos_favoritos">
                  <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart-plus -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="6" cy="19" r="2" />
                    <circle cx="17" cy="19" r="2" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13" />
                    <path d="M15 6h6m-3 -3v6" />
                  </svg>
                  Productos favoritos
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/eventos/venta_multiple">
                  <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart-plus -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="6" cy="19" r="2" />
                    <circle cx="17" cy="19" r="2" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13" />
                    <path d="M15 6h6m-3 -3v6" />
                  </svg>
                  Venta multiple
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/encabezado">
                  <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart-plus -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="6" cy="19" r="2" />
                    <circle cx="17" cy="19" r="2" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l6.005 .429m7.138 6.573l-.143 .998h-13" />
                    <path d="M15 6h6m-3 -3v6" />
                  </svg>
                  Encabezado y pie de factura electrónica
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/configuracion/productos_impuestos">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                  </svg>
                  Productos con impuestos
                </a>
                <a class="dropdown-item text-green" href="<?= base_url() ?>/actualizacion/Bd">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0" />
                    <path d="M4 6v6a8 3 0 0 0 16 0v-6" />
                    <path d="M4 12v6a8 3 0 0 0 16 0v-6" />
                  </svg>
                  Sincronización
                </a>

              </div>
          </li>
        <?php } ?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#navbar-url" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
            <span class="text-purple">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-link">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M9 15l6 -6" />
                <path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" />
                <path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" />
              </svg>
            </span>
            <span class="nav-link-title">
              URL
            </span>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="<?= base_url() ?>/configuracion/sincronizar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rotate-clockwise">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4.05 11a8 8 0 1 1 .5 4m-.5 5v-5h5" />
              </svg>
              Sincronizar
            </a>
            <a class="dropdown-item" href="<?= base_url() ?>/configuracion/asignar">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
              </svg>
              Asignar
            </a>
          </div>
        </li>


        <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
          <li class="nav-item dropdown">

            <?php if ($id_tipo['fk_tipo_empresa'] == 1) : ?>
              <a class="nav-link " href="<?= base_url() ?>/pedidos/mesas">
                <span class="text-blue">
                  <!-- Download SVG icon from http://tabler-icons.io/i/coffee -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 14c.83 .642 2.077 1.017 3.5 1c1.423 .017 2.67 -.358 3.5 -1c.83 -.642 2.077 -1.017 3.5 -1c1.423 -.017 2.67 .358 3.5 1" />
                    <path d="M8 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                    <path d="M12 3a2.4 2.4 0 0 0 -1 2a2.4 2.4 0 0 0 1 2" />
                    <path d="M3 10h14v5a6 6 0 0 1 -6 6h-2a6 6 0 0 1 -6 -6v-5z" />
                    <path d="M16.746 16.726a3 3 0 1 0 .252 -5.555" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Pedidos
                </span>
              </a>
            <?php endif ?>
            <?php if ($id_tipo['fk_tipo_empresa'] == 2 || $id_tipo['fk_tipo_empresa'] == 3) : ?>
              <a class="nav-link " href="<?= base_url() ?>/pedidos/mesas">
                <span class="text-blue">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
                    <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                  </svg>
                </span>
                <span class="nav-link-title">
                  Vender
                </span>
              </a>
            <?php endif ?>
            <div class="dropdown-menu">

              <a class="dropdown-item" href="<?= base_url() ?>/pedidos/mesas">
                <!-- Download SVG icon from http://tabler-icons.io/i/cup -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M5 11h14v-3h-14z" />
                  <path d="M17.5 11l-1.5 10h-8l-1.5 -10" />
                  <path d="M6 8v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
                  <path d="M15 5v-2" />
                </svg> Dispositivos moviles
              </a>
              <a class="dropdown-item" href="<?= base_url() ?>/pedidos/mesas">
                <!-- Download SVG icon from http://tabler-icons.io/i/cup -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M5 11h14v-3h-14z" />
                  <path d="M17.5 11l-1.5 10h-8l-1.5 -10" />
                  <path d="M6 8v-1a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v1" />
                  <path d="M15 5v-2" />
                </svg> Pedidos
              </a>
            </div>
          </li>
        <?php } ?>

        <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="text-orange">
                <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="6" cy="19" r="2" />
                  <circle cx="17" cy="19" r="2" />
                  <path d="M17 17h-11v-14h-2" />
                  <path d="M6 5l14 1l-1 7h-13" />
                </svg>
              </span>
              <span class="nav-link-title">
                Ventas
              </span>
            </a>
            <div class="dropdown-menu">
              <div class="dropend">
                <a class="dropdown-item" href="<?= base_url() ?>/clientes/tabla_todos_los_clientes">
                  <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="7" r="4" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                  </svg>Clientes
                </a>

                <!--  <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="3" y1="19" x2="21" y2="19" />
                    <rect x="5" y="6" width="14" height="10" rx="1" />
                  </svg> Caja general
                </a> -->
                <div class="dropdown-menu">
                  <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                  <a class="dropdown-item" href="<?= base_url() ?>/caja_general/apertura_general">
                    <!-- Download SVG icon from http://tabler-icons.io/i/lock-open -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <rect x="5" y="11" width="14" height="10" rx="2" />
                      <circle cx="12" cy="16" r="1" />
                      <path d="M8 11v-5a4 4 0 0 1 8 0" />
                    </svg>Apertura de caja general
                  </a>
                  <a class="dropdown-item" href="<?= base_url() ?>/caja_general/cierre_general">
                    <!-- Download SVG icon from http://tabler-icons.io/i/lock -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <rect x="5" y="11" width="14" height="10" rx="2" />
                      <circle cx="12" cy="16" r="1" />
                      <path d="M8 11v-4a4 4 0 0 1 8 0v4" />
                    </svg>Cierre de caja general
                  </a>
                  <?php if ($user_session->tipo == 0) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>/caja_general/consulta_general">
                      <!-- Download SVG icon from http://tabler-icons.io/i/question-mark -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                        <line x1="12" y1="19" x2="12" y2="19.01" />
                      </svg>Consultas de caja general
                    </a>
                  <?php } ?>
                </div>
                <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <!-- Download SVG icon from http://tabler-icons.io/i/device-laptop -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="3" y1="19" x2="21" y2="19" />
                    <rect x="5" y="6" width="14" height="10" rx="1" />
                  </svg> Caja
                </a>
                <div class="dropdown-menu">
                  <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                  <a class="dropdown-item" href="<?= base_url() ?>/caja/apertura">
                    <!-- Download SVG icon from http://tabler-icons.io/i/device-desktop -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <rect x="3" y="4" width="18" height="12" rx="1" />
                      <line x1="7" y1="20" x2="17" y2="20" />
                      <line x1="9" y1="16" x2="9" y2="20" />
                      <line x1="15" y1="16" x2="15" y2="20" />
                    </svg>Apertura de caja
                  </a>
                  <a class="dropdown-item" href="<?= base_url() ?>/caja/cierre">
                    <!-- Download SVG icon from http://tabler-icons.io/i/device-desktop -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <rect x="3" y="4" width="18" height="12" rx="1" />
                      <line x1="7" y1="20" x2="17" y2="20" />
                      <line x1="9" y1="16" x2="9" y2="20" />
                      <line x1="15" y1="16" x2="15" y2="20" />
                    </svg>Cierre de caja
                  </a>
                  <?php if ($user_session->tipo == 0) { ?>
                    <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/consultas_caja">
                      <!-- Download SVG icon from http://tabler-icons.io/i/question-mark -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                        <line x1="12" y1="19" x2="12" y2="19.01" />
                      </svg>Consultas de caja
                    </a>
                    <!--    <a class="dropdown-item" href="<?= base_url() ?>/configuracion/admin_imp">
                      
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="5" y="11" width="14" height="10" rx="2" />
                        <circle cx="12" cy="16" r="1" />
                        <path d="M8 11v-5a4 4 0 0 1 8 0" />
                      </svg>Abrir cajon monedero
                    </a> -->
                  <?php } ?>
                </div>
              </div>

              <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
                <div class="dropend">
                  <!--  <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                      <path d="M12 3v3m0 12v3" />
                    </svg> Facturación
                  </a> -->


                  <div class="dropend">
                    <!--    <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">

                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="7" y="3" width="14" height="14" rx="2" />
                        <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                      </svg>
                      Copia de factura
                    </a> -->
                    <div class="dropdown-menu">
                      <a href="<?= base_url() ?>/consultas_y_reportes/duplicado_factura" class="dropdown-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                          <line x1="9" y1="7" x2="10" y2="7" />
                          <line x1="9" y1="13" x2="15" y2="13" />
                          <line x1="13" y1="17" x2="15" y2="17" />
                        </svg>
                        POS </a>
                      <a href="<?= base_url() ?>/pedidos/lista_electronicas" class="dropdown-item">
                        <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                          <line x1="9" y1="7" x2="10" y2="7" />
                          <line x1="9" y1="13" x2="15" y2="13" />
                          <line x1="13" y1="17" x2="15" y2="17" />
                        </svg>
                        Electrónica</a>

                    </div>
                  </div>
                  <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
                    <a href="<?= base_url() ?>/eventos/consultar_ventas" class="dropdown-item">
                      <!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12h4l3 8l4 -16l3 8h4" />
                      </svg>
                      Consultar ventas</a>
                </div>
              <?php } ?>
            <?php } ?>

            </div>
          </li>
        <?php } ?>


        <?php if ($user_session->tipo == 0) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="text-green">
                <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                  <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                  <line x1="9" y1="7" x2="10" y2="7" />
                  <line x1="9" y1="13" x2="15" y2="13" />
                  <line x1="13" y1="17" x2="15" y2="17" />
                </svg>
              </span>
              <span class="nav-link-title">
                Consultas y reportes
              </span>
            </a>
            <div class="dropdown-menu">

              <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
              <!-- <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/informe_fiscal_de_ventas">
              
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                  <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                  <circle cx="16.5" cy="17.5" r="2.5" />
                  <line x1="18.5" y1="19.5" x2="21" y2="22" />
                </svg>Informe fiscal de ventas diarias
              </a>-->

              <div class="dropend">
                <!--<a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
               
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M9 14h.01" />
                    <path d="M9 17h.01" />
                    <path d="M12 16l1 1l3 -3" />
                  </svg>
                  Reporte de caja diaria
                </a>-->
                <div class="dropdown-menu">
                  <a href="<?= base_url() ?>/consultas_y_reportes/reporte_caja_diaria" class="dropdown-item"><!-- Download SVG icon from http://tabler-icons.io/i/clipboard-list -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                      <rect x="9" y="3" width="6" height="4" rx="2" />
                      <line x1="9" y1="12" x2="9.01" y2="12" />
                      <line x1="13" y1="12" x2="15" y2="12" />
                      <line x1="9" y1="16" x2="9.01" y2="16" />
                      <line x1="13" y1="16" x2="15" y2="16" />
                    </svg>Generar informe fiscal</a>
                  <a href="<?= base_url() ?>/consultas_y_reportes/reporte_caja_diario" class="dropdown-item"><!-- Download SVG icon from http://tabler-icons.io/i/list-check -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                      <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                      <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                      <line x1="11" y1="6" x2="20" y2="6" />
                      <line x1="11" y1="12" x2="20" y2="12" />
                      <line x1="11" y1="18" x2="20" y2="18" />
                    </svg>Ver reportes de caja generados</a>
                </div>
              </div>

              <div class="dropend">
                <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="6" cy="19" r="2" />
                    <circle cx="17" cy="19" r="2" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                  Reporte de ventas
                </a>
                <div class="dropdown-menu">

                  <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                  <!-- <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/informe_fiscal_de_ventas">
                        
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                        <circle cx="16.5" cy="17.5" r="2.5" />
                        <line x1="18.5" y1="19.5" x2="21" y2="22" />
                      </svg>Informe fiscal de ventas diarias
                    </a>-->



                  <!--  <a href="<?= base_url() ?>/consultas_y_reportes/index" class="dropdown-item">

                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="3" y="4" width="18" height="12" rx="1" />
                        <line x1="7" y1="20" x2="17" y2="20" />
                        <line x1="9" y1="16" x2="9" y2="20" />
                        <line x1="15" y1="16" x2="15" y2="20" />
                        <path d="M8 12l3 -3l2 2l3 -3" />
                      </svg>
                      General </a> 
                    <a href="<?= base_url() ?>/consultas_y_reportes/producto" class="dropdown-item">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="4" y="5" width="16" height="16" rx="2" />
                        <line x1="16" y1="3" x2="16" y2="7" />
                        <line x1="8" y1="3" x2="8" y2="7" />
                        <line x1="4" y1="11" x2="20" y2="11" />
                        <line x1="11" y1="15" x2="12" y2="15" />
                        <line x1="12" y1="15" x2="12" y2="18" />
                      </svg> Fecha</a>-->
                  <a href="<?= base_url() ?>/reportes/reporte_costo" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-infographic" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M7 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                      <path d="M7 3v4h4"></path>
                      <path d="M9 17l0 4"></path>
                      <path d="M17 14l0 7"></path>
                      <path d="M13 13l0 8"></path>
                      <path d="M21 12l0 9"></path>
                    </svg>De Costo</a>
                  <a href="<?= base_url() ?>/reportes/reportes_ventas" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                      <path d="M12 3v3m0 12v3" />
                    </svg> De Ventas</a>
                  <a href="<?= base_url() ?>/consultas_y_reportes/producto_agrupados" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <circle cx="6" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M17 17h-11v-14h-2" />
                      <path d="M6 5l14 1l-1 7h-13" />
                    </svg> Por Producto</a>
                  <a href="<?= base_url() ?>/consultas_y_reportes/impuestos" class="dropdown-item">
                    <!-- Download SVG icon from http://tabler-icons.io/i/chart-donut -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-3.8a4.1 4.1 0 1 1 -5 -5v-4a0.9 .9 0 0 0 -1 -.8" />
                      <path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a9 9 0 0 0 -1 -1v-4.5" />
                    </svg> Impuestos </a>


                  <!--  -->
                  <!--  <a href="<?= base_url() ?>/consultas_y_reportes/consulta_cartera" class="dropdown-item">

                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                        <path d="M12 3v3m0 12v3" />
                      </svg> Crédito con saldo </a> -->
                </div>
              </div>

              <div class="dropend">
                <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                    <path d="M12 17v1m0 -8v1" />
                  </svg>
                  Pedidos
                </a>
                <div class="dropdown-menu">
                  <a href="<?= base_url() ?>/consultas_y_reportes/buscar_pedidos_borrados" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M19 19h-11l-4 -4a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9 9" />
                      <line x1="18" y1="12.3" x2="11.7" y2="6" />
                    </svg>
                    Pedidos eliminados </a>
                  <a href="<?= base_url() ?>/reportes/productos_borrados" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="7" y1="4" x2="17" y2="20" />
                      <line x1="17" y1="4" x2="7" y2="20" />
                    </svg> Productos borrados de pedido</a>
                </div>
              </div>

              <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/reporte_flujo_efectivo">
                <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                  <path d="M12 3v3m0 12v3" />
                </svg>Egresos
              </a>

              <!-- <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/ventas_de_mesero">
                Download SVG icon from http://tabler-icons.io/i/user-check 
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <circle cx="9" cy="7" r="4" />
                  <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                  <path d="M16 11l2 2l4 -4" />
                </svg>Ventas de mesero
              </a>-->

            </div>
          </li>
        <?php } ?>
        <?php if ($user_session->tipo == 0) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="text-blue">
                <!-- Download SVG icon from http://tabler-icons.io/i/pencil -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                  <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                </svg>
              </span>
              <span class="nav-link-title">
                Inventario
              </span>
            </a>
            <div class="dropdown-menu">

              <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">

              <a class="dropdown-item" href="<?= base_url() ?>/categoria/marcas">
                <!-- Download SVG icon from http://tabler-icons.io/i/ticket -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="15" y1="5" x2="15" y2="7" />
                  <line x1="15" y1="11" x2="15" y2="13" />
                  <line x1="15" y1="17" x2="15" y2="19" />
                  <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                </svg> Marcas
              </a>

              <a class="dropdown-item" href="<?= base_url() ?>/categoria/index">
                <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="9" y1="6" x2="20" y2="6" />
                  <line x1="9" y1="12" x2="20" y2="12" />
                  <line x1="9" y1="18" x2="20" y2="18" />
                  <line x1="5" y1="6" x2="5" y2="6.01" />
                  <line x1="5" y1="12" x2="5" y2="12.01" />
                  <line x1="5" y1="18" x2="5" y2="18.01" />
                </svg> Categorias
              </a>
              <a class="dropdown-item" href="<?= base_url() ?>/configuracion/crear_sub_categoria">
                <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="9" y1="6" x2="20" y2="6" />
                  <line x1="9" y1="12" x2="20" y2="12" />
                  <line x1="9" y1="18" x2="20" y2="18" />
                  <line x1="5" y1="6" x2="5" y2="6.01" />
                  <line x1="5" y1="12" x2="5" y2="12.01" />
                  <line x1="5" y1="18" x2="5" y2="18.01" />
                </svg> Sub categorias
              </a>

              <a class="dropdown-item" href="<?= base_url() ?>/producto/lista_de_productos">
                <!-- Download SVG icon from http://tabler-icons.io/i/bottle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M10 5h4v-2a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v2z" />
                  <path d="M14 3.5c0 1.626 .507 3.212 1.45 4.537l.05 .07a8.093 8.093 0 0 1 1.5 4.694v6.199a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2v-6.2c0 -1.682 .524 -3.322 1.5 -4.693l.05 -.07a7.823 7.823 0 0 0 1.45 -4.537" />
                  <path d="M7.003 14.803a2.4 2.4 0 0 0 .997 -.803a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 1 -.805" />
                </svg>Producto
              </a>


              <div class="dropend">
                <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">

                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                    <path d="M12 17v1m0 -8v1" />
                  </svg>
                  Inventario
                </a>
                <div class="dropdown-menu">
                  <a href="<?= base_url() ?>/inventario/ingreso" class="dropdown-item">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="12" y1="5" x2="12" y2="19" />
                      <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Entradas </a>
                  <a href="<?= base_url() ?>/inventario/salida" class="dropdown-item">
                    <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="5" y1="12" x2="19" y2="12" />
                    </svg> Salidas</a>
                  <a href="<?= base_url() ?>/administracion_impresora/inventario" class="dropdown-item">
                    <!-- Download SVG icon from http://tabler-icons.io/i/building-skyscraper -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="3" y1="21" x2="21" y2="21" />
                      <path d="M5 21v-14l8 -4v18" />
                      <path d="M19 21v-10l-6 -4" />
                      <line x1="9" y1="9" x2="9" y2="9.01" />
                      <line x1="9" y1="12" x2="9" y2="12.01" />
                      <line x1="9" y1="15" x2="9" y2="15.01" />
                      <line x1="9" y1="18" x2="9" y2="18.01" />
                    </svg> Consultar inventario </a>

                </div>
              </div>

              <a class="dropdown-item" href="<?= base_url() ?>/inventario/consultar_entrada_salida">
                <!-- Download SVG icon from http://tabler-icons.io/i/brand-producthunt -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M10 16v-8h2.5a2.5 2.5 0 1 1 0 5h-2.5" />
                  <circle cx="12" cy="12" r="9" />
                </svg>Movimiento de producto
              </a>
              <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/cruce_inventario">
                <!-- Download SVG icon from http://tabler-icons.io/i/arrows-horizontal -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <polyline points="7 8 3 12 7 16" />
                  <polyline points="17 8 21 12 17 16" />
                  <line x1="3" y1="12" x2="21" y2="12" />
                </svg>Cruce de inventario
              </a>
              <a class="dropdown-item" href="<?= base_url() ?>/categoria/productos_categoria">
                <!-- Download SVG icon from http://tabler-icons.io/i/clipboard-list -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                  <rect x="9" y="3" width="6" height="4" rx="2" />
                  <line x1="9" y1="12" x2="9.01" y2="12" />
                  <line x1="13" y1="12" x2="15" y2="12" />
                  <line x1="9" y1="16" x2="9.01" y2="16" />
                  <line x1="13" y1="16" x2="15" y2="16" />
                </svg>Categorias producto
              </a>

            </div>
          </li>
        <?php } ?>
        <?php if ($user_session->tipo == 0) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="text-blue">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-currency-dollar">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                  <path d="M12 3v3m0 12v3" />
                </svg>
              </span>
              <span class="nav-link-title">
                Compras
              </span>
            </a>
            <div class="dropdown-menu">

              <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">

              <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/proveedor">
                <!-- <img src="<?php echo base_url(); ?>/Assets/img/proveedor.png" width="20" height="20" alt="Macondo" class="navbar-brand-image"> Proveedores -->
                <!-- Download SVG icon from http://tabler-icons.io/i/checkup-list -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                  <rect x="9" y="3" width="6" height="4" rx="2" />
                  <path d="M9 14h.01" />
                  <path d="M9 17h.01" />
                  <path d="M12 16l1 1l3 -3" />
                </svg> Proveedor
              </a>

              <div class="dropend">
                <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">

                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M17 17h-11v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                  </svg>
                  Compras
                </a>
                <div class="dropdown-menu">
                  <a href="<?= base_url() ?>/edicion_eliminacion_factura_pedido/ingresar_compra" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart-plus">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                      <path d="M12.5 17h-6.5v-14h-2" />
                      <path d="M6 5l14 1l-.86 6.017m-2.64 .983h-10.5" />
                      <path d="M16 19h6" />
                      <path d="M19 16v6" />
                    </svg>
                    Ingresar </a>
                  <a href="<?= base_url() ?>/inventario/consultar_compras" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-message-question">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M8 9h8" />
                      <path d="M8 13h6" />
                      <path d="M14 18h-1l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                      <path d="M19 22v.01" />
                      <path d="M19 19a2.003 2.003 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" />
                    </svg> Consultar compras </a>
                </div>
              </div>

            </div>
          </li>
        <?php } ?>

        <!--         <?php if ($user_session->tipo == 0) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="text-blue">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                  <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                </svg>
              </span>
              <span class="nav-link-title">
                Eventos
              </span>
            </a>
            <div class="dropdown-menu">

              <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">

              <a class="dropdown-item" href="<?= base_url() ?>/eventos/boletas">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="15" y1="5" x2="15" y2="7" />
                  <line x1="15" y1="11" x2="15" y2="13" />
                  <line x1="15" y1="17" x2="15" y2="19" />
                  <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                </svg> Boleteria
              </a>

              <a class="dropdown-item" href="<?= base_url() ?>/eventos/consultar_boleta">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="15" y1="5" x2="15" y2="7" />
                  <line x1="15" y1="11" x2="15" y2="13" />
                  <line x1="15" y1="17" x2="15" y2="19" />
                  <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                </svg> Cover
              </a>




            </div>
          </li>
        <?php } ?> -->
        </ul>
      </div>
    </div>
  </div>
</header>