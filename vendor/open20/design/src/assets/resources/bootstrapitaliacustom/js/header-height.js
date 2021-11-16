function calcMainMinHeight() {
    var headerHeight = $('#headerContent').outerHeight();
    var footerHeight = $('#footerContent > .it-footer-main').outerHeight();
    var viewportHeight = window.innerHeight;
    var spaceFromHeaderToMainContent = '0';
    $('#mainContent > main').css('margin-top', Number(headerHeight) + Number(spaceFromHeaderToMainContent));
    $('#mainContent > main').css('min-height', Number(viewportHeight) - (Number(headerHeight) + Number(footerHeight)) );
};

$(document).ready(function () {
    calcMainMinHeight();
});

$(window).on('resize',function () {
    calcMainMinHeight();
});