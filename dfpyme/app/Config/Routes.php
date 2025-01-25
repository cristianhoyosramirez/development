<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');

$routes->group('salones', ['namespace' => 'App\Controllers\Salones', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('list', 'salonesController::index');
    $routes->get('salones', 'salonesController::salones'); //consulta de todos los salones creados 
    $routes->get('datos_iniciales', 'salonesController::datos_iniciales'); //consulta de todos los salones creados 
    $routes->post('mesas', 'salonesController::salon_mesas');
    $routes->post('save', 'salonesController::save');
    $routes->post('edit', 'salonesController::editar');
    $routes->post('update', 'salonesController::actualizar');
    $routes->get('consultar_mesa', 'salonesController::consultar_mesa');
});

$routes->group('login', ['namespace' => 'App\Controllers\login'], function ($routes) {
    $routes->post('login', 'loginController::login');
    $routes->get('closeSesion', 'loginController::closeSesion');
});

$routes->get('home', 'Home::index');


$routes->group('home', ['Home::index', 'filter' => \App\Filters\Auth::class], function ($routes) {});




$routes->group('usuarios', ['namespace' => 'App\Controllers\usuarios', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('list', 'usuariosController::index');
    $routes->post('edit', 'usuariosController::editar');
    $routes->post('update', 'usuariosController::actualizar');
    $routes->post('eliminar', 'usuariosController::eliminar');
    $routes->post('crear', 'usuariosController::crear');
});


$routes->group('mesas', ['namespace' => 'App\Controllers\Mesa', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('list', 'mesaController::index');
    $routes->get('add', 'mesaController::datos_iniciales');
    $routes->post('save', 'mesaController::save');
    $routes->post('pedido', 'mesaController::mesaPedido');
    $routes->post('editar', 'mesaController::editar');
    $routes->post('cambiar_de_mesa', 'mesaController::cambiar_de_mesa');
    $routes->post('actualizar', 'mesaController::actualizar');
    $routes->get('todas_las_mesas', 'mesaController::todas_las_mesas');
    $routes->post('intercambio_mesa', 'mesaController::intercambio_mesa');
});

$routes->group('producto', ['namespace' => 'App\Controllers\producto', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('pedido', 'productoController::productoPedido');
    $routes->post('pedido_pos', 'productoController::pedido_pos');
    $routes->post('agregar_producto_al_pedido', 'productoController::agregar_producto_al_pedido');
    $routes->post('insertar_productos_tabla_pedido', 'productoController::insertar_productos_tabla_pedido');
    $routes->post('buscar_productos_id_categoria', 'productoController::buscar_productos_id_categoria');
    $routes->post('agregar_productos_x_categoria', 'productoController::agregar_productos_x_categoria');
    $routes->post('productos_del_pedido_para_facturar', 'productoController::productos_del_pedido_para_facturar');
    $routes->post('detalle_pedido', 'productoController::detalle_pedido');
    $routes->post('editar_cantidades_de_pedido', 'productoController::editar_cantidades_de_pedido');
    $routes->post('buscar_por_codigo_de_barras', 'productoController::buscar_por_codigo_de_barras');
    $routes->post('cargar_producto_al_pedido', 'productoController::cargar_producto_al_pedido');
    $routes->post('entregar_producto', 'productoController::entregar_producto');
    $routes->post('actualizar_entregar_producto', 'productoController::actualizar_entregar_producto');
    $routes->post('usuario_pedido', 'productoController::usuario_pedido');
    $routes->post('agregar_observacion_general', 'productoController::agregar_observacion_general');
    $routes->post('facturar_pedido', 'productoController::facturar_pedido');
    $routes->post('insertar_producto_desde_categoria', 'productoController::insertar_producto_desde_categoria');
    $routes->post('actualizar_cantidades_de_pedido', 'productoController::actualizar_cantidades_de_pedido');
    $routes->post('eliminar_producto', 'productoController::eliminar_producto');
    $routes->post('eliminacion_de_producto', 'productoController::eliminacion_de_producto');
    $routes->post('cargar_item_al_pedido', 'productoController::cargar_item_al_pedido');
    $routes->post('editar_con_pin', 'productoController::editar_con_pin');
    $routes->post('eliminar_con_pin_pad', 'productoController::eliminar_con_pin_pad');
    $routes->post('editar_con_pin_pad', 'productoController::editar_con_pin_pad');
    $routes->post('crear', 'operacionesProductoController::crear');
    $routes->post('imagen', 'operacionesProductoController::imagen');
    $routes->post('lista_precios', 'operacionesProductoController::lista_precios');
    $routes->get('actualizar_factura_venta', 'productoController::actualizar_factura_venta');
    $routes->post('listado', 'productoController::index');
    $routes->post('get_codigo_interno', 'operacionesProductoController::get_codigo_interno');
    $routes->post('categorias', 'operacionesProductoController::categorias');
    $routes->post('marcas', 'operacionesProductoController::marcas');
    $routes->post('iva', 'operacionesProductoController::iva');
    $routes->post('ico', 'operacionesProductoController::ico');
    $routes->get('lista_de_productos', 'productoController::lista_de_productos');
    $routes->post('index', 'productoController::index');
    $routes->post('creacion_producto', 'operacionesProductoController::creacion_producto');
    $routes->post('editar_precios', 'operacionesProductoController::editar_precios');
    $routes->post('actualizar_precio_producto', 'operacionesProductoController::actualizar_precio_producto');
    $routes->post('eliminar_producto_inventario', 'operacionesProductoController::eliminar_producto_inventario');
    $routes->post('borrar_producto_inventario', 'operacionesProductoController::borrar_producto_inventario');
    $routes->post('eliminacion_de_pedido_desde_pedido', 'operacionesProductoController::eliminacion_de_pedido_desde_pedido');
    $routes->post('actualizacion_cantidades', 'productoController::actualizacion_cantidades');
    $routes->post('autorizacion_pin', 'operacionesProductoController::autorizacion_pin');
    $routes->post('eliminar_pedido_usuario', 'operacionesProductoController::eliminar_pedido_usuario');
    $routes->post('entrada_salida', 'operacionesProductoController::entrada_salida');
});

$routes->group('impresora', ['namespace' => 'App\Controllers\impresora', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('listado', 'impresoraController::index');
    $routes->get('datos_iniciales', 'impresoraController::datos_iniciales');
    $routes->post('salvar', 'impresoraController::salvar');
    $routes->post('editar', 'impresoraController::editar');
    $routes->post('actualizar', 'impresoraController::actualizar');
    $routes->get('administracion', 'impresoraController::administracion');
});

$routes->group('categoria', ['namespace' => 'App\Controllers\categoria', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('index', 'categoriaController::index');
    $routes->get('crear', 'categoriaController::crear');
    $routes->post('actualizar', 'categoriaController::actualizar');
    $routes->post('guardar', 'categoriaController::guardar');
    $routes->post('actualizar_estado_categoria', 'categoriaController::actualizar_estado_categoria');
    $routes->post('actualizar_impresora', 'categoriaController::actualizar_impresora');
    $routes->post('actualizar_nombre', 'categoriaController::actualizar_nombre');
    $routes->get('marcas', 'categoriaController::get_todas_las_marcas_producto');
    $routes->post('crear_marcas', 'categoriaController::crear_marcas');
    $routes->post('editar_marca', 'categoriaController::editar_marca');
    $routes->post('actualizar_marca', 'categoriaController::actualizar_marca');
    $routes->post('sub_categoria', 'categoriaController::sub_categoria');
    $routes->post('actualizar_sub_categoria', 'categoriaController::actualizar_sub_categoria');
    $routes->post('consulta_sub_categoria', 'categoriaController::consulta_sub_categoria');
    $routes->get('productos_categoria', 'categoriaController::productos_categoria');
    $routes->post('actualizar_productos', 'categoriaController::actualizar_productos');
    $routes->post('actualizacion_productos', 'categoriaController::actualizacion_productos');
    $routes->post('componentes_producto', 'categoriaController::componentes_producto');
    $routes->get('verRecetas', 'categoriaController::verRecetas');
});

$routes->group('pedido', ['namespace' => 'App\Controllers\pedido', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('pedidos_para_facturar', 'pedidoController::index');
    $routes->get('pedidos_para_facturacion', 'pedidoController::pedidos_para_facturacion');
    $routes->post('eliminacion_de_pedido', 'pedidoController::eliminacion_de_pedido');
    $routes->post('agregar_nota_al_pedido', 'pedidoController::agregar_nota_al_pedido');
    $routes->post('facturar_pedido', 'pedidoController::facturar_pedido');
    $routes->post('cerrar_venta', 'pedidoController::cerrar_venta');
    $routes->post('nota_de_pedido', 'pedidoController::nota_de_pedido');
    $routes->post('valor_pedido', 'pedidoController::valor_pedido');
    $routes->post('total_pedido', 'pedidoController::total_pedido');
    $routes->post('forma_pago', 'pedidoController::forma_pago');
    $routes->post('facturar_credito', 'pedidoController::facturar_credito');
    $routes->post('borrar_pedido', 'pedidoController::borrar_pedido');
    $routes->post('actualizar_cantidades', 'pedidoController::actualizar_cantidades');
    $routes->post('eliminar_cantidades', 'pedidoController::eliminar_cantidades');
    $routes->post('pedido', 'pedidoController::pedido');
});

$routes->group('factura_pos', ['namespace' => 'App\Controllers\factura_pos', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('factura_pos', 'facturacionConImpuestosController::factura_pos');
    $routes->get('factura_pos', 'facturacionConImpuestosController::factura_pos');
    $routes->post('facturacion_pos', 'facturacionConImpuestosController::facturacion_pos');
    $routes->get('pedidos_para_facturacion', 'pedidoController::pedidos_para_facturacion');
    $routes->get('cerrar_venta', 'facturacionConImpuestosController::cerrar_venta');
    $routes->post('imprimir_factura', 'facturacionConImpuestosController::imprimir_factura');
    $routes->post('imprimir_factura_desde_pedido', 'facturacionConImpuestosController::imprimir_factura_desde_pedido');
    $routes->post('municipios', 'facturacionConImpuestosController::municipios');
    $routes->post('imprimir_factura_sin_impuestos', 'facturacionSinImpuestosController::imprimir_factura');
    $routes->post('imprimir_factura_sin_impuestos_directa', 'facturacionSinImpuestosController::imprimir_factura_directa');
    $routes->post('imprimir_factura_partir_factura', 'facturacionSinImpuestosController::imprimir_factura_partir_factura');
    $routes->post('cerrar_venta_partir_factura', 'facturacionSinImpuestosController::cerrar_venta_partir_factura');
    $routes->post('reset_factura', 'facturacionSinImpuestosController::reset_factura');
    $routes->get('modulo_facturacion', 'facturacionConImpuestosController::modulo_facturacion');
});

$routes->group('comanda', ['namespace' => 'App\Controllers\comanda', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('imprimir_comanda', 'imprimirComandaController::imprimir_comanda');
    $routes->post('imprimir_comanda_desde_pedido', 'imprimirComandaController::imprimir_comanda_desde_pedido');
    $routes->post('re_imprimir_comanda', 'imprimirComandaController::re_imprimir_comanda');
    $routes->post('directa', 'imprimirComandaController::directa');
    $routes->post('imprimir_compra', 'imprimirComandaController::imprimir_compra');
    $routes->post('impresion_compra', 'imprimirComandaController::impresion_compra');
    $routes->post('imprimir_movimiento', 'imprimirComandaController::imprimir_movimiento');
});

$routes->group('clientes', ['namespace' => 'App\Controllers\cliente', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('listado', 'clienteController::index');
    $routes->post('agregar', 'clienteController::agregar');
    $routes->post('clientes_autocompletado', 'clienteController::clientes_autocompletado');
    $routes->get('todos_los_clientes', 'clienteController::todos_los_clientes');
    $routes->get('tabla_todos_los_clientes', 'clienteController::tabla_todos_los_clientes');
    $routes->post('nuevo_cliente', 'clienteController::nuevo_cliente');
    $routes->post('editar_cliente', 'clienteController::editar_cliente');
    $routes->post('actualizar_datos_cliente', 'clienteController::actualizar_datos_cliente');
});

$routes->group('pre_factura', ['namespace' => 'App\Controllers\pre_factura', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('imprimir', 'prefacturaController::imprimir');
    $routes->post('imprimir_prefactura', 'prefacturaController::imprimir_prefactura');
    $routes->post('imprimir_desde_pedido', 'prefacturaController::imprimir_desde_pedido');
    $routes->get('impresora', 'prefacturaController::impresora');
    $routes->post('asignar_impresora', 'prefacturaController::asignar_impresora');
    $routes->post('buscar_por_codigo', 'prefacturaController::buscar_por_codigo');
    $routes->post('cruzarInventario', 'prefacturaController::cruzarInventario');
    $routes->get('productosIva', 'prefacturaController::productosIva');
    $routes->get('productosInc', 'prefacturaController::productosInc');
    $routes->post('ingresarInv', 'prefacturaController::ingresarInv');
    $routes->post('buscarProducto', 'prefacturaController::buscarProducto');
    $routes->post('busqueda', 'prefacturaController::busqueda');
    $routes->post('busquedaCategoria', 'prefacturaController::busquedaCategoria');
});

$routes->group('clientes', ['namespace' => 'App\Controllers\clientes', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('list', 'clienteController::index');
    $routes->get('add', 'clientesController::agregar_cliente');
});

$routes->group('factura_electronica', ['namespace' => 'App\Controllers\factura_electronica', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('pre_factura', 'FacturaElectronica::pre_factura');
});


$routes->group('factura_directa', ['namespace' => 'App\Controllers\factura_pos', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('facturacion', 'facturaDirectaController::facturacion');
    $routes->post('imprimir_comanda', 'imprimirComandaController::imprimir_comanda');
    $routes->post('eliminar_producto', 'facturaDirectaController::eliminar_producto');
    $routes->post('eliminacion_de_producto', 'facturaDirectaController::eliminacion_de_producto');
    $routes->get('factura_pos', 'facturaDirectaController::factura_pos');
    $routes->post('comanda_directa', 'facturaDirectaController::comanda_directa');
});

$routes->group('administracion_impresora', ['namespace' => 'App\Controllers\administracion_impresora', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('apertutraCajonMonedero', 'aperturaCajonMonederoController::apertutra_cajon_monedero');
    $routes->get('cajon_monedero', 'aperturaCajonMonederoController::cajon_monedero');
    $routes->get('impresion_factura', 'impresionFacturaController::impresion_factura');
    $routes->post('asignar_impresora_facturacion', 'impresionFacturaController::asignar_impresora_facturacion');
    $routes->get('configuracion_pedido', 'impresionFacturaController::configuracion_pedido');
    $routes->post('actualizar_configuracion_pedido', 'impresionFacturaController::actualizar_configuracion_pedido');
    $routes->get('proveedor', 'impresionFacturaController::proveedor');
    $routes->post('crear_proveedor', 'impresionFacturaController::crear_proveedor');
    $routes->post('editar_proveedor', 'impresionFacturaController::editar_proveedor');
    $routes->post('actualizar_proveedor', 'impresionFacturaController::actualizar_proveedor');
    $routes->get('inventario', 'impresionFacturaController::inventario');
    $routes->get('ProductosInventario', 'impresionFacturaController::ProductosInventario');
});


$routes->group('edicion_eliminacion_factura_pedido', ['namespace' => 'App\Controllers\pedido', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('edicion', 'edicionEliminacionFacturaPedidoController::edicion');
    $routes->post('edicion_pos', 'edicionEliminacionFacturaPedidoController::edicion_pos');
    $routes->post('actualizar_producto_pedido', 'edicionEliminacionFacturaPedidoController::actualizar_producto_pedido');
    $routes->post('actualizar_producto_pos', 'edicionEliminacionFacturaPedidoController::actualizar_producto_pos');
    $routes->post('actualizar_precio_pedido', 'edicionEliminacionFacturaPedidoController::actualizar_precio_pedido');
    $routes->post('eliminar_producto_pedido', 'edicionEliminacionFacturaPedidoController::eliminar_producto_pedido');
    $routes->post('borrar_producto', 'edicionEliminacionFacturaPedidoController::borrar_producto');
    $routes->post('actualizar_registro_factura_directa', 'edicionEliminacionFacturaPedidoController::actualizar_registro_factura_directa');
    $routes->get('ingresar_compra', 'edicionEliminacionFacturaPedidoController::ingresar_compra');
});


$routes->group('partir_factura', ['namespace' => 'App\Controllers\pedido', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('partir_factura', 'partirFacturaController::partir_factura');
    $routes->post('consultar_total', 'partirFacturaController::consultar_total');
    $routes->post('facturar', 'partirFacturaController::facturar');
    $routes->post('cancelar_partir_factura', 'partirFacturaController::cancelar_partir_factura');
    $routes->post('actualizar_cantidad_tabla_partir_factura', 'partirFacturaController::actualizar_cantidad_tabla_partir_factura');
});

$routes->group('consultas_y_reportes', ['namespace' => 'App\Controllers\consultas_y_reportes', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('informe_fiscal_de_ventas', 'informeFiscalVentasController::informe_fiscal_ventas');
    $routes->get('duplicado_factura', 'duplicadoFacturaController::duplicado_factura');
    $routes->post('informe_fiscal_de_ventas_datos', 'informeFiscalVentasController::informe_fiscal_ventas_datos');
    $routes->post('facturas_por_rango_de_fechas', 'duplicadoFacturaController::facturas_por_rango_de_fechas');
    $routes->post('detalle_factura', 'duplicadoFacturaController::detalle_factura');
    $routes->post('imprimir_duplicado_factura', 'duplicadoFacturaController::imprimir_duplicado_factura');
    $routes->get('informe_fiscal_ventas_pdf', 'informeFiscalVentasController::informe_fiscal_ventas_pdf');
    $routes->get('generar_informe_fiscal_ventas_pdf', 'informeFiscalVentasController::generar_informe_fiscal_ventas_pdf');
    $routes->get('reporte_caja_diaria', 'cajaDiariaController::reporte_caja_diaria');
    $routes->get('index', 'reporteDeVentasController::index');
    $routes->get('producto', 'reporteDeVentasController::producto');
    $routes->post('informe_caja', 'cajaDiariaController::informe_caja');
    $routes->post('reporte_caja_diaria_datos', 'cajaDiariaController::reporte_caja_diaria_datos');
    $routes->post('reporte_caja_diaria_datos_ico', 'cajaDiariaController::reporte_caja_diaria_datos_ico');
    $routes->post('guardar_reporte_caja_diaria', 'cajaDiariaController::guardar_reporte_caja_diaria');
    $routes->post('solo_guardar_reporte_caja_diaria', 'cajaDiariaController::solo_guardar_reporte_caja_diaria');
    $routes->get('imprimir_reporte_de_caja_id', 'cajaDiariaController::imprimir_reporte_de_caja_id');
    $routes->post('imprimir_reporte_caja_diaria', 'cajaDiariaController::imprimir_reporte_caja_diaria');
    $routes->get('consulta_de_ventas', 'reporteDeVentasController::consulta_de_ventas');
    $routes->get('consultas_caja', 'reporteDeVentasController::consultas_caja');
    $routes->post('consultas_caja_por_fecha', 'reporteDeVentasController::consultas_caja_por_fecha');
    $routes->post('datos_consultas_caja_por_fecha', 'reporteDeVentasController::datos_consultas_caja_por_fecha');
    $routes->post('detalle_de_ventas', 'reporteDeVentasController::detalle_de_ventas');
    $routes->post('detalle_de_ventas_sin_cierre', 'reporteDeVentasController::detalle_de_ventas_sin_cierre');
    $routes->post('datos_consultar_producto', 'reporteDeVentasController::datos_consultar_producto');
    $routes->post('tabla_consultar_producto', 'reporteDeVentasController::tabla_consultar_producto');
    $routes->get('producto_agrupados', 'reporteDeVentasController::producto_agrupados');
    $routes->post('consultar_producto_agrupado', 'reporteDeVentasController::consultar_producto_agrupado');
    $routes->post('datos_consultar_producto_agrupado', 'reporteDeVentasController::datos_consultar_producto_agrupado');
    $routes->post('valor_apertura', 'reporteDeVentasController::valor_apertura');
    $routes->post('actualizar_efectivo_usuario', 'reporteDeVentasController::actualizar_efectivo_usuario');
    $routes->post('actualizar_transaccion_usuario', 'reporteDeVentasController::actualizar_transaccion_usuario');
    $routes->get('datos_consultar_producto_agrupado_pdf', 'reporteDeVentasController::datos_consultar_producto_agrupado_pdf');
    $routes->get('reporte_caja_diario', 'reporteDeVentasController::reporte_caja_diario');
    $routes->post('imprimir_reporte_fiscal', 'reporteDeVentasController::imprimir_reporte_fiscal');
    $routes->post('saldo_factura', 'AbonosController::saldo_factura');
    $routes->post('actualizar_saldo', 'AbonosController::actualizar_saldo');
    $routes->post('imprimir_ingreso', 'AbonosController::imprimir_ingreso');
    $routes->get('reporte_flujo_efectivo', 'FlujoEfectivoController::reporte_flujo_efectivo');
    $routes->post('datos_reporte_flujo_efectivo', 'FlujoEfectivoController::datos_reporte_flujo_efectivo');
    $routes->post('excel_reporte_flujo_efectivo', 'FlujoEfectivoController::excel_reporte_flujo_efectivo');
    $routes->post('pdf_reporte_producto', 'reporteDeVentasController::pdf_reporte_producto');
    $routes->post('editar_valor_apertura', 'cajaDiariaController::editar_valor_apertura');
    $routes->post('cambiar_valor_apertura', 'cajaDiariaController::cambiar_valor_apertura');
    $routes->post('total_ingresos_efectivo', 'cajaDiariaController::total_ingresos_efectivo');
    $routes->post('total_ingresos_transaccion', 'cajaDiariaController::total_ingresos_transaccion');
    $routes->post('retiros', 'cajaDiariaController::retiros');
    $routes->post('movimientos_de_caja', 'cajaDiariaController::movimientos_de_caja');
    $routes->post('detalle_movimiento_de_caja', 'cajaDiariaController::detalle_movimiento_de_caja');
    $routes->post('reporte_de_ventas', 'cajaDiariaController::reporte_de_ventas');
    $routes->post('detalle_retiros', 'cajaDiariaController::detalle_retiros');
    $routes->post('editar_valor_cierre', 'cajaDiariaController::editar_valor_cierre');
    $routes->post('actualizar_valor_cierre', 'cajaDiariaController::actualizar_valor_cierre');
    $routes->post('editar_valor_cierre_transferencias', 'cajaDiariaController::editar_valor_cierre_transferencias');
    $routes->post('actualizar_valor_cierre_transferencias', 'cajaDiariaController::actualizar_valor_cierre_transferencias');
    $routes->get('buscar_pedidos_borrados', 'cajaDiariaController::buscar_pedidos_borrados');
    $routes->post('pedidos_borrados', 'cajaDiariaController::pedidos_borrados');
    $routes->post('pedidos_borrados', 'cajaDiariaController::pedidos_borrados');
    $routes->post('informe_fiscal_desde_caja', 'cajaDiariaController::informe_fiscal_desde_caja');
    $routes->post('informe_fiscal_electronico', 'cajaDiariaController::informe_fiscal_electronico');
    $routes->post('expotar_informe_ventas_pdf', 'informeFiscalVentasController::expotar_informe_ventas_pdf');
    $routes->post('fiscal_manual_pdf', 'informeFiscalVentasController::fiscal_manual_pdf');
    $routes->get('documento', 'Documento::documento');
    $routes->get('tipo_documento', 'Documento::tipo_documento');
    $routes->get('cliente', 'Documento::cliente');
    $routes->post('cartera_cliente', 'Documento::cartera_cliente');
    $routes->post('pagar_cartera_cliente', 'Documento::pagar_cartera_cliente');
    $routes->post('imprimir_comprobante_ingreso', 'Documento::imprimir_comprobante_ingreso');
    $routes->get('consulta_cartera', 'Documento::consulta_cartera');
    $routes->post('datos_consulta_cartera', 'Documento::datos_consulta_cartera');
    $routes->get('por_defecto', 'Documento::por_defecto');
    $routes->post('consultar_por_documento', 'Documento::consultar_por_documento');
    $routes->post('consultar_por_cliente', 'Documento::consultar_por_cliente');
    $routes->post('consulta_de_cartera', 'Documento::consulta_de_cartera');
    $routes->post('aperturas', 'Documento::aperturas');
    $routes->get('ventas_de_mesero', 'Documento::ventas_de_mesero');
    $routes->post('expotar_informe_electronico_pdf', 'informeFiscalVentasController::expotar_informe_electronico_pdf');
    $routes->post('reporte_de_ventas_excel', 'informeFiscalVentasController::reporte_de_ventas_excel');
    $routes->get('excel_mov', 'AbonosController::excel_mov');
    $routes->get('impuestos', 'AbonosController::impuestos');
    $routes->get('reporte_impuestos', 'AbonosController::reporte_impuestos');
    $routes->get('cruce_inventario', 'AbonosController::cruce_inventario');
    $routes->get('reporte_cruce_inventarios', 'AbonosController::reporte_cruce_inventarios');
    $routes->get('reporte_sobrantes', 'AbonosController::reporte_sobrantes');
    $routes->get('reporte_faltantes', 'AbonosController::reporte_faltantes');
    $routes->get('productos_inventario', 'AbonosController::productos_inventario');
    $routes->post('Inventario', 'AbonosController::Inventario');
    $routes->get('closeModal', 'AbonosController::closeModal');
});

$routes->group('devolucion', ['namespace' => 'App\Controllers\devolucion', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('guardar_devolucion', 'devolucionController::guardar_devolucion');
    $routes->post('retiro', 'devolucionController::retiro');
    $routes->post('imprimir_retiro', 'devolucionController::imprimir_retiro');
    $routes->post('re_imprimir_retiro', 'devolucionController::re_imprimir_retiro');
    $routes->post('no_imprimir_retiro', 'devolucionController::no_imprimir_retiro');
    $routes->post('edicion_retiro_de_dinero', 'devolucionController::edicion_retiro_de_dinero');
    $routes->post('actualizar_retiro_de_dinero', 'devolucionController::actualizar_retiro_de_dinero');
    $routes->get('crear_cuenta', 'RetiroController::crear_cuenta');
    $routes->post('agregar_cuenta', 'RetiroController::agregar_cuenta');
    $routes->get('listado', 'RetiroController::listado');
    $routes->get('rubros_listado', 'RetiroController::rubros_listado');
    $routes->get('crear_rubro', 'RetiroController::crear_rubro');
    $routes->post('agregar_rubro', 'RetiroController::agregar_rubro');
    $routes->post('cuenta_rubro', 'RetiroController::cuenta_rubro');
    $routes->post('editar_rubro', 'RetiroController::editar_rubro');
    $routes->post('actualizar_rubro', 'RetiroController::actualizar_rubro');
    $routes->post('editar_retiro', 'devolucionController::editar_retiro');
    $routes->post('actualizar_retiro', 'devolucionController::actualizar_retiro');
});
$routes->group('caja', ['namespace' => 'App\Controllers\caja', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('apertura', 'cajaController::apertura');
    $routes->get('lista_precios', 'cajaController::lista_precios');
    $routes->post('listado_precios', 'cajaController::listado_precios');
    $routes->post('generar_apertura', 'cajaController::generar_apertura');
    $routes->get('cierre', 'cajaController::cierre');
    $routes->post('generar_cierre', 'cajaController::generar_cierre');
    $routes->post('imprimir_cierre', 'cajaController::imprimir_cierre');
    $routes->post('imprimir_movimiento_caja', 'cajaController::imprimir_movimiento_caja');
    $routes->post('actualizar_lista_precios', 'cajaController::actualizar_lista_precios');
    $routes->post('imprimir_movimiento_caja_sin_cierre', 'cajaController::imprimir_movimiento_caja_sin_cierre');
    $routes->post('actualizar_apertura_caja_sin_cierre', 'cajaController::actualizar_apertura_caja_sin_cierre');
    $routes->post('exportar_a_excel_reporte_categorias', 'cajaController::exportar_a_excel_reporte_categorias');
    $routes->post('imp_movimiento_caja', 'cajaController::imp_movimiento_caja');
});

$routes->group('empresa', ['namespace' => 'App\Controllers\empresa', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('datos', 'EmpresaController::datos');
    $routes->post('actualizar_datos', 'EmpresaController::actualizar_datos');
    $routes->get('resolucion_facturacion', 'EmpresaController::resolucion_facturacion');
    $routes->get('resolucion_electronica', 'EmpresaController::resolucion_electronica');
    $routes->get('consecutivos', 'EmpresaController::consecutivos');
    $routes->post('guardar_resolucion_facturacion', 'EmpresaController::guardar_resolucion_facturacion');
    $routes->post('actualizar_consecutivos', 'EmpresaController::actualizar_consecutivos');
    $routes->post('municipios', 'EmpresaController::municipios');
    $routes->post('actualizar_nombre_cuenta', 'EmpresaController::actualizar_nombre_cuenta');
    $routes->post('datos_resolucion_facturacion', 'EmpresaController::datos_resolucion_facturacion');
    $routes->post('actualizar_resolucion_facturacion', 'EmpresaController::actualizar_resolucion_facturacion');
    $routes->post('activacion_resolucion_facturacion', 'EmpresaController::activacion_resolucion_facturacion');
    $routes->post('activar_resolucion_dian', 'EmpresaController::activar_resolucion_dian');
    $routes->get('comprobante_transaccion', 'EmpresaController::comprobante_transaccion');
    $routes->post('configuracion_impresion', 'EmpresaController::configuracion_impresion');
    $routes->post('agregar_resolucion_electronica', 'EmpresaController::agregar_resolucion_electronica');
    $routes->post('editar_resolucion_electronica', 'EmpresaController::editar_resolucion_electronica');
    $routes->post('actualizar_resolucion_electronica', 'EmpresaController::actualizar_resolucion_electronica');
});

