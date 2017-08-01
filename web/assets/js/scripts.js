/* BIND DATEPICKERS */

$(function () {
    $("input[type='datetime']").each(function () {
        var args = {};
        if (this.hasAttribute('future')) {
            args.minDate = 0;
        }

        $(this).datepicker(args);
    })
});
