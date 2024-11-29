function consultar_producto() {
  var url = document.getElementById("url").value;
  var codigointernoproducto = document.getElementById("id_producto").value;
  var limpiar_codigointernoproducto = (document.getElementById(
    "id_producto"
  ).value = "");
  var producto = (document.getElementById("producto").value = "");

  $.ajax({
    data: { codigointernoproducto },
    url: url + "/" + "producto/agregar_producto_al_pedido",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {
        document.getElementById("codigo_internoproducto_autocompletar").value =
          resultado.codigointernoproducto;
        $("#nombre_producto_autocompletar").html(resultado.nombre_producto);
        $("#precio_venta_autocompletar").html(
          resultado.valor_venta_con_formato
        );
        document.getElementById("precioventa_autocompletar").value =
          resultado.valor_venta;
        $("#codigointernoproducto_autocompletar").html(
          resultado.codigointernoproducto
        );
        myModal = new bootstrap.Modal(
          document.getElementById("autocompletar_producto"),
          {}
        );
        myModal.show();
      }
      if (resultado.resultado == 0) {
        alert("No hubo coincidencias ");
      }
    },
  });
}

/**
 *  En el input producto_pedido_cantidad_autocompletar del modal autocompletar_producto
 * solo deben ir valores numericos
 * @param {*} e
 * @returns
 */
function soloNumeros(e) {
  var key = window.Event ? e.which : e.keyCode;
  return key >= 48 && key <= 57;
}

/**
 * Establece el autofoco en el modal autocompletar_producto en el input cantidad
 */
$(function () {
  $("#autocompletar_producto").on("shown.bs.modal", function (e) {
    $("#producto_pedido_cantidad_autocompletar").focus();
  });
});

/**
 *
 */

/**
 * Funcion que se encargar de mostrar el valor en pesos del producto pedido por la cantidad solicita desde el modal auto_completar_producto
 */
function multiplicarAgregar() {
  var total = 0;
  var cantidad = document.getElementById(
    "producto_pedido_cantidad_autocompletar"
  ).value;
  var valor_unitario = document.getElementById(
    "precioventa_autocompletar"
  ).value;

  total = cantidad * valor_unitario;

  resultado = total.toLocaleString();

  document.getElementById("total_autocompletar").value = resultado;
}

/**
 * Cada vez que que se da enter en el formulario del modal autocompletar_producto pasa a otro input
 * @param {*} e
 * @param {*} id
 */

function saltar(e, id) {
  // Obtenemos la tecla pulsada

  e.keyCode ? (k = e.keyCode) : (k = e.which);

  // Si la tecla pulsada es enter (codigo ascii 13)

  if (k == 13) {
    // Si la variable id contiene "submit" enviamos el formulario

    if (id == "submit") {
      document.forms[0].submit();
    } else {
      // nos posicionamos en el siguiente input

      document.getElementById(id).focus();
    }
  }
}

// convertir de minusculas a mayusculas del campo de notas

function minusculasAmayusculas() {
  var x = document.getElementById("notas");
  x.value = x.value.toUpperCase();
}
/**
 *Agregar productos al pedido 
 * 
 */

function insertar_productos_tabla_pedido() {
  var validar_cantidad = document.getElementById("producto_pedido_cantidad_autocompletar").value;

  if (validar_cantidad == 0 || validar_cantidad == "" || validar_cantidad < 0) {
    $("#error_de_cantidad").html("Dato errado");
  } else {
    var numero_pedido = document.getElementById("numero_pedido_salvar").value;
    var id_mesa = document.getElementById("id_mesa").value;
    var id_usuario = document.getElementById("id_usuario").value;
    var nota_producto = document.getElementById("notas").value;
    var valor_unitario = document.getElementById("precioventa_autocompletar").value;
    var url = document.getElementById("url").value;
    var codigointernoproducto = document.getElementById("codigo_internoproducto_autocompletar").value;
    document.getElementById("form_autocompletar_producto").reset();
    document.getElementById('agregar_producto_pedido').disabled = true;

    $.ajax({
      data: {
        validar_cantidad,
        numero_pedido,
        id_mesa,
        id_usuario,
        nota_producto,
        valor_unitario,
        codigointernoproducto,
      },
      url: url + "/" + "producto/insertar_productos_tabla_pedido",
      type: "POST",
      success: function (resultado) {
        var resultado = JSON.parse(resultado);
        if (resultado.resultado == 0) {
          Swal.fire({
            icon: "error",
            title: "Cantidad errada ",
          });
        }

        if (resultado.resultado == 1) {  //LA MESA NO TIENE PEDIDO
          document.getElementById('agregar_producto_pedido').disabled = false;
          $("#productos_pedido").html(resultado.productos_pedido);
          document.getElementById("numero_pedido_salvar").value = resultado.numero_pedido;
          $("#numero_pedido_mostrar").html(resultado.numero_pedido);
          $("#valor_total").html(resultado.total_pedido);
          $("#cantidad_de_productos").html(resultado.cantidad_de_pruductos);

          document.getElementById("id_mesa").value = resultado.id_mesa;
          document.getElementById("numero_pedido_imprimir_comanda").value = resultado.numero_pedido;
          $('#autocompletar_producto').modal('hide');

          document.getElementById("producto").value = "";
          document.getElementById("id_producto").value = "";
          document.getElementById("producto").focus()
          
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: "Producto agregado a la mesa" + " " + resultado.nombre_mesa,
          })
        }

        if (resultado.resultado == 2) { //Se agrego producto y la mesa ya tiene pedido
          document.getElementById('agregar_producto_pedido').disabled = false;
          $("#productos_pedido").html(resultado.productos_pedido);
          document.getElementById("numero_pedido_salvar").value = resultado.numero_pedido;
          document.getElementById("id_mesa").value = resultado.id_mesa;
          $("#valor_total").html(resultado.total);
          $("#cantidad_de_productos").html(resultado.cantidad_de_productos);
          $('#autocompletar_producto').modal('hide');
          document.getElementById("producto").value = "";
          document.getElementById("id_producto").value = "";
          document.getElementById("producto").focus()

          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })

          Toast.fire({
            icon: 'success',
            title: "Producto agregado a la mesa" + " " + resultado.nombre_mesa,
          })

        }
        if (resultado.resultado == 3) {
          Swal.fire({
            icon: "success",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#2AA13D",
            title: "El numero del pedido no corresponde a la mesa ",
          });
        }
      },
    });
  }
}

//Select 2 del modal categoria producto
$(document).ready(function () {
  $("#categoria").select2({
    width: "100%",
    placeholder: "Filtrar productos por categoria",
    language: "es",
    theme: "bootstrap-5",
    allowClear: true,
    dropdownParent: $("#categorias_producto"),
  });
});

/**
 * @param id_categoria valor de id de categoria para consultar los productos asociados
 */
function categoriaProductos() {
  /* Para obtener el valor */
  var id_categoria = document.getElementById("categoria").value;
  var url = document.getElementById("url").value;
  $.ajax({
    data: {
      id_categoria,
    },
    url: url + "/" + "producto/buscar_productos_id_categoria",
    type: "post",
    success: function (resultado) {
      if (resultado == 1) {
        $("#categoria_productos").html(resultado);
      } else {
        $("#categoria_productos").html(resultado);
      }
    },
  });
}

/**
 * Datos que viene desdede l el modal productos_x_categorias
 * @param {id_producto} id_producto 
 */
function agregar_productos_x_categoria(id_producto) {

  $('#categorias_producto').modal('hide');

  var url = document.getElementById("url").value;
  $.ajax({
    data: { id_producto },
    url: url + "/" + "producto/agregar_productos_x_categoria",
    type: "POST",
    success: function (resultado) {
      var resultado = JSON.parse(resultado);
      if (resultado.resultado == 1) {

        $("#codigointernoproducto_x_categoria").html(resultado.id_producto);
        $("#nombre_producto_x_categoria").html(resultado.nombre_producto);
        $("#precio_venta_x_categoria").html(resultado.valor_venta);
        document.getElementById("codigo_internoproducto_producto_categoria").value = resultado.id_producto
        document.getElementById("precioventa_x_categoria").value = resultado.valor_venta_sin_formato
        myModal = new bootstrap.Modal(document.getElementById("productos_x_categorias"), {});
        myModal.show();


      }
    },
  });
}

$(function () {
  $("#productos_x_categorias").on("shown.bs.modal", function (e) {
    $("#cantidad_producto_pedido_x_categoria").focus();
  });
});


function minusculas_a_mayusculas() {
  var x = document.getElementById("notas_x_categoria");
  x.value = x.value.toUpperCase();
}


function multiplicarAgregar_x_categoria() {
  var total = 0;
  var cantidad = document.getElementById(
    "cantidad_producto_pedido_x_categoria"
  ).value;
  var valor_unitario = document.getElementById(
    "precioventa_x_categoria"
  ).value;

  total = cantidad * valor_unitario;

  resultado = total.toLocaleString();

  document.getElementById("total_x_categoria").value = resultado;
}
