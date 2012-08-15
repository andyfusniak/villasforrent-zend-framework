$(function(){
    $('#finance-payment-filter').change(function() {
        var queryParams = $.param({
            filter: $('#finance-payment-filter').val()
        });
        window.location.replace('/admin/finance-payment/list?' + queryParams);
    });
});