$(document).ready(function () {

    $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
        }
        var $subMenu = $(this).next('.dropdown-menu');
        $subMenu.toggleClass('show');


        return false;
    });

    $(".dropdown-submenu a.dropdown-toggle").click(function(){
        $(this).parents('.dropdown-submenu').first().toggleClass("show");
      });

});