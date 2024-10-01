$(document).ready(function () {
    var formModificato = false;
    var submitted = false;
    jQuery(function () {

        // Cambio lo stato della variabile se si accede ad un elemento del form
        $('form input, form select, form textarea').focus(function () {
            formModificato = true;
        });

        $("form").submit(function () {
            submitted = true;
        });

    });

    window.onbeforeunload = function () {
        if (submitted === false && formModificato === true) {
            return true;
        }
    };

});