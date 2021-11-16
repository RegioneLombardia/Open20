// MARGIN TOP bkPage
function calcMainContentMarginTop() {
  var headerHeight = $('#headerContent').outerHeight();
  var footerHeight = $('#footerContent').outerHeight();
  var viewportHeight = window.innerHeight;
  var spaceFromHeaderToMainContent = '0';
  $('#bk-page').css('margin-top', Number(headerHeight) + Number(spaceFromHeaderToMainContent));
  $('#bk-page').css('min-height', Number(viewportHeight) - (Number(headerHeight) + Number(footerHeight)));

};

$(document).ready(function () {
  calcMainContentMarginTop();
});

$(window).on('resize',function () {
  calcMainContentMarginTop();
});