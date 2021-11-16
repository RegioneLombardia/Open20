window.onload = function () {

    // If pretty URLs are enabled ('urlManager' => ['enablePrettyUrl' => 'true'])
    // one can use relative URLs, so controller_url can be emtpy.
    // But when the index action is called implicitly (when no action is given in the URL)
    // the generated URL for the action run-task is missing the controller name,
    // so this URL has to be defined in 'urlManager' => 'rules'
    var controller_url = '';

    // tasks list page
    $('.run_task').click(function () {
        runTask({id: $(this).attr('id')});
        return false;
    });
    $('#select_all').change(function () {
        if ($(this).prop('checked')) {
            $('.task_checkbox').prop('checked', 'checked');
        } else {
            $('.task_checkbox').prop('checked', '');
        }
    });
    $('#execute_action').click(function (e) {
        e.preventDefault();
        var mode = $('#mode').find('option:selected').val();
        var tasks = $('.task_checkbox:checked').map(function () {
            return $(this).val();
        }).get();
        if ('Run' == mode) {
            runTask({id: tasks});
        } else {
            $.post($(this).prop("form").action, {id: tasks, mode: mode}, function () {
                window.location.reload();
            });
        }
        return false;
    });
    $('.show_output').click(function () {
        $('#output_container').html('Loading...');
        $.post(controller_url + 'get-output', {task_run_id: $(this).attr('data-task-run-id')}, function (data) {
            $('#output_container').html(data);
            return false;
        });
    });
    $('#run_custom_task').click(function () {
        runTask({custom_task: $('#command').val()});
        return false;
    });

    function runTask(data) {
        $('#output_section').show();
        $('#task_output_container').text('Running...');
        $.post(controller_url + 'run-task', data, function (data) {
            $('#task_output_container').html(data);
        }).fail(function (xhr, textStatus, errorThrown) {
            alert(xhr.responseText);
        });
    }

    //edit page
    $('#method').change(function () {
        $('#task-command').val($(this).val());
    });

    function getRunDates() {
        $.post(controller_url + 'get-dates', {time: $('#task-time').val()}, function (data) {
            $('#dates_list').html(data);
        })
    }

    var $time = $('#task-time');
    $time.change(function () {
        getRunDates();
    });
    if ($time.length) {
        getRunDates();
    }
    $('#times').change(function () {
        $time.val($(this).val());
        getRunDates();
    });

    //export page
    $('#parse_crontab_form').submit(function () {
        $.post(controller_url + 'parse-crontab', $(this).serialize(), function (data) {
            var list = '';
            data.forEach(function (element) {
                element.forEach(function (el) {
                    list += '' + el + '<br>';
                });
                list += '<hr>';
            });
            $('#parse_result').html(list);
        }, 'json');
        return false;
    });
    $('#export_form').submit(function () {
        $.post(controller_url + 'export-tasks', $(this).serialize(), function (data) {
            var list = '';
            data.forEach(function (element) {
                list += '' + element + '<br>';
            });
            $('#export_result').html(list);
        }, 'json');
        return false;
    });
};