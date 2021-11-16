/* ATTIVAZIONE TOOLTIP E POPOVER */
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle-second="tooltip"]').tooltip();
})
$(function () {
  $('[data-toggle="popover"]').popover();
})
$('.popover-dismiss').popover({
  trigger: 'focus'
})


/* EFFETTO SHRINK HEADER */
$(document).on("scroll", function () {
  if
    ($(document).scrollTop() > 200) {
    $("body").addClass("shrink");
  }
  else {
    $("body").removeClass("shrink");
  }
});

/* DATE PICKER */
$(document).ready(function () {
  $('.it-date-datepicker').datepicker({
    inputFormat: ["dd/MM/yyyy"],
    outputFormat: 'dd/MM/yyyy',
  });
  $('.it-datepicker-narrow').datepicker({
    inputFormat: ["dd/MM/yyyy"],
    outputFormat: 'dd/MM/yyyy',
    weekDayFormat: 'narrow',
  });

  
    $('.it-date-datepicker').datepicker({
      inline: 'calendar'
    });
  


});


/* PUSH UP ASSISTANNCE */
$(document).on("scroll", function () {
  if
    ($(document).scrollTop() >= 99) {
    $(".bi-assistance").addClass("push-up");
  }
  else {
    $(".bi-assistance").removeClass("push-up");
  }
});

/**
 * Google Chrome fix autocomplete input field
 */
/*$(document).ready(function () {
  var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
  if (isChrome) {
    var allInputInPage = $('input:not([type="hidden"]):not([type="checkbox"])');
    var classFakeRequiredInput = 'js-fake-not-readonly';
    var allInputsFakeRequired = $('.' + classFakeRequiredInput);
    allInputInPage.each(function () {
      $(this).prop('readonly',true);
      $(this).addClass(classFakeRequiredInput);
      $(this).bind('focus', function() {
        $(this).removeClass(classFakeRequiredInput);
        $(this).prop('readonly',false);
      });
    });
  }
}); */

$(document).ready(function () {
  window.setTimeout( function(){
    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    var isEdge = /Edg/.test(navigator.userAgent);
    if ((isChrome)||(isEdge)) {
      var allInputInPage = $('input:not([type="hidden"]):not([type="checkbox"])');
      $('input:-webkit-autofill').each(function () {
        $('label[for="' + $(this).attr('id') + '"').addClass('active');
      });
    }
  },1);
});

