$(document).ready(function() {
    $('html,body').animate({
        scrollTop: $('#inbox-message-reply').offset().top
    }, 2000);

    var inboxReply = $('#inbox-reply');
    inboxReply.click(function(event) {
        var replyBody = $("#inbox-reply-body");

        $.ajax({
            type: 'POST',
            url: '/inbox-reply/post-message',
            data: {
                'idMessageThread': $("input[name='idMessageThread']").val(),
                'body': replyBody.val()
            },
            success: function(data, statusText, xhr) {
                if (data.error === undefined) {
                    var l = $("div.inbox-message:last");
                    var n = l.clone();

                    // replace the body text in place
                    n.find('.inbox-message-body-text').text(data.body.replace(/\n/g, '<br>'));

                    // clear the textarea box
                    $('#inbox-reply-body').val('');

                    // clear the time stamp
                    n.find('.inbox-message-body-header span').text(data.added);

                    // replace the avatar
                    n.find('.inbox-message-image').html(data.image);

                    // attach the new message
                    l.after(n);

                    console.log(data);
                    //console.log(statusText);
                    //console.log(xhr);
                } else {
                    console.log(data);
                    console.log(statusText);
                    console.log(xhr);
                }
            },
            error: function (xhr, statusText, exObj) {
                alert('err');
                console.log(xhr);
                console.log(statusText);
                console.log(exObj);
            },
            dataType: 'json'
        });

        return false;
    });
});