$routes->group('caja_general', ['namespace' => 'App\Controllers\caja_general', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('apertura_general', 'cajaGeneralController::apertura');
    $routes->get('cierre_general', 'cajaGeneralController::cierre');
    $routes->post('generar_apertura', 'cajaGeneralController::generar_apertura');
    $routes->post('generar_cierre', 'cajaGeneralController::generar_cierre');
    $routes->get('consulta_general', 'cajaGeneralController::consulta_general');
    $routes->post('total_ingresos', 'cajaGeneralController::total_ingresos');
    $routes->post('imprimir_movimiento_caja_general', 'cajaGeneralController::imprimir_movimiento_caja_general');
    $routes->post('ver_retiros', 'cajaGeneralController::ver_retiros');
    $routes->post('editar_apertura', 'cajaGeneralController::editar_apertura');
    $routes->post('actualizar_valor_apertura', 'cajaGeneralController::actualizar_valor_apertura');
    $routes->post('actualizar_valor_cierre', 'cajaGeneralController::actualizar_valor_cierre');
    $routes->post('validar_cierre', 'cajaGeneralController::validar_cierre');
    $routes->get('todos_los_cierres_caja_general', 'cajaGeneralController::todos_los_cierres_caja_general');
    $routes->post('consultar_movimiento', 'cajaGeneralController::consultar_movimiento');
    $routes->post('exportCostoExcel', 'cajaGeneralController::exportCostoExcel');
    $routes->post('exportVentas', 'cajaGeneralController::exportVentas');
    
});

/**
 * Rutas para la tomas de pedido mejorados 
 */

$routes->group('pedidos', ['namespace' => 'App\Controllers\pedidos', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('mesas', 'Mesas::index');
    $routes->post('productos_categoria', 'Mesas::productos_categoria');
    $routes->post('agregar_producto', 'Mesas::agregar_producto');
    $routes->post('agregar_producto_celular', 'Mesas::agregar_producto_celular');
    $routes->post('imprimirComanda', 'Imprimir::imprimirComanda');
    $routes->post('pedido', 'Mesas::pedido');
    $routes->post('prefactura', 'Imprimir::imprimir_prefactura');
    $routes->post('nota', 'Mesas::nota');
    $routes->get('tiempo_real', 'Mesas::get_mesas_tiempo_real');
    $routes->post('mesas_salon', 'Mesas::mesas_salon');
    $routes->post('get_mesas', 'Mesas::get_mesas');
    $routes->post('agregar_nota', 'Mesas::agregar_nota');
    $routes->post('consultar_nota', 'Mesas::consultar_nota');
    $routes->post('eliminar_producto', 'Mesas::eliminar_producto');
    $routes->post('actualizar_cantidades', 'Mesas::actualizar_cantidades');
    $routes->post('eliminacion_de_pedido', 'Mesas::eliminacion_de_pedido');
    $routes->post('restar_producto', 'Mesas::restar_producto');
    $routes->post('productos_pedido', 'Mesas::productos_pedido');
    $routes->post('partir_factura', 'PartirFactura::partir_factura');
    $routes->post('valor', 'PartirFactura::valor');
    $routes->post('cerrar_venta', 'CerrarVenta::cerrar_venta');
    $routes->post('imprimir_factura', 'Imprimir::imprimir_factura');
    $routes->post('actualizar_cantidad_pago_parcial', 'PartirFactura::actualizar_cantidad_pago_parcial');
    $routes->post('restar_partir_factura', 'PartirFactura::restar_partir_factura');
    $routes->post('cancelar_pago_parcial', 'PartirFactura::cancelar_pago_parcial');
    $routes->post('valor_pago_parcial', 'PartirFactura::valor_pago_parcial');
    $routes->post('propinas', 'CerrarVenta::propinas');
    $routes->post('reporte_propinas', 'Mesas::reporte_propinas');
    $routes->post('todas_las_mesas', 'Mesas::todas_las_mesas');
    $routes->post('actualizar_mesero', 'CerrarVenta::actualizar_mesero');
    $routes->post('buscar_mesas', 'Mesas::buscar_mesas');
    $routes->post('imprimir_movimiento_caja', 'Imprimir::imprimir_movimiento_caja');
    $routes->post('crear_mesero', 'Mesas::crear_mesero');
    $routes->get('gestion_pedidos', 'TomaPedidosController::index');
    $routes->post('buscar_mesero', 'Mesas::buscar_mesero');
    $routes->get('lista_electronicas', 'Imprimir::lista_electronicas');
    $routes->post('imprimir_factura_electronica', 'Imprimir::imprimir_factura_electronica');
    $routes->post('impresion_factura_electronica', 'Imprimir::impresion_factura_electronica');
    $routes->post('detalle_f_e', 'Imprimir::detalle_f_e');
    $routes->post('reporte_ventas', 'Imprimir::reporte_ventas');
    $routes->post('imprimir_fiscal', 'Imprimir::imprimir_fiscal');
    $routes->get('imprimir_inventario', 'Imprimir::imprimir_inventario');
    $routes->get('imprimir_inventario_sin_cantidades', 'Imprimir::imprimir_inventario_sin_cantidades');
    $routes->post('imprimir_categoria_con_cantidades', 'Imprimir::imprimir_categoria_con_cantidades');
    $routes->post('imprimir_categoria_sin_cantidades', 'Imprimir::imprimir_categoria_sin_cantidades');
    $routes->post('mesas_de_salon', 'Mesas::mesas_de_salon');
});

$routes->group('inventario', ['namespace' => 'App\Controllers\pedidos', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('ingreso', 'Inventarios::ingreso');
    $routes->get('salida', 'Inventarios::salida');
    $routes->post('ingreso_inventario', 'Inventarios::ingreso_inventario');
    $routes->post('salida_inventario', 'Inventarios::salida_inventario');
    $routes->post('buscar', 'Inventarios::buscar');
    $routes->get('exportar', 'Inventarios::exportar_inventario');
    $routes->post('productos_borrados', 'Inventarios::productos_borrados');
    $routes->post('productos_subcategoria', 'Inventarios::productos_subcategoria');
    $routes->post('reporte_meseros', 'Inventarios::reporte_meseros');
    $routes->post('entradas_salidas', 'Inventarios::entradas_salidas');
    $routes->get('exportar_excel', 'Inventarios::exportar_excel');
    $routes->get('reporte_categoria', 'Inventarios::reporte_categoria');
    $routes->post('reporte_ventas', 'Inventarios::reporte_ventas');
    $routes->post('export_pdf', 'Inventarios::export_pdf');
    $routes->post('producto_entrada', 'Inventarios::producto_entrada');
    $routes->get('ingresar_entrada', 'Inventarios::ingresar_entrada');
    $routes->post('eliminar_producto_compra', 'Inventarios::eliminar_producto_compra');
    $routes->post('actualizar_producto_compra', 'Inventarios::actualizar_producto_compra');
    $routes->post('usuario_producto_compra', 'Inventarios::usuario_producto_compra');
    $routes->post('buscar_por_codigo', 'Inventarios::buscar_por_codigo');
    $routes->post('actualizacion_cantidades_compra', 'Inventarios::actualizacion_cantidades_compra');
    $routes->post('procesar_compra', 'Inventarios::procesar_compra');
    $routes->get('consultar_compras', 'Inventarios::consultar_compras');
    $routes->post('consultar_productos_compra', 'Inventarios::consultar_productos_compra');
    $routes->get('consultar_entrada_salida', 'Inventarios::consultar_entradas_salida');
    $routes->post('actualizacion_producto_compra', 'Inventarios::actualizacion_producto_compra');
    $routes->post('eliminacion_producto_compra', 'Inventarios::eliminacion_producto_compra');
    $routes->post('borrar_compra', 'Inventarios::borrar_compra');
    $routes->post('proveedor', 'Inventarios::proveedor');
});

$routes->group('eventos', ['namespace' => 'App\Controllers\Boletas', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('boletas', 'Boletas::boletas');
    $routes->get('set_boletas', 'Boletas::set_boletas');
    $routes->get('consultar_boleta', 'Boletas::consultar_boleta');
    $routes->post('cliente', 'Boletas::cliente');
    $routes->post('actualizar_producto_porcentaje', 'Boletas::actualizar_producto_porcentaje');
    $routes->post('editar_precio_producto', 'Boletas::editar_precio_producto');
    $routes->post('lista_precios', 'Boletas::lista_precios');
    $routes->post('cortesia', 'Boletas::cortesia');
    $routes->post('cerrar_modal', 'Boletas::cerrar_modal');
    $routes->post('descontar_dinero', 'Boletas::descontar_dinero');
    $routes->post('nombre_producto', 'Boletas::nombre_producto');
    $routes->post('generar_cortesia', 'Boletas::generar_cortesia');
    $routes->post('asignar_p1', 'Boletas::asignar_p1');
    $routes->post('municipios', 'Boletas::municipios');
    $routes->post('cancelar_descuentos', 'Boletas::cancelar_descuentos');
    $routes->post('valor', 'Boletas::valor');
    $routes->post('editar_cantidades', 'Boletas::editar_cantidades');
    $routes->post('actualizar_cantidades', 'Boletas::actualizar_cantidades');
    $routes->get('consultar_ventas', 'Boletas::consultar_ventas');
    $routes->get('consultar_documento', 'Boletas::documento');
    $routes->post('numero_documento', 'Boletas::numero_documento');
    $routes->post('get_cliente', 'Boletas::get_cliente');
    $routes->post('borrar_propina', 'Boletas::borrar_propina');
    $routes->get('tipo_documento', 'Boletas::tipo_documento');
    $routes->post('actualizar_propina', 'Boletas::actualizar_propina');
    $routes->get('consultar_de_tipo_documento', 'Boletas::consultar_de_tipo_documento');
    $routes->get('consultar_cliente', 'Boletas::consultar_cliente');
    $routes->get('get_mesas_pedido', 'Boletas::get_mesas_pedido');
    $routes->get('venta_multiple', 'Boletas::venta_multiple');
    $routes->post('actualizar_venta_multiple', 'Boletas::actualizar_venta_multiple');
    $routes->get('validar_venta_directa', 'Boletas::validar_venta_directa');
    $routes->post('editar_precio', 'Boletas::editar_precio');
    $routes->post('eliminar_f_e', 'Boletas::eliminar_f_e');
    $routes->post('validar_pass', 'Boletas::validar_pass');
    $routes->post('borrar_propina_parcial', 'Boletas::borrar_propina_parcial');
    $routes->get('consultar_entradas', 'Boletas::consultar_entradas');
});



