$(document).ready(function () {
    $(".sondaggi-index").addClass("container-padding");
    $(".sondaggi-form").addClass("container-padding");
    $(".bk-btnFormContainer").addClass("container-padding");

    //allineo i bottoni a quelli del frontend
    $("button.btn.btn-navigation-primary").addClass("btn-default");

    //activate tooltip
    $('[data-toggle="tooltip"]').tooltip();

});