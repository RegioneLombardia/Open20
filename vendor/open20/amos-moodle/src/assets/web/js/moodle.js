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
            } else if (lessonModname == "customcert") {
                modal.find('.modal-body').load('/moodle/lesson/customcert-detail?lessonId=' + lessonId + 'certificateId=' + lessonInstance);
            } else if (lessonModname == "quiz") {
                modal.find('.modal-body').load('/moodle/lesson/quiz-detail?lessonId=' + lessonId + '&quizId=' + lessonInstance);
            } else if (lessonModname == "page") {
                modal.find('.modal-body').load('/moodle/lesson/page-detail?lessonId=' + lessonId + '&pageId=' + lessonInstance);
            } else if (lessonModname == "questionnaire") {
                modal.find('.modal-body').load('/moodle/lesson/questionnaire-detail?lessonId=' + lessonId + '&questionnaireId=' + lessonInstance);
            } else if (lessonModname == "resource") {
                modal.find('.modal-body').load('/moodle/lesson/resource-detail?lessonId=' + lessonId + '&resourceId=' + lessonInstance);
            }
        })
    .on('hidden.bs.modal', function () {
        location.reload(); // quando viene chiusa, ricarica la pagina padre
    });

    $("body").on('click', "#btn-get-resource", function(e) {
        e.preventDefault();
        
        var href = $(this).attr('href');
        opened = window.open(href);
        
        $(this).text('Chiudi');
        this.id = "btn-close-modal";
    });
    
    $("body").on('click', "#btn-close-modal", function(e) {
        e.preventDefault();

        $("#lesson-info .close").click()
    });
    
});