$routes->group('reportes', ['namespace' => 'App\Controllers\reportes', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->post('ventas', 'Ventas::ventas');
    $routes->post('exportar_excel', 'Ventas::exportar_excel');
    $routes->post('consolidado_ventas', 'Ventas::consolidado_ventas');
    $routes->post('retiros', 'Ventas::retiros');
    $routes->post('devoluciones', 'Ventas::devoluciones');
    $routes->get('productos_borrados', 'Ventas::productos_borrados');
    $routes->get('reporte_costo', 'Ventas::reporte_costo');
    $routes->get('datos_reporte_costo', 'Ventas::datos_reporte_costo');
    $routes->post('exportar_reporte_costo', 'Ventas::exportar_reporte_costo');
    $routes->post('exportar_reporte_costo_excel', 'Ventas::exportar_reporte_costo_excel');
    $routes->post('exportar_reporte_ventas', 'Ventas::exportar_reporte_ventas');
    $routes->post('exportar_reporte_ventas_excel', 'Ventas::exportar_reporte_ventas_excel');
    $routes->post('datos_productos_borrados', 'Ventas::datos_productos_borrados');
    $routes->get('reportes_ventas', 'Ventas::reporte_ventas');
    $routes->post('datos_reportes_ventas', 'Ventas::datos_reporte_ventas');
    $routes->post('editar_apertura', 'Ventas::editar_apertura');
    $routes->post('editar_cierre_efectivo', 'Ventas::editar_cierre_efectivo');
    $routes->post('editar_cierre_transaccion', 'Ventas::editar_cierre_transaccion');
    $routes->post('cambiar_valor_apertura', 'Ventas::cambiar_valor_apertura');
    $routes->get('data_table_reporte_costo', 'Ventas::data_table_reporte_costo');
    $routes->get('pedidos_borrados', 'Ventas::pedidos_borrados');
    $routes->post('cambiar_valor_cierre_efectivo', 'Ventas::cambiar_valor_cierre_efectivo');
    $routes->get('data_table_ventas', 'ReportesController::data_table_ventas');
    $routes->post('retrasmistir', 'ReportesController::retrasmistir');
    //$routes->get('retrasmistir', 'ReportesController::sendDian');
    $routes->get('estado_dian', 'ReportesController::estado_dian');
    $routes->post('actualizar_pagos', 'ReportesController::actualizar_pagos');
    $routes->post('datos_pagos', 'ReportesController::datos_pagos');
    $routes->get('ver_productos_eliminanados', 'ReportesController::ver_productos_eliminanados');
    $routes->post('activar_producto', 'ReportesController::activar_producto');
    $routes->post('total_ventas_electronicas', 'ReportesController::total_ventas_electronicas');
    $routes->get('reporte_de_ventas_fecha', 'ReportesController::reporte_de_ventas_fecha');
    $routes->post('comprobar_fechas', 'ReportesController::comprobar_fechas');
    $routes->post('actualizar_fechas', 'ReportesController::actualizar_fechas');
    $routes->get('actualizacion_estado_mesas', 'ReportesController::actualizacion_estado_mesas');
    $routes->post('productos_pedido', 'ReportesController::productos_pedido');
    $routes->post('reporte_movimiento', 'ReportesController::reporte_movimiento');
    $routes->post('reporte_impuestos', 'ReportesController::reporte_impuestos');
});

$routes->group('configuracion', ['namespace' => 'App\Controllers\configuracion', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('mesero', 'Configuracion::mesero');
    $routes->post('actualizar_mesero', 'Configuracion::actualizar_mesero');
    $routes->get('propina', 'Configuracion::propina');
    $routes->post('configuracion_propina', 'Configuracion::configuracion_propina');
    $routes->get('estacion_trabajo', 'Configuracion::estacion_trabajo');
    $routes->post('actualizar_caja', 'Configuracion::actualizar_caja');
    $routes->get('sub_categoria', 'Configuracion::sub_categoria');
    $routes->post('actualizar_sub_categoria', 'Configuracion::actualizar_sub_categoria');
    $routes->get('crear_sub_categoria', 'Configuracion::crear_sub_categoria');
    $routes->post('editar_sub_categoria', 'Configuracion::editar_sub_categoria');
    $routes->post('actualizar_sub_categoria', 'Configuracion::actualizar_sub_categoria');
    $routes->post('eliminar_sub_categoria', 'Configuracion::eliminar_sub_categoria');
    $routes->post('actualizar_estado_sub_categoria', 'Configuracion::actualizar_estado_sub_categoria');
    $routes->get('tipos_de_factura', 'Configuracion::tipos_de_factura');
    $routes->post('actualizar_estado', 'Configuracion::actualizar_estado');
    $routes->post('consulta_factura_electronica', 'Configuracion::consulta_factura_electronica');
    $routes->get('borrar_remisiones', 'Configuracion::borrar_remisiones');
    $routes->post('actualizar_remisiones', 'Configuracion::actualizar_remisiones');
    $routes->post('borrado_de_remisiones', 'Configuracion::borrado_de_remisiones');
    $routes->get('abrir_cajon', 'Configuracion::abrir_cajon');
    $routes->get('admin_imp', 'Configuracion::admin_imp');
    $routes->get('comanda', 'Configuracion::comanda');
    $routes->post('actualizar_comanda', 'Configuracion::actualizar_comanda');
    $routes->get('productos_favoritos', 'Configuracion::productos_favoritos');
    $routes->get('encabezado', 'Configuracion::encabezado');
    $routes->post('actualizar_encabezado', 'Configuracion::actualizar_encabezado');
    $routes->post('actualizar_pie', 'Configuracion::actualizar_pie');
    $routes->post('actualizar_favorito', 'Configuracion::actualizar_favorito');
    $routes->get('borrado_masivo', 'Configuracion::borrado_masivo');
    $routes->get('productos_impuestos', 'Configuracion::productos_impuestos');
    $routes->get('impuestos', 'Configuracion::productos_impuestos');
    $routes->get('select_impuestos', 'Configuracion::select_impuestos');
    $routes->get('actualizar_impuestos', 'Configuracion::actualizar_impuestos');
    $routes->get('reset_producto', 'Configuracion::reset_producto');
    $routes->post('validar_pin', 'Configuracion::validar_pin');
    $routes->post('eliminacion_masiva', 'Configuracion::eliminacion_masiva');
    $routes->post('propina_parcial', 'Configuracion::propina_parcial');
    $routes->get('sincronizar', 'Configuracion::sincronizar');
    $routes->get('asignar', 'Configuracion::asignar');
    $routes->post('update_url', 'Configuracion::actualizar_url');
    $routes->post('AddDocument', 'Configuracion::AddDocument');
});


$routes->group('actualizacion', ['namespace' => 'App\Controllers\actualizaciones', 'filter' => \App\Filters\Auth::class], function ($routes) {
    $routes->get('Bd', 'ActualizacionesController::Bd');
    $routes->get('parametrizacion', 'ParametrizacionController::parametrizacion');
    $routes->post('actualizar_codigo', 'ParametrizacionController::actualizar_codigo');
    $routes->post('actualizar_altura', 'ParametrizacionController::actualizar_altura');
});

$routes->get('/qr-codes', 'QrCodeGeneratorController::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
