$(document).ready(function () {
    $('.btn-modify-cms-page').hover(function () {
        $('main').addClass('highlight-modify-cms-page');
    },
        function () {
            $('main').removeClass('highlight-modify-cms-page');
        });
});  