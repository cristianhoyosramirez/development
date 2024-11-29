<?php
$user_session = session();
?>
<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar navbar-light">
      <div class="container-xl">
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
                  Administracion
                </span>
              </a>
              <div class="dropdown-menu">
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
                <a class="dropdown-item" href="<?= base_url() ?>/caja/lista_precios">
                  <!-- Download SVG icon from http://tabler-icons.io/i/checkup-list -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M9 14h.01" />
                    <path d="M9 17h.01" />
                    <path d="M12 16l1 1l3 -3" />
                  </svg>Lista precios
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/devolucion/listado">
                  <!-- Download SVG icon from http://tabler-icons.io/i/clipboard -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                  </svg>Cuentas retiro de dinero
                </a>
                <a class="dropdown-item" href="<?= base_url() ?>/devolucion/rubros_listado">
                  <!-- Download SVG icon from http://tabler-icons.io/i/clipboard -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                  </svg>
                  Rubros cuenta retiro
                </a>
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
                  Resolucion de facturación
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
                <a class="dropdown-item" href="<?= base_url() ?>/administracion_impresora/configuracion_pedido">
                  <!-- Download SVG icon from http://tabler-icons.io/i/file -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                  </svg>
                  Configuracion de toma pedido
                </a>
              </div>
          </li>
        <?php } ?>

        <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
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
            <div class="dropdown-menu">
              <a class="dropdown-item" href="<?= base_url() ?>/mesas/todas_las_mesas">
                <!-- Download SVG icon from http://tabler-icons.io/i/building-pavilon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                  <line x1="6" y1="21" x2="6" y2="12" />
                  <line x1="18" y1="21" x2="18" y2="12" />
                  <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                </svg> Mesas
              </a>

              <a class="dropdown-item" href="<?= base_url() ?>/salones/salones">
                <!-- Download SVG icon from http://tabler-icons.io/i/building-pavilon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M3 21h7v-3a2 2 0 0 1 4 0v3h7" />
                  <line x1="6" y1="21" x2="6" y2="12" />
                  <line x1="18" y1="21" x2="18" y2="12" />
                  <path d="M6 12h12a3 3 0 0 0 3 -3a9 8 0 0 1 -9 -6a9 8 0 0 1 -9 6a3 3 0 0 0 3 3" />
                </svg> Salones
              </a>


              <?php if ($user_session->tipo == 0) { ?>
                <a class="dropdown-item" href="<?= base_url() ?>/pedido/pedidos_para_facturar">
                  <!-- Download SVG icon from http://tabler-icons.io/i/eye-check -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="2" />
                    <path d="M12 19c-4 0 -7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7c-.42 .736 -.858 1.414 -1.311 2.033" />
                    <path d="M15 19l2 2l4 -4" />
                  </svg> Ver todos los pedidos
                </a>
              <?php } ?>
            </div>
          </li>
        <?php } ?>

        <?php if ($user_session->tipo == 0) { ?>
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

                <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <!-- Download SVG icon from http://tabler-icons.io/i/device-laptop -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="3" y1="19" x2="21" y2="19" />
                    <rect x="5" y="6" width="14" height="10" rx="1" />
                  </svg> Caja general
                </a>
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
                  <?php } ?>
                </div>
              </div>

              <?php if ($user_session->tipo == 0 || $user_session->tipo == 1) { ?>
                <div class="dropend">
                  <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                    <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                      <path d="M12 3v3m0 12v3" />
                    </svg> Facturación
                  </a>
                  <div class="dropdown-menu">
                    <form action="<?= base_url() ?>/factura_pos/factura_pos" method="post">
                      <input type="hidden" value="<?php echo $user_session->id_usuario; ?>" id="id_usuario" name="id_usuario">
                      <button class="dropdown-item" type="submit">
                        <!-- Download SVG icon from http://tabler-icons.io/i/file-invoice -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                          <line x1="9" y1="7" x2="10" y2="7" />
                          <line x1="9" y1="13" x2="15" y2="13" />
                          <line x1="13" y1="17" x2="15" y2="17" />
                        </svg> Facturación
                      </button>
                    </form>
                    <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/duplicado_factura">
                      <!-- Download SVG icon from http://tabler-icons.io/i/box-multiple -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="7" y="3" width="14" height="14" rx="2" />
                        <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                      </svg> Copia de factura
                    </a>
                  </div>
                </div>
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
              <a class="dropdown-item" href="<?= base_url() ?>/consultas_y_reportes/informe_fiscal_de_ventas">
                <!-- Download SVG icon from http://tabler-icons.io/i/file-search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                  <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                  <circle cx="16.5" cy="17.5" r="2.5" />
                  <line x1="18.5" y1="19.5" x2="21" y2="22" />
                </svg>Informe fiscal de ventas diarias
              </a>

              <div class="dropend">
                <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                  <!-- Download SVG icon from http://tabler-icons.io/i/checkup-list -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                    <rect x="9" y="3" width="6" height="4" rx="2" />
                    <path d="M9 14h.01" />
                    <path d="M9 17h.01" />
                    <path d="M12 16l1 1l3 -3" />
                  </svg>
                  Reporte de caja diaria
                </a>
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
                  <a href="<?= base_url() ?>/consultas_y_reportes/index" class="dropdown-item">
                     
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
                    </svg> Fecha</a>
                  <a href="<?= base_url() ?>/consultas_y_reportes/producto_agrupados" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <circle cx="6" cy="19" r="2" />
                      <circle cx="17" cy="19" r="2" />
                      <path d="M17 17h-11v-14h-2" />
                      <path d="M6 5l14 1l-1 7h-13" />
                    </svg> Categoria</a>
                  <a href="<?= base_url() ?>/consultas_y_reportes/consulta_cartera" class="dropdown-item">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                      <path d="M12 3v3m0 12v3" />
                    </svg> Crédito con saldo </a>
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
                 <!--  <a href="<?= base_url() ?>/consultas_y_reportes/producto" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <line x1="7" y1="4" x2="17" y2="20" />
                      <line x1="17" y1="4" x2="7" y2="20" />
                    </svg> Productos borrados de pedido</a> -->
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
                <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <line x1="9" y1="6" x2="20" y2="6" />
                  <line x1="9" y1="12" x2="20" y2="12" />
                  <line x1="9" y1="18" x2="20" y2="18" />
                  <line x1="5" y1="6" x2="5" y2="6.01" />
                  <line x1="5" y1="12" x2="5" y2="12.01" />
                  <line x1="5" y1="18" x2="5" y2="18.01" />
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

              <a class="dropdown-item" href="<?= base_url() ?>/producto/lista_de_productos">
                <!-- Download SVG icon from http://tabler-icons.io/i/file-search -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                  <path d="M12 21h-5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v4.5" />
                  <circle cx="16.5" cy="17.5" r="2.5" />
                  <line x1="18.5" y1="19.5" x2="21" y2="22" />
                </svg>Producto
              </a>
            </div>
          </li>
        <?php } ?>

        </ul>
      </div>
    </div>
  </div>
</div>