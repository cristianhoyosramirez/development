/**
 *  Consultar todas las que pertenecen a un salon
 * @param {*} id_salon
 */
function ver_mesas_por_salon(id_salon) {
  var url = document.getElementById("url").value;

  $.ajax({
    data: {
      id_salon,
    },
    url: url + "/" + "salones/mesas",
    type: "post",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        $("#no_hay_mesas").html("");
        $("#mesas_salon").html(resultado.mesas);
      } else if (resultado.resultado == 0) {
        $("#no_hay_mesas").html("El salon no tiene mesas");
        $("#mesas_salon").html("");
      }
    },
  });
}

