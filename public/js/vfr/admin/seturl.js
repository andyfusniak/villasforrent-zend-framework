$(function() {
    var idPropertyTd;
    var url;

    var $urlDialog = $("#seturl-form").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        open: function( event, ui ) {
            $('#setUrl').val(url);
        },
        close: function( event, ui ) {}
    });

    $(".setUrl")
        .button()
        .click(function() {
            $(this).closest('tr').children().eq(5).css('border', '1px solid red');

            idPropertyTd = $(this).closest('tr').children('td:first').text();
            url          = $(this).closest('tr').children().eq(5).text();

            $urlDialog.dialog('open');

            $("#seturl-form").dialog({
                buttons: {
                    "Set the URL": function() {
                        alert("boom" + $('#setUrl').val());

                        $(this).dialog( "close" );
                    },

                    Cancel: function() {
                        $(this).dialog( "close" );
                    }
                }
            });
        });
});
