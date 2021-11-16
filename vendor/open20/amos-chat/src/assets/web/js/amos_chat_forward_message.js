// $('.forward-message select').hide();

$(document).on('click','.forward-button',function (event) {
    event.preventDefault();
    var msgSelect = $(this).next();
    $(msgSelect).show();
    // $.ajax({
    //     url: '/forward-message',
    //     type: 'GET',
    //     data: {
    //         idMessage: $(msgSelect).attr('data-key'),
    //         idUserToForward: $(msgSelect).val()
    //     },
    //     success: function(data) {
    //     alert('Message forwarded correctly');
    // }
    // });
});

    $(document).on('change', '.forward-message select', function () {
        if($(this).val()) {
            if(confirm('Are you sure to forward the message')==true) {
                var msgSelect = $(this);
                $.ajax({
                    url: '/forward-message',
                    type: 'GET',
                    data: {
                        idMessage: $(msgSelect).attr('data-key'),
                        idUserToForward: $(msgSelect).val()
                    },
                    success: function (data) {
                        alert('Message forwarded correctly');
                        location.reload();
                    }
                });
            }
        }
    });
