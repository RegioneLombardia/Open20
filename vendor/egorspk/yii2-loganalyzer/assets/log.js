(function($){
    $('.loganalyzer').on('click','.stack-btn',function(e){
        $(this).nextAll('.stack-pre').slideToggle('fast');
        e.preventDefault();
        return false;
    });

    $('#stack-showall').click(function(e){
        $('.stack-pre').slideDown('fast');
        e.preventDefault();
        return false;
    });

    $('#stack-collapseall').click(function(e){
        $('.stack-pre').slideUp('fast');
        e.preventDefault();
        return false;
    });

    $('.clear').click(function(e){
        if(!confirm('Вы хотите удалить этот лог?')) {
            e.preventDefault();
            return false;
        }
    });

    $('.filter-log').click(function (e) {
        var rel   = $(this).attr('rel'),
            error = $('.log-list .error-line'),
            warn  = $('.log-list .warning-line'),
            info  = $('.log-list .info-line');

        if (rel == 'error') {
            error.slideDown('fast');
            warn.slideUp('fast');
            info.slideUp('fast');
        } else if (rel == 'warning') {
            error.slideUp('fast');
            warn.slideDown('fast');
            info.slideUp('fast');
        } else if (rel == 'info') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        }else if (rel == 'all') {
            error.slideDown('fast');
            warn.slideDown('fast');
            info.slideDown('fast');
        }

        e.preventDefault();
        return false;
    });
})(jQuery);