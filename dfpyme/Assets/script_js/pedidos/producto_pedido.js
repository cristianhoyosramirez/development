$("#producto").autocomplete(
  {
    source: function (request, response) {
      var url = document.getElementById("url").value;
      $.ajax({
        type: "POST",
        url: url + "/" + "producto/pedido",
        data: request,
        success: response,
        dataType: "json",
      });
    },
  },
  {
    minLength: 1,
  },
  {
    select: function (event, ui) {
      //$("#producto").val(ui.item.value);
      //$("#id_producto").val(ui.item.id_producto);
      $("#codigo_internoproducto_autocompletar").val(ui.item.id_producto);
      $("#precioventa_autocompletar").val(ui.item.valor_venta);
      $("#nombre_producto_autocompletar").html(ui.item.nombre_producto);
      $("#codigointernoproducto_autocompletar").html(ui.item.id_producto);
      $("#precio_venta_autocompletar").html(ui.item.valor_venta);

      myModal = new bootstrap.Modal(
        document.getElementById("autocompletar_producto"),
        {}
      );
      myModal.show();
    },
  }
);
