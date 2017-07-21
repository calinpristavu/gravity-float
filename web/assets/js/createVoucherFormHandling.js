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

    $( "#voucher_usages_0" ).click(function() {
        if (!$('#voucher_usages_0').hasClass('hidden')) {
            $("#voucher_massage_type").attr("required", true);
        } else {
            $("#voucher_massage_type").attr("required", false);
        }
    });

    $( "#voucher_onlineVoucher" ).click(function() {
        if (!$('#voucher_onlineVoucher').hasClass('hidden')) {
            $("#voucher_orderNumber").attr("required", true);
            $("#voucher_invoiceNumber").attr("required", true);
        } else {
            $("#voucher_orderNumber").attr("required", false);
            $("#voucher_invoiceNumber").attr("required", false);
        }
    });

    JsBarcode("#barcode", $('#barcode').attr('data-code'), {});
});
