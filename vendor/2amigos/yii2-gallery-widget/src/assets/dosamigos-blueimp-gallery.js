/**
 */
if (typeof dosamigos == "undefined" || !dosamigos) {
    var dosamigos = {};
}
dosamigos.gallery = (function($){
    var pub = {
        registerLightBoxHandlers: function(selector, opts) {
            var options = opts || {};
            $(document).off('click.gallery', selector).on('click.gallery', selector, function() {
                var links = $(this).parent().find('a.gallery-item');
                options.index = $(this)[0];
                blueimp.Gallery(links, options);
                return false;
            });
        }
    };
    return pub;
})(jQuery);
