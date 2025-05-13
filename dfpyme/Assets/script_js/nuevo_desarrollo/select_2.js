
$("#impuestos").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
    closeOnSelect: true
});

$("#responsabilidad_fiscal").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: false,
    dropdownParent: $("#crear_cliente"),
});
$("#municipios").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});
$("#ciudad").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});
$("#tipo_persona").select2({
    minimumResultsForSearch: -1,
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: false,
    dropdownParent: $("#crear_cliente"),
})

$("#tipo_documento").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});
$("#documento").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: false,
    dropdownParent: $("#finalizar_venta"),
    minimumResultsForSearch: -1,
});
$("#tipo_documento").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});

$("#regimen").select2({
    minimumResultsForSearch: -1,
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: false,
    dropdownParent: $("#crear_cliente"),
});
$("#tipo_ventas").select2({
    minimumResultsForSearch: -1,
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: false,
    dropdownParent: $("#crear_cliente"),
});
$("#departamento").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});
$("#codigo_postal").select2({
    width: "100%",
    //placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#crear_cliente"),
});

$("#tipo_descuento").select2({
    width: "100%",
    placeholder: "Tipo de descuento ",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#agregar_nota"),
});
