$(document).ready(function () {
    $('#create-ticket').on('show.bs.modal', function (event) {
        var link = $(event.relatedTarget); // link that triggered the modal

        var categoryName = link.data('category-name');
        var categoryInstance = link.data('category-instance');

        var modal = $(this);
        modal.find('#modal-title').text(categoryName);

        modal.find('.modal-body').load('/ticket/ticket/create?categoriaId=' + categoryInstance);
        //modal.find('.modal-body').load('/ticket/ticket/create?categoriaId=7');

    })
            .on('hidden.bs.modal', function () {
                // location.reload(); // quando viene chiusa, ricarica la pagina padre
            });

    /*function checkChild() {
     if (opened.closed) {
     clearInterval(timer);
     var modal = $('#create-ticket'); // la modale
     //console.log('lessonUrl',lessonUrl);
     modal.find('.modal-body').load(lessonUrl+'&close=1');
     }
     }*/

    /*  $("body").on('click', ".js-btn-entra", function(event) {
     event.preventDefault();
     var href = $(this).attr('href');
     opened = window.open(href);
     timer = setInterval(checkChild, 500);  // ogni secondo
     });*/
    /*$("body").on('click', '#save_ticket_button', function () {
     console.log("sono qua!!");
     var modal = $('#create-ticket'); // la modale
     modal.find('.modal-body').load($(this).attr('value'));
     });*/


    /*  var form = jQuery('#ticket-form');
   
     var modal = $('#create-ticket'); // la modale
      
    var form = modal.find('#ticket-form');
    
    var url= form.attr('action');

    form.on('beforeSubmit', function (e) {
        e.preventDefault();

      jQuery.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: new FormData(form[0]),
            mimeType: 'multipart/form-data',
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                alert('ID: ' + data.id + ' someOtherData:' + data.someOtherData);
            }
        });
        return false;
    });*/




});

