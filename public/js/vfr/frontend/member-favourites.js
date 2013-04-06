$(document).ready(function() {
    var addToFavourites      = $('#add-to-favourites');
    var deleteFromFavourites = $('.delete-from-favourites');

    if (addToFavourites.length) {
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
    }

    if (deleteFromFavourites.length) {
        var idProperty = addToFavourites.data('id-property');

        deleteFromFavourites.bind('click', function() {

            console.log($(this).data('id-property'));

            $.ajax({
                type: 'DELETE',
                url: '/favourites/delete',
                data: {
                    'idProperty': $(this).data('id-property')
                },
                success: function(data, statusText, xhr) {
                    deleteFromFavourites.fadeOut("normal", function() {
                        $(this).remove();
                    });

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
    }
});