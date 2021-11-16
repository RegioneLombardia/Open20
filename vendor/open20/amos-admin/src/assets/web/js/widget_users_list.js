$(document).ready(function () {
    $usersList = $('.box-widget.latest-users .list-items');
    watchOnPjaxEvents($usersList);

    function watchOnPjaxEvents ($list) {
        if($list.length){
            $(document).on('pjax:send', function () { 
                $list.addClass('loading'); 
            }).on('pjax:complete', function () { 
                $list.removeClass('loading'); 
            }).on('pjax:end', function () {
                watchOnPjaxEvents($('.box-widget.latest-users .list-items'));
            });
        }
    }
});



