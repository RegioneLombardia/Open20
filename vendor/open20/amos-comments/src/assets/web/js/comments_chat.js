var Comments = {};

// Id messaggi chat
Comments.idUpdateComment = null;
Comments.idDeleteComment = null;
Comments.lastMessageId = 0;

// Dati di contesto per aggiornare la chat
Comments.context = null;
Comments.context_id = null;
Comments.model = null;
Comments.autoupdateInterval = 5;

Comments.comment = function(context_id) {
    // Identify comment container of the content.
    var comment_container = $("#bk-contribute");

    // If it's hidden, it show it
    if (comment_container.is(":hidden")) {
        comment_container.show();
    }
    // In other case it hide it
    else {
        comment_container.hide();
    }
};

Comments.beforeSendAjax = function() {
    $('#comments-loader').toggleClass('hidden show');
    $('#contribute-btn').prop('disabled', true);
    $('#comments_anchor').prop('disabled', true);
};

Comments.completeAjax = function() {
    $('#comments-loader').toggleClass('show hidden');
    $('#contribute-btn').prop('disabled', false);
    $('#comments_anchor').prop('disabled', false);

    Comments.updateScroll(); // Scroll chat sempre in basso
    Comments.disableSendButton();
};

Comments.saveComment = function(context_id, context, csrfKey, csrfValue) {
    // Identify comment container of the content.
    var comment_container = $("#bk-contribute");

    // It retrieves the comment textarea.
    var comment_textarea = comment_container.find("[name='contribute-area']");

    // It retrieves the comment text.
    var comment_text = comment_textarea.val();

    var sendMailNotification = ($('#sendMailNotification').is(':checked') ? 1 : 0);

    // If the comment text is not empty...
    if (comment_text) {
        // ...it build params.
        var formData = new FormData();
        formData.append('Comment[context]', context);
        formData.append('Comment[context_id]', context_id);
        formData.append('Comment[comment_text]', comment_text);
        formData.append('sendMailNotification', sendMailNotification);

        if (csrfKey !== null && csrfKey !== 'undefined') {
            formData.append(csrfKey, csrfValue);
        }

        // Check if the comment contains words from the blacklist
        $.ajax({
            url: '/comments/comment-blacklist/scan-comment',
            data: {
                commentText: comment_text,
                isChat: true
            },
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if (response.containsBlacklistedWords) {
                    $('#scan-comment-modal-id').find('.modal-body').html(response.modalBody);
                    if (!$('#scan-comment-modal-id .modal-footer').length) {
                        $(response.modalFooter).insertAfter('#scan-comment-modal-id .modal-body');
                    }
                    $('#scan-comment-modal-id').modal('show');
                } else {
                    $.ajax({
                        url: '/comments/comment/create-ajax?chat=true',
                        contentType: false,
                        processData: false,
                        data: formData,
                        type: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            Comments.beforeSendAjax();
                        },
                        complete: function(jjqXHR, textStatus) {
                            Comments.completeAjax();
                        },
                        success: function(response) {
                            if (response.error) {
                                alert(response.error.msg);
                            } else {
                                var urlRedirect = $('#url-redirect').val();
                                if(urlRedirect != '' && urlRedirect != undefined){
                                    window.location.href = urlRedirect;
                                }
                                Comments.insertNewMessage(response);
                                Comments.updateScroll();

                                // Salvo l'ultimo messaggio che vedo in chat
                                Comments.lastMessageId = $('.content-message-chat').last().prop('id').split('-')[1];
                                comment_textarea.val('');
                            }
                        },
                        error: function(response) {
                            console.log(response);
                            $('#ajax-error-comment-modal-id').modal('show');
                        }
                    });
                }
            }
        });
    } else {
        $('#empty-comment-modal-id').modal('show');
    }
};

