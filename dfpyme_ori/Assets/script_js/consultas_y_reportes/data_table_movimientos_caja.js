$(document).ready(function () {
    $('#movimientos_caja').DataTable({
        ordering: false,
        "language": {
            "decimal": "",
            "emptyTable": "No hay datos",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(Filtro de _MAX_ total registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron coincidencias",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Próximo",
                "previous": "Anterior"
            },
            "aria": {
                "sortAscending": ": Activar orden de columna ascendente",
                "sortDescending": ": Activar orden de columna desendente"
            }

        },
        "order": [],
        "bSort": true,
    }

    );
})


$(document).ready(function() {
    $('#consulta_ventas_cliente').DataTable({
        "language": {
          "decimal": "",
          "emptyTable": "No hay datos",
          "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
          "infoEmpty": "Mostrando 0 a 0 de 0 registros",
          "infoFiltered": "(Filtro de _MAX_ total registros)",
          "infoPostFix": "",
          "thousands": ",",
          "lengthMenu": "Mostrar _MENU_ registros",
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "search": "Buscar:",
          "zeroRecords": "No se encontraron coincidencias",
          "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Próximo",
            "previous": "Anterior"
          },
          "aria": {
            "sortAscending": ": Activar orden de columna ascendente",
            "sortDescending": ": Activar orden de columna desendente"
          }

        },
        "order": [
          [3, "desc"]
        ]
      }

    );
  });