/**
 * This is the JavaScript widget used by the open20\amos\chat\MessageWidget widget.
 */
(function ($) {
    $.fn.amosChatMessages = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.amosChatMessages');
            return false;
        }
    };

    var view = null;

    var loadTypes = {
        up: 'history',
        down: 'new'
    };

    var events = {
        init: 'initialized',
        send: {
            beforeSend: 'beforeSend.send',
            complete: 'complete.send',
            error: 'error.send',
            success: 'success.send'
        },
        load: {
            beforeSend: 'beforeSend.load',
            complete: 'complete.load',
            error: 'error.load',
            success: 'success.load'
        }

    };

    var defaults = {
        loadUrl: '',
        loadMethod: 'GET',
        sendUrl: false,
        sendMethod: false,
        limit: 10,
        templateUrl: '',
        itemCssClass: 'msg'
    };

    var methods = {
        init: function (user, contact, options) {

            setTimeout(methods.scrollToBottom,1000);
            return this.each(function () {
                var $chat = $(this);
                if ($chat.data('amosChatMessages')) {
                    return;
                }
                var settings = $.extend({}, defaults, options || {});
                $chat.data('amosChatMessages', {
                    settings: settings,
                    user: user,
                    contact: contact,
                    status: 0  // status of the chat, 0: pending load, 1: loading
                });
                var $form = $(settings.form);
                $('body').on('submit', settings.form, function (e) {
                    e.preventDefault();
                    methods.send.apply($chat);
                });

                if (settings.sendUrl) {
                    $form.attr('action', settings.sendUrl)
                }
                if (settings.sendMethod) {
                    $form.attr('method', settings.sendMethod)
                }
                $chat.trigger(events.init);
            });
        },

        resetForm: function () {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $form = $chat.find(widget.settings.form);
            $form.find('input, textarea, select, iframe').each(function () {
                var $input = $(this);
                $input.val('');
            });
            //console.log("Reset");
            tinyMCE.activeEditor.setContent("");
        },

        send: function () {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $form = $chat.find(widget.settings.form);
            var url = $form.attr('action');
               
            $.ajax({
                url: url,
                type: $form.attr('method'),
                dataType: 'JSON',
                data: $form.serialize(),
                beforeSend: function (xhr, settings) {
                    $chat.trigger(events.send.beforeSend, [xhr, settings]);
                },
                complete: function (xhr, textStatus) {
                    $chat.trigger(events.send.complete, [xhr, textStatus]);
                },
                success: function (data) {
                    $chat.trigger(events.send.success, [data]);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $chat.trigger(events.send.error, [xhr, textStatus, errorThrown]);
                }
            });
            methods.scrollToBottom();
        },

        reload: function () {
            var $chat = $(this);
            methods.resetForm.apply($chat);
            methods.empty.apply($chat);
            methods.load.apply($chat);
        },

        load: function (args,send) {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var data = {
                type: loadTypes.up,
                limit: widget.settings.limit
            };
            if (typeof args == 'number') {
                data['limit'] = args;
            } else if (typeof args == 'string') {
                data['type'] = args;
            } else {
                data = $.extend({}, data, args || {});
            }

            var elem = find($chat, data['type'] == loadTypes.up ? 'first' : 'last');

            if (elem) {
                data['key'] = elem.data('key');
            }

            var url = widget.settings.loadUrl;
            $.pjax.defaults.timeout = 40000;
            $.pjax.reload({
                container: "#msg-container-pjax",
            }).done(function () {
                $.pjax.reload({container: '#conversations-container-pjax'});
            }).done(function () {
                if (typeof send == 'string' && send === 'send') {
                    methods.scrollToBottom();
                }
            });
            
        },

        empty: function () {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $container = $chat.find(widget.settings.container);
            $container.empty();
        },

        append: function (data) {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $container = $chat.find(widget.settings.container);
            // if(data.model) {
            // var a = '<div class="item-chat nop msg mine" data-key="900"><div class="text-msg-chat">'+data.model.text+'</div>'+data.model.date+'</div>';}
            if (typeof data == 'object') {
                //$container.prepend(tmpl(widget.settings.templateUrl, data));
                //$container.append(a);
            } else {
                $container.append(data);
            }
        },

        prepend: function (data) {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $container = $chat.find(widget.settings.container);
            if (typeof data == 'object') {
                $container.prepend(tmpl(widget.settings.templateUrl, data));
            } else {
                $container.prepend(data);
            }
        },

        insert: function (data, selector, before) {
            var $chat = $(this);
            var widget = $chat.data('amosChatMessages');
            var $container = $chat.find(widget.settings.container);
            var $elem = $container.find(selector);
            var $message = null;
            if (typeof data == 'object') {
                $message = tmpl(widget.settings.templateUrl, data);
            } else {
                $message = data;
            }
            if (before) {
                $elem.before($message);
            } else {
                $elem.after($message);
            }
        },
     scrollToBottom: function (){
         $('#msg-container-pjax').scrollTop ($('#msg-container-pjax').get(0).scrollHeight - $('#msg-container-pjax').outerHeight());
        },
    destroy: function () {
            return this.each(function () {
                var $chat = $(this);
                var widget = $chat.data('amosChatMessages');
                var $form = $chat.find(widget.settings.form);
                $form.off('.amosChatMessages');
                $chat.removeData('amosChatMessages');
            });
        },

        widget: function () {
            return this.data('amosChatMessages');
        },

        find: function (id) {
            var $chat = $(this);
            return find($chat, id);
        }
    };


    var find = function ($chat, id) {
        var widget = $chat.data('amosChatMessages');
        var $container = $chat.find(widget.settings.container);
        if (typeof id == 'number') {
            return $container.find('[data-key=' + id + ']');
        } else if (id == 'last') {
            return $container.find('.' + widget.settings.itemCssClass).last();
        } else if (id == 'first') {
            return $container.find('.' + widget.settings.itemCssClass).first();
        } else {
            return $container.find(id);
        }
    };

    var tmpl = function (url, data) {
        $.pjax.defaults.timeout = 1000;
        // $.pjax.reload({
        //     container  : "#msg-container-pjax"
        // });
    };

    var gotoBottom = function (id) {
        var element = document.getElementById(id);
        element.scrollTop = element.scrollHeight - element.clientHeight;
    }


})(jQuery);
