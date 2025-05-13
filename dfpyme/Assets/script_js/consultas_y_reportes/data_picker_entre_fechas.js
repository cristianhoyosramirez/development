
$(function () {
    // var dateFormat = "mm/dd/yy",
    var dateFormat = "yy/mm/dd",

        from = $("#fecha_inicial_reporte")
            .datepicker({
                //defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                changeYear: true,

            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            }),
        to = $("#fecha_final_reporte").datepicker({
            //defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            });

    function getDate(element) {
        var date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }
});
