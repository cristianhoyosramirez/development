function impoconsumo(e, base) {
  const base_ico = document.querySelector("#base_ico");
  function formatNumber(n) {
    n = String(n).replace(/\D/g, "");
    return n === "" ? n : Number(n).toLocaleString();
  }
  base_ico.addEventListener("keyup", (e) => {
    const element = e.target;
    const value = element.value;
    element.value = formatNumber(value);
  });

  var enterKey = 13;

  if (e.which == enterKey) {
    var url = document.getElementById("url").value;
    var base = document.getElementById("base_ico").value;
    var cuadre_caja = document.getElementById("total_venta_cero").value;
    var baseFormat = base.replace(/[.]/g, "");
    var cuadre_caja_format = cuadre_caja.replace(/[.]/g, "");
    if (base == "") {
      $("#falta_base_cero").html('');
      $("#falta_base_ico").html('No hay valor definido');
    } else if (base !== "") {
      $("#falta_base_ico").html('');
      $.ajax({
        data: {
          baseFormat,
          cuadre_caja_format,
        },
        url: url + "/" + "consultas_y_reportes/reporte_caja_diaria_datos_ico",
        type: "POST",
        success: function (resultado) {
          var resultado = JSON.parse(resultado);

          if (resultado.resultado == 1) {
            $("#valor_impuesto_8").val(resultado.valor_impuesto);
            $("#total_venta_8").val(resultado.total_ico);
            $("#total_cuadre_caja").val(resultado.cuadre_caja);

            //$("#buscar_producto").autocomplete("close");
            // $("#mensaje_de_error").empty();
          }
        },
      });
    }
  }
}

function enviar_formulario() {
  $("#formulario_reporte_caja_diaria").submit();
}
