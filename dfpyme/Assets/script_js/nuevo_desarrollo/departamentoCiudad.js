function departamentoCiudad() {

    var url = document.getElementById("url").value;
    var valorSelect1 = document.getElementById("departamento").value;

   
    $.ajax({
      data: {
        valorSelect1

      },
      url: url +
        "/" +
        "eventos/municipios",
      type: "post",
      success: function(resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 1) {

           $('#ciudad').html(resultado.ciudad)
           $('#ciudad_cliente_edicion').html(resultado.ciudad)
           //$('#municipios').html(resultado.ciudad)
           $('#municipios').html(resultado.municipios)

        }

        if (resultado.resultado == 0) {
          alert('No se puede actualizar ')
        }
      },
    });



  }