jQuery(document).ready(function ($) {
    $("#deadline").datepicker({ dateFormat: 'mm/dd/yy' });

    // nếu status chuyển thành đã ký hoặc duyệt in => load tiến độ thực hiện để assign task
    $('#status').on('change', function () {
        var status = $(this).val();
        var project_id = $(this).attr('pid');
        if (status == 3 || status == 4) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "/ajax/show-process",
                async: false,
                data: {
                    project_id: project_id,
                    status: status
                },
                success: function (data) {
                    $('#project-tasks').html(data.html);
                }
            });
        }
    });


    $('body').on('click', '#btnAddThuChi', function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var pid = $(this).attr('pid');
        var title = $('#txtTitle').val();
        var pom = $('#slThuChi').val();
        var amount = $('#txtAmount').val();

        $.ajax({
            type: 'POST',
            url: "/ajax/add-thuchi-to-project",
            async: false,
            data: {
                pid: pid,
                title: title,
                pom: pom,
                amount: amount
            },
            success: function (data) {
                $('#thuchiTable-region').html(data.thuchiTable);
            }
        });
    })
});