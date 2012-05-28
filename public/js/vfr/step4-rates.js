$(function() {
    // create an array of days to be disabled
    //var disabledDays = ["10-5-2011", "11-5-2011", "12-5-2011"];
    var disabledDays = [];
    var d, m, y;

    function eliminateDays(date) {
        d = date.getDate(), m = date.getMonth(), y = date.getFullYear();
        //console.log('Checking (raw): ' + m + '-' + d + '-' + y);
        if ($.inArray(d+'-'+(m+1)+'-'+y, disabledDays) != -1) {
            //console.log('bad:  ' + (m+1) + '-' + d + '-' + y + ' / ' + disabledDays[i]);
            return [false];
        }
        //console.log('good:  ' + (m+1) + '-' + d + '-' + y);
        return [true];
    }

    $('#start').datepicker({
        defaultDate: '+5d',
        changeMonth: true,
        changeYear: true,
        numberOfMonths:1,
        //minDate:'+0d',
        showOn: 'both',
        buttonImage: '/js/jquery-ui-1.8.17/development-bundle/demos/images/calendar.gif',
        buttonImageOnly: true,
        dateFormat: 'd-M-yy',
        beforeShowDay: eliminateDays,
        onClose: function(dateText, inst) {
            if (dateText !== '') {
                try {
                    var fromDate = $.datepicker.parseDate(inst.settings.dateFormat, dateText, inst.settings);
                    fromDate.setDate(fromDate.getDate() + 1);
                    $('#endDate').datepicker('option', 'minDate', fromDate);
                }
                catch (err) {
                    console.log(err);
                }
            }
            else {
                //If #from is empty, restore the original limit in #to
                $('#end').datepicker('option', 'minDate', '+0d');
            }
        }

    });

    $('#end').datepicker({
        defaultDate: '+5d',
        changeMonth: true,
        changeYear: true,
        numberOfMonths:1,
        minDate:'+0d',
        showOn: 'both',
        buttonImage: '/js/jquery-ui-1.8.17/development-bundle/demos/images/calendar.gif',
        buttonImageOnly: true,
        dateFormat: 'd-M-yy',
        beforeShowDay: eliminateDays,
        onClose: function(dateText, inst) {
            if (dateText !== '') {
                try {
                    var toDate = $.datepicker.parseDate(inst.settings.dateFormat, dateText, inst.settings);
                    toDate.setDate(toDate.getDate() - 1);
                    $('#start').datepicker('option', 'maxDate', toDate);
                }
                catch (err) {
                    console.log(err);
                }
            }
            else {
                //If #to is empty, remove the limit in #from
                $('#start').datepicker('option', 'maxDate', null);
            }
        }
    });

    $("#start").keydown(function(event) {
        if (event.which == 8 || event.which == 46) {
            $(this).val("");
        }
    });

    $("#end").keydown(function(event) {
        if (event.which == 8 || event.which == 46) {
            $(this).val("");
        }
    });
});
