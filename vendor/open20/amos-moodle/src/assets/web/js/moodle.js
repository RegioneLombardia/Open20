$(document).ready(function () {
    $('#lesson-info').on('show.bs.modal', function (event) {
            var link = $(event.relatedTarget); // link that triggered the modal
            var lessonId = link.data('lesson-id'); // Extract info from data-* attributes
            var lessonName = link.data('lesson-name');
            var lessonInstance = link.data('lesson-instance');
            var lessonModname = link.data('lesson-modname');
            var modal = $(this);
            modal.find('#modal-title').text(lessonName);

            if (lessonModname == "scorm") {
                lessonUrl = '/moodle/lesson/lesson-detail?lessonId=' + lessonId; // usato nella callback
                modal.find('.modal-body').load(lessonUrl);
            } else if (lessonModname == "certificate") {
                modal.find('.modal-body').load('/moodle/lesson/certificate-detail?certificateId=' + lessonInstance);
            }
        })
    .on('hidden.bs.modal', function () {
        location.reload(); // quando viene chiusa, ricarica la pagina padre
    });

    function checkChild() {
        if (opened.closed) {
            clearInterval(timer);
            var modal = $('#lesson-info'); // la modale
            //console.log('lessonUrl',lessonUrl);
            modal.find('.modal-body').load(lessonUrl+'&close=1');
        }
    }

    $("body").on('click', ".js-btn-entra", function(event) {
        event.preventDefault();
        var href = $(this).attr('href');
        opened = window.open(href);
        timer = setInterval(checkChild, 500);  // ogni secondo
    });
});

