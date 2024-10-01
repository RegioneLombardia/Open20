// ATTIVAZIONE TOOLTIP
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
  $('[data-toggle-second="tooltip"]').tooltip();
})

// ATTIVAZIONE POPOVER
$(function () {
  $('[data-toggle="popover"]').popover()
})

// ATTIVAZIONE DATEPICKER
$(document).ready(function() {
    $('.it-date-datepicker').datepicker({
      inputFormat: ["dd/MM/yyyy"],
      outputFormat: 'dd/MM/yyyy',
    });
});

// EFFETTO SHRINK HEADER
$(document).on("scroll", function(){
  if
    ($(document).scrollTop() > 100){
    $("body").addClass("shrink");
  }
  else
  {
    $("body").removeClass("shrink");
  }
});








