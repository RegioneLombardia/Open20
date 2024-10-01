function calcMainMinHeight() {
    var headerHeight = $('#headerContent').outerHeight();
    var btnModifyCmsPage = $('.btn-modify-cms-page').outerHeight();
    var footerHeight = $('#footerContent > .it-footer-main').outerHeight();
    var viewportHeight = window.innerHeight;
    var spaceFromHeaderToMainContent = '0';
    $('#mainContent > main').css('margin-top', Number(headerHeight) + Number(spaceFromHeaderToMainContent));
    $('#mainContent > main').css('min-height', Number(viewportHeight) - (Number(headerHeight) + Number(footerHeight)) );
    $('#mainContent > #sidebarLeftRedattore').css('margin-top', Number(headerHeight) + Number(spaceFromHeaderToMainContent));
    $('#mainContent > #sidebarLeftRedattore').css('min-height', Number(viewportHeight) - (Number(headerHeight) + Number(footerHeight)) );
    $('.favorite-from-cms').css('top', Number(headerHeight) + Number(btnModifyCmsPage) + Number(5));
};

$(document).ready(function () {
    calcMainMinHeight();
});

$(window).on('resize',function () {
    calcMainMinHeight();
});