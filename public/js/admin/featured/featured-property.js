$(function() {
    $('#start-date').datepicker({
        changeMonth: false,
        changeYear: false,
        dateFormat: 'dd/mm/yy',
        showButtonPanel: true,
        numberOfMonths: [1, 3],
        stepMonths: 3,
        onSelect: function(selectedDate) {
            $('#expiry-date').datepicker('option', 'minDate', selectedDate);
		},
        beforeShowDay: highlightDays
    });

    $('#expiry-date').datepicker({
        changeMonth: false,
        changeYear: false,
        numberOfMonths: [1, 3],
        dateFormat: 'dd/mm/yy',
        numberOfMonths: 3,
        onSelect: function(selectedDate) {
			$('#start-date').datepicker('option', 'maxDate', selectedDate);
        },
        beforeShowDay: highlightDays
    });


    $('#start-date').datepicker('setDate', $.datepicker.parseDate('dd/mm/yy', $('#start-date').val()));
    $('#expiry-date').datepicker('setDate', $.datepicker.parseDate('dd/mm/yy', $('#expiry-date').val()));

    function highlightDays(d) {
        var startDate = $.datepicker.parseDate('dd/mm/yy', $('#start-date').val());
        var expiryDate = $.datepicker.parseDate('dd/mm/yy', $('#expiry-date').val());

        //console.log(d, startDate, expiryDate);

        if ((d >= startDate) && (d <= expiryDate)) {
            return [true, 'ui-state-active'];
        } else {
            return [true, ''];
        }
    }

    $('#featured-details').ajaxStart(function() {
        console.log('loading...');
        $(this).addClass('loading');
    }).ajaxStop(function() {
        console.log('finished loading...');
        $(this).removeClass('loading');
    });

    $('#featured-details').ajaxSuccess(function(e, xhr, settings) {
        console.log('callback...');
        if (settings.url.indexOf('/admin/featured-property/lookup') == 0) {
            $(this).text('Triggered ajaxSuccess handler. The ajax response was:'
                     + xhr.responseText );
        }
    });

    $('#featured-lookup').click(function() {
        var idProperty = $('#idProperty').val();

        console.log('looking up ' + idProperty);

        $.ajax({
            url: '/admin/featured-property/lookup',
            type: 'GET',
            data: {
                'idProperty': idProperty
            },
            dataType: 'json',
            success: function(data, statusText, xhr) {
                console.log(data);
                //$('#featured-details').html(data);
                //console.log(statusText);
                //console.log(xhr);
            },
            error: function (xhr, statusText, exObj) {
                //alert('err');
                console.log(xhr);
                console.log(statusText);
                console.log(exObj);
            }
        });
        return false;
    });

});
