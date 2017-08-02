/* BIND DATEPICKERS */

$(function () {
    $("input[type='datetime'], .datepicker").each(function () {
        var args = {
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy"
        };
        if (this.hasAttribute('future')) {
            args.minDate = 0;
        }

        $(this).datepicker(args);
    })
});
