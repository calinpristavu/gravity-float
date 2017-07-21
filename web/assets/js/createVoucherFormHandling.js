$(function () {
    $('#voucher_usages').find('input[type="checkbox"]').addClass('manager');
    $('#voucher_massage_type').find('input[type="radio"]').addClass('manager');

    $('.manager').on('change', function () {
        if (!$(this).is(':radio')) {
            $(".interactive[data-managed-by='" + this.id + "']").toggleClass("hidden");
            return;
        }

        var listeners = this.id.slice(0, -2);
        var that = this;
        $(".interactive[data-managed-by='" + listeners + "']").each(function () {
            if (that.id === $(this).attr('data-managed-by') + '_' + $(this).attr('data-manager-val')) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        })
    });

    JsBarcode("#barcode", $('#barcode').attr('data-code'), {});
});
