jQuery(document).ready(function ($) {
    var tid = 0;
    var pid = 0;

    $('body').on('click', '.btnFinish', function () {
        var tid = $(this).attr('tid');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/ajax/task-finish",
            async: false,
            data: {
                tid: tid
            },
            success: function (data) {
                $('#tbTasks tr[tid=' + tid + ']').remove();
            }
        });

    });

    $('body').on('click', '#btnSendReply', function () {
        var uid = $('#reply-to').val();
        var content = $('#reply-content').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/ajax/task-reply",
            async: false,
            data: {
                tid: tid,
                pid: pid,
                uid: uid,
                content: content
            },
            success: function (data) {
                console.log(data);
            }
        });
    });


    $('body').on('click', '.btnSendFeedback', function () {
        $('#feedback-area').show();
        tid = $(this).attr('tid');
        pid = $(this).attr('pid');
        $('#project-id').text(pid);
    });


});
