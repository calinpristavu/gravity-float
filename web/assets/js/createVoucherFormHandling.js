(function () {
    $(document).ready(function() {
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
                $('#voucher_ordernumber').removeClass('hidden');
                $('#voucher_invoicenumber').removeClass('hidden');
                $('#voucher_includepostalcharges').removeClass('hidden');
                $('label[for="voucher_ordernumber"]').removeClass('hidden');
                $('label[for="voucher_invoicenumber"]').removeClass('hidden');
                $('label[for="voucher_includepostalcharges"]').removeClass('hidden');
            } else {
                $('#voucher_ordernumber').addClass('hidden');
                $('#voucher_invoicenumber').addClass('hidden');
                $('#voucher_includepostalcharges').addClass('hidden');
                $('label[for="voucher_ordernumber"]').addClass('hidden');
                $('label[for="voucher_invoicenumber"]').addClass('hidden');
                $('label[for="voucher_includepostalcharges"]').addClass('hidden');
            }
        });
    });
})();