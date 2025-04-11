function impuesto_iva(e, base) {
  const base_cero = document.querySelector("#base_cero");
  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  base_cero.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });

  var enterKey = 13;

  if (e.which == enterKey) {
    var url = document.getElementById("url").value;
    var base = document.getElementById("base_cero").value;
    var cuadre_caja = document.getElementById("total_cuadre_caja").value;
    var baseFormat = base.replace(/[.]/g, "");
    var cuadre_caja_format = cuadre_caja.replace(/[.]/g, "");
    if (base == "") {
      $("#falta_base_cero").html('No hay valor definido');
    } else if (base !== "") {
      $.ajax({
        data: {
          baseFormat,
          cuadre_caja_format,
        },
        url: url + "/" + "consultas_y_reportes/reporte_caja_diaria_datos",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            $("#valor_impuesto_cero").val(0);
            $("#total_venta_cero").val(resultado.total_base_cero);
            $("#total_cuadre_caja").val(resultado.cuadre_caja);
            $("#base_ico").focus();

            //$("#buscar_producto").autocomplete("close");
            // $("#mensaje_de_error").empty();
          }
        },
      });
    }
  }
}
