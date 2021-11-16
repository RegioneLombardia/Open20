var Comments = {};

Comments.comment = function (context_id) {
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

Comments.beforeSendAjax = function () {
    $('#comments-loader').toggleClass('hidden show');
    $('#contribute-btn').prop('disabled', true);
    $('#comments_anchor').prop('disabled', true);
};

Comments.completeAjax = function () {
    $('#comments-loader').toggleClass('show hidden');
    $('#contribute-btn').prop('disabled', false);
    $('#comments_anchor').prop('disabled', false);
};

Comments.saveComment = function (context_id, context, csrfKey, csrfValue) {
    // Identify comment container of the content.
    var comment_container = $("#bk-contribute");

    // It retrieves the comment textarea.
    var comment_textarea = comment_container.find("[name='contribute-area']");

    // It retrieves the comment text.
    var comment_text = comment_textarea.val();

    // If the comment text is not empty...
    if (comment_text) {
        // ...it build params.
        var formData = new FormData();
        formData.append('Comment[context]', context);
        formData.append('Comment[context_id]', context_id);
        formData.append('Comment[comment_text]', comment_text);
        if (csrfKey !== null && csrfKey !== 'undefined') {
            formData.append(csrfKey, csrfValue);
        }

        // It retrieves the comment attachments.
        var commentAttachments = $('#commentAttachments').prop('files');
        $.each(commentAttachments, function (key, value) {
            formData.append('commentAttachments[]', value);
        });

        // Find the "send_notify_mail" inputs and add the values to form data.
        var sendNotifyMailInputs = comment_container.find(":input[name^='send_notify_mail']");
        sendNotifyMailInputs.each(function (index) {
            if (($(this).attr('name') === 'send_notify_mail') && ($(this).attr('type') === 'checkbox')) {
                // It retrieves the checkbox value.
                var send_notify_mail_val = ($(this).is(":checked") ? 1 : 0);
                formData.append('send_notify_mail', send_notify_mail_val);
            } else {
                formData.append($(this).attr('name'), $(this).val());
            }
        });

        $.ajax({
            url: '/comments/comment/create-ajax',
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                Comments.beforeSendAjax();
            },
            complete: function (jjqXHR, textStatus) {
                Comments.completeAjax();
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error.msg);
                } else {
                    var urlRedirect = $('#url-redirect').val();
                    if(urlRedirect != '' && urlRedirect != undefined){
                        window.location.href = urlRedirect;
                    }
                    Comments.reloadComments();
                    comment_textarea.val('');
                    Comments.emptyRedactorEditor(comment_textarea.attr('id'));
                    $('#commentAttachments').fileinput('clear');
                }
            },
            error: function (response) {
                console.log(response);
                $('#ajax-error-comment-modal-id').modal('show');
            }
        });
    } else {
        $('#empty-comment-modal-id').modal('show');
    }
};

Comments.reply = function (reply_id) {
    // Identify the container of the comment reply
    var comment_reply_container = $("#bk-comment-reply-" + reply_id);
    comment_reply_container.toggleClass('hidden show');
    comment_reply_container.find('.redactor-editor').focus();
};

Comments.saveCommentReply = function (comment_id, csrfKey, csrfValue) {
    // Identify the container of the comment reply
    var comment_reply_container = $("#bk-comment-reply-" + comment_id);

    // It retrieves the comment reply textarea.
    var comment_reply_textarea = comment_reply_container.find("[name='comment-reply-area']");

    // It retrieves the comment reply text.
    var comment_reply_text = comment_reply_textarea.val();

    // If the comment text is not empty...
    if (comment_reply_text) {
        // ...it build params.
        var formData = new FormData();
        formData.append('CommentReply[comment_id]', comment_id);
        formData.append('CommentReply[comment_reply_text]', comment_reply_text);
        if (csrfKey !== null && csrfKey !== 'undefined') {
            formData.append(csrfKey, csrfValue);
        }

        // It retrieves the comment reply attachments.
        var commentReplyAttachmentsId = '#commentReplyAttachments' + comment_id;
        var commentReplyAttachments = $(commentReplyAttachmentsId).prop('files');
        $.each(commentReplyAttachments, function (key, value) {
            formData.append('commentReplyAttachments[]', value);
        });

        // Find the "send_reply_notify_mail" inputs and add the values to form data.
        var sendNotifyMailInputs = comment_reply_container.find(":input[name^='send_reply_notify_mail']");
        sendNotifyMailInputs.each(function (index) {
            if ($(this).attr('name') === 'send_reply_notify_mail') {
                // It retrieves the checkbox value.
                var send_reply_notify_mail_val = ($(this).is(":checked") ? 1 : 0);
                formData.append('send_reply_notify_mail', send_reply_notify_mail_val);
            } else {
                formData.append($(this).attr('name'), $(this).val());
            }
        });

        $.ajax({
            url: '/comments/comment-reply/create-ajax',
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                Comments.beforeSendAjax();
            },
            complete: function (jjqXHR, textStatus) {
                Comments.completeAjax();
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error.msg);
                } else {
                    Comments.reloadComments();
                }
            },
            error: function (response) {
                console.log(response);
                $('#ajax-error-comment-reply-modal-id').modal('show');
            }
        });
    } else {
        $('#empty-comment-reply-modal-id').modal('show');
    }
};

Comments.reloadComments = function () {
    $.pjax.defaults.timeout = 15000;
    $.pjax.reload({container: '#pjax-block-comments', async: false});
};

Comments.emptyRedactorEditor = function (editorId) {
    tinyMCE.get(editorId).setContent('');
};