// Per attivare o disattivare le notifiche in ricezione dei messaggi chat in community
Comments.toggleNotification = function(partialUrl) {
    let valueEnabled = $('#toggleNotification')[0].checked ? 1 : 0;
    let completeUrl = partialUrl + valueEnabled;
    $.ajax({
        url: completeUrl,
        contentType: false,
        type: 'post',
        success: function(response) {
            if(valueEnabled){
                $('#iconNotification').html('<span class="am am-notifications-add m-r-5" style="font-size: 24px;"></span>');
            }
            else{
                $('#iconNotification').html('<svg class="m-r-5" style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="currentColor" d="M17.5 13A4.5 4.5 0 0 0 13 17.5A4.5 4.5 0 0 0 17.5 22A4.5 4.5 0 0 0 22 17.5A4.5 4.5 0 0 0 17.5 13M17.5 14.5A3 3 0 0 1 20.5 17.5A3 3 0 0 1 20.08 19L16 14.92A3 3 0 0 1 17.5 14.5M14.92 16L19 20.08A3 3 0 0 1 17.5 20.5A3 3 0 0 1 14.5 17.5A3 3 0 0 1 14.92 16M12 2C10.9 2 10 2.9 10 4C10 4.1 10 4.19 10 4.29C7.12 5.14 5 7.82 5 11V17L3 19V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 17.5 11A6.5 6.5 0 0 1 19 11.18V11C19 7.82 16.88 5.14 14 4.29C14 4.19 14 4.1 14 4C14 2.9 13.11 2 12 2M10 21C10 22.11 10.9 23 12 23C12.5 23 12.97 22.81 13.33 22.5A6.5 6.5 0 0 1 12.03 21Z"/></svg>');
            }
        }
    });
};

// Cancella un commento
Comments.deleteComment = function() {
    // Id numerico
    id = Comments.idDeleteComment.split("-")[1];

    $.ajax({
        url: '/comments/comment/delete?id=' + id,
        contentType: false,
        type: 'post',
        beforeSend: function(response) {
            $('#comment-'+id).remove();
            Comments.totalComments--;
        }
    });
};

// Aggiorna un commento
Comments.updateComment = function() {
    // Creazione dati
    var formData = new FormData();
    let comment_text = $('#contribute-area').val();
    formData.append('Comment[comment_text]', comment_text);

    // Check if the comment contains words from the blacklist
    $.ajax({
        url: '/comments/comment-blacklist/scan-comment',
        data: {
            commentText: comment_text,
            isChat: true
        },
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response.containsBlacklistedWords) {
                $('#scan-comment-modal-id').find('.modal-body').html(response.modalBody);
                if (!$('#scan-comment-modal-id .modal-footer').length) {
                    $(response.modalFooter).insertAfter('#scan-comment-modal-id .modal-body');
                }
                $('#scan-comment-modal-id').modal('show');
            } else {
                $.ajax({
                    url: '/comments/comment/update?id=' + Comments.idUpdateComment,
                    contentType: false,
                    processData: false,
                    data: formData,
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#contribute-area').val('');
                        Comments.saveButton();
                        Comments.disableSendButton();
                        comment_text = Comments.at2Strong(comment_text);
                        comment_text = Comments.search4Links(comment_text);
                        $('#comment-' + Comments.idUpdateComment + ' p[class*="answer_text"]').html(comment_text);
                    }
                });
            }
        }
    });
};

// Mostra il pulsante per salvare un nuovo commento
Comments.saveButton = function(){
    $('#contribute-btn').show();
    $('#contributeUpdate-btn').hide();
    $('#contributeCancelUpdate-btn').hide();
};

// Mostra il pulsante per modificare un commento selezionato
Comments.updateButton = function(){
    $('#contribute-btn').hide();
    $('#contributeUpdate-btn').show();
    $('#contributeCancelUpdate-btn').show();
};

// Posiziona la barra laterale in basso, per mostrare sempre l'ultimo messaggio disponibile
Comments.updateScroll = function(){
    $('.comment-chat-content').scrollTop(
        $('.comment-chat-content').prop('scrollHeight') - $('.comment-chat-content').innerHeight()
    );
};

Comments.disableSendButton = function(){
    $('#contribute-btn').prop('disabled', true);
    $('#contributeUpdate-btn').prop('disabled', true);
};

Comments.enableSendButton = function(){
    $('#contribute-btn').prop('disabled',false);
    $('#contributeUpdate-btn').prop('disabled',false);
};

// Mostra graficamente il messaggio da modificare
Comments.selectedComment = function(){
    $('#comment-' + Comments.idUpdateComment).addClass('edit-comment-chat');
    $('.write-comment-chat').addClass('edit-comment-chat');
};

// Rimuove la grafica attorno al commento da modificare
Comments.resetSelectedComment = function(){
    if(Comments.idUpdateComment != null) $('#comment-' + Comments.idUpdateComment).removeClass('edit-comment-chat');
    if(Comments.idUpdateComment != null)$('.write-comment-chat').removeClass('edit-comment-chat');
}

// Aggiornamento automatico
Comments.autoupdate = function(){
    $.ajax({
        url: '/comments/comment/update-chat-ajax?id=' + Comments.lastMessageId,
        contentType: "application/json; charset=utf-8",
        data: JSON.stringify({
            'context' : Comments.context,
            'context_id' : Comments.context_id,
            'timeCheck' : Comments.updateTimeRequest(),
            'model' : Comments.model
        }),
        dataType: "json",
        type: 'post',
        success: function(response) {
            if(response[0].length > 0) {
                for (let i = 0; i < response[0].length; i++) {
                    Comments.insertNewMessage(response[0][i]);
                }
                Comments.updateScroll();
                Comments.lastMessageId = response[0][response[0].length - 1]['id'];
            }

            if(response[1].length > 0){
                for (let i = 0; i < response[1].length; i++) {
                    $('#comment-' + response[1][i]['id'] + ' p[class*="answer_text"]').text(response[1][i]['message']);
                }
            }
            if(response[2].length > 0){
                for (let i = 0; i < response[2].length; i++) {
                    $('#comment-' + response[2][i]['id']).remove();
                    Comments.totalComments--;
                }
            }
        }
    });
};

// Ritorna l'ora dell'ultimo aggiornamento automatico effettuato
Comments.updateTimeRequest = function(){
    var currentdate = new Date();currentdate.setSeconds(currentdate.getSeconds() - Comments.autoupdateInterval);
    var datetime = currentdate.getFullYear() + '-' +
        ('0' + (currentdate.getMonth()+1)).slice(-2) + '-' +
        ('0' + currentdate.getDate()).slice(-2) + ' ' +
        ('0' + currentdate.getHours()).slice(-2) + ':' +
        ('0' + currentdate.getMinutes()).slice(-2) + ':' +
        ('0' + currentdate.getSeconds()).slice(-2);
    return datetime;
};

// Inserisce un nuovo messaggio in chat
Comments.insertNewMessage = function(response){
    let text = Comments.at2Strong(response['message']);
    text = Comments.search4Links(text);
    let newMessage = $(Comments.template).appendTo('.comment-chat-content');
    Comments.totalComments++;

    // Modifiche al nuovo messaggio
    newMessage.attr('id', 'comment-' + response['id']);

    newMessage.find('.square-img').attr('src', response['image']);
    newMessage.find('.square-img').attr('alt', response['nomeCognome']);

    newMessage.find('a').attr('href', response['profileId']);
    newMessage.find('a').text(response['nomeCognome']);

    newMessage.find('.date-message').text(response['date']);

    newMessage.find('.answer_text').html(text);

    newMessage.find('.deleteComment').attr('id', 'deleteComment-' + response['id']);
    newMessage.find('.updateComment').attr('id', 'updateComment-' + response['id']);

    // Eliminazione dei pulsantini se non ci sono i permessi per vederli
    if(!response['deleteCAN'] && response['deleteCAN'] !== undefined) newMessage.find('.deleteComment').remove();
    if(!response['updateCAN'] && response['deleteCAN'] !== undefined) newMessage.find('.updateComment').remove();

    // Se ci sono troppi messaggi cancella il più vecchio
    if(Comments.totalComments > 100){
        $('.content-message-chat').first().remove();
        Comments.totalComments--;
    }
};

// Cerca le chiocciole per mettere in evidenza i tag
Comments.at2Strong = function(text){
    let spaceArray = [];
    let atArray = [];
    let atFound = false
    let searchForSpaces = false;

    // Analisi
    for(var i=0; i < text.length; i++){
        if(atFound){
            // Serve l'indice extra per il successivo substring()
            if(text[i] === ' '){
                spaceArray.push(i);
                atFound = false;
            }
            else if(i === text.length - 1){
                spaceArray.push(i + 1);
                atFound = false;
            }
        }
        else{
            if(text[i] === '@'){
                if((i > 0 && text[i - 1] === ' ') || i === 0){
                    atArray.push(i);
                    atFound = true;
                }
            }
        }
    }
    // Modifica della stringa
    if(atArray.length > 0){
        text = Comments.createStrong(text, atArray, spaceArray);
    }
    return text;
};

// Crea i tag strong nei punti precedentemente ricercati
Comments.createStrong = function(str, atArray, spaceArray){
    let result = '';
    let startPoint = 0;
    for(let i=0; i < atArray.length; i++){
        result += str.substring(startPoint, atArray[i]);
        result += '<strong>' + str.substring(atArray[i], spaceArray[i]) + '</strong>';
        startPoint = spaceArray[i];
    }
    result += str.substring(startPoint, str.length);
    return result;
}

// Mette in evidenza i link
Comments.search4Links = function(text){
    //const urlRegex = /(https?:\/\/[^\s]+)/g;
    text = text.replace(
        /((http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/g,
        '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
    );
    return text;
}