$(document).ready(function() {
    var addToFavourites = $('#add-to-favourites');
    var idProperty = addToFavourites.data('id-property');

    addToFavourites.click(function() {
        $.ajax({
            type: 'POST',
            url: '/favourites/toggle',
            data: {
                'idProperty': idProperty
            },
            success: function(data, statusText, xhr) {
                addToFavourites.toggleClass('favourited');

                if (data.error === undefined) {
                    //alert('added to favs');

                } else {
                    console.log(data);
                    console.log(statusText);
                    console.log(xhr);
                }
            },
            error: function (xhr, statusText, exObj) {
                //alert('err');
                console.log(xhr);
                console.log(statusText);
                console.log(exObj);
            },
            dataType: 'json'
        });
    });
});