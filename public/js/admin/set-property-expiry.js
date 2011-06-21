$(function() {
    $('#expiry').datepicker({
       changeMonth: true,
       changeYear: true,
       dateFormat: 'd-M-yy',
       minDate: 1
    });
    
    $("#expiry").keydown(function(event) {
		if (event.which == 8 || event.which == 46) {
			$(this).val("");
		}
	});
});