(function () {
    $(document).ready(function() {
        if ($('#voucher_usages_0').is(':checked')) {
            $('label[for="massage_type_label"]').removeClass('hidden');
            $('#voucher_massage_type').removeClass('hidden');
            if ($('#voucher_massage_type_0').is(':checked') || $('#voucher_massage_type_1').is(':checked')) {
                $('label[for="time_for_massage"]').removeClass('hidden');
                $('#voucher_time_for_massage').removeClass('hidden');
            }
        }

        if ($('#voucher_massage_type_0').is(':checked')) {
            $('label[for="time_for_massage"]').removeClass('hidden');
            $('#voucher_time_for_massage').removeClass('hidden');
        }

        if ($('#voucher_massage_type_1').is(':checked')) {
            $('label[for="time_for_massage"]').removeClass('hidden');
            $('#voucher_time_for_massage').removeClass('hidden');
        }

        if ($('#voucher_usages_1').is(':checked')) {
            $('label[for="voucher_time_for_floating"]').removeClass('hidden');
            $('#voucher_time_for_floating').removeClass('hidden');
        }

        if ($('#voucher_usages_1').is(':checked')) {
            $('#voucher_orderNumber').removeClass('hidden');
            $('#voucher_invoiceNumber').removeClass('hidden');
            $('#voucher_includedPostalCharges').removeClass('hidden');
            $('label[for="voucher_orderNumber"]').removeClass('hidden');
            $('label[for="voucher_invoiceNumber"]').removeClass('hidden');
            $('label[for="voucher_includedPostalCharges"]').removeClass('hidden');
        }

        $('#voucher_usages_0').click(function() {
            if ($(this).is(':checked')) {
                $('label[for="massage_type_label"]').removeClass('hidden');
                $('#voucher_massage_type').removeClass('hidden');
                if ($('#voucher_massage_type_0').is(':checked') || $('#voucher_massage_type_1').is(':checked')) {
                    $('label[for="time_for_massage"]').removeClass('hidden');
                    $('#voucher_time_for_massage').removeClass('hidden');
                }
            } else {
                $('label[for="massage_type_label"]').addClass('hidden');
                $('#voucher_massage_type').addClass('hidden');
                $('label[for="time_for_massage"]').addClass('hidden');
                $('#voucher_time_for_massage').addClass('hidden');
                $('label[for="time_for_massage"]').addClass('hidden');
                $('#voucher_time_for_massage').addClass('hidden');
            }
        });

        $('#voucher_massage_type_0').click(function() {
            $('label[for="time_for_massage"]').removeClass('hidden');
            $('#voucher_time_for_massage').removeClass('hidden');
        });

        $('#voucher_massage_type_1').click(function() {
            $('label[for="time_for_massage"]').removeClass('hidden');
            $('#voucher_time_for_massage').removeClass('hidden');
        });

        $('#voucher_usages_1').click(function() {
            if ($(this).is(':checked')) {
                $('label[for="voucher_time_for_floating"]').removeClass('hidden');
                $('#voucher_time_for_floating').removeClass('hidden');
            } else {
                $('label[for="voucher_time_for_floating"]').addClass('hidden');
                $('#voucher_time_for_floating').addClass('hidden');
                $('label[for="time_for_massage"]').addClass('hidden');
                $('#voucher_time_for_massage').addClass('hidden');
            }
        });

        $('#voucher_onlineVoucher').click(function() {
            if ($(this).is(':checked')) {
                $('#voucher_orderNumber').removeClass('hidden');
                $('#voucher_invoiceNumber').removeClass('hidden');
                $('#voucher_includedPostalCharges').removeClass('hidden');
                $('label[for="voucher_orderNumber"]').removeClass('hidden');
                $('label[for="voucher_invoiceNumber"]').removeClass('hidden');
                $('label[for="voucher_includedPostalCharges"]').removeClass('hidden');
            } else {
                $('#voucher_orderNumber').addClass('hidden');
                $('#voucher_invoiceNumber').addClass('hidden');
                $('#voucher_includedPostalCharges').addClass('hidden');
                $('label[for="voucher_orderNumber"]').addClass('hidden');
                $('label[for="voucher_invoiceNumber"]').addClass('hidden');
                $('label[for="voucher_includedPostalCharges"]').addClass('hidden');
            }
        });
    });
})();