$(document).ready(function () {    
    $('#ordina-dopo-risposta').prop('disabled', true);   
   
    $('#ordinamento-radio-risposta').change(function(event, messages) {
       var ord = $('input[type="radio"]:checked').val();     
       if(ord == "dopo"){
           $('#ordina-dopo-risposta').prop('disabled', false);
       }
       else {
           $('#ordina-dopo-risposta').prop('disabled', true);
       }
    });    
});