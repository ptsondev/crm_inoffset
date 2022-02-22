jQuery(document).ready(function ($) {

    $('body').on('click', '#btnClosePopup', function () {
        $('#my-popup').remove();
        $('body').removeClass('popup-showing');
    });

    setInterval(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/ajax/reload-notification",
            async: false,
            data: {

            },
            success: function (data) {
                if (data.new > 0) {
                    var text = ' (' + data.new + ') ';
                    $('#notice-new').text(text);
                    var tmp = document.title.split(" -- ");
                    document.title = tmp[0] + ' -- ' + text;
                    $('#site-favicon').attr("href", "/images/favicon_notice.ico");
                    //document.getElementById('notice-sound').play();
                    $('#user-notices').html(data.html);
                }
            }
        });

    }, 5000);


    $('body').on('click', '#btnShowNotice', function () {
        $('#user-notices').toggle();
        $('#notice-new').text('');
        var tmp = document.title.split(" -- ");
        document.title = tmp[0];
        $('#site-favicon').attr("href", "/images/favicon.ico");

        // set các notice ở đây là [đã đọc]
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "/ajax/read-notification",
            async: false,
            data: {

            },
            success: function (data) {

            }
        });

        return false;
    });
});

// type: success|danger https://getbootstrap.com/docs/4.0/components/alerts/
function showPopupMessage(msg, type = 'success') {
    $('body').append('<div id="my-popup-mgs" class="alert alert-' + type + '">' + msg + '</div>');
    setTimeout(function () {
        $('#my-popup-mgs').remove();
    }, 2000);
}

function showPopup(content) {
    $('body').append('<div id="my-popup"><div id="btnClosePopup"><i class="far fa-times-circle"></i></div>' + content + '</div>');
    $('body').addClass('popup-showing');
}

function showPopupByID(element_id) {
    $('#' + element_id).html('<div id="my-popup"><div id="btnClosePopup"><i class="far fa-times-circle"></i></div>' + $('#' + element_id).html() + '</div>');
    $('#' + element_id).append('body');
    $('body').addClass('popup-showing');
}

function is_mobile() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        // true for mobile device
        return true;
    } else {
        // false for not mobile device
        return false;
    }
}

function formatNumber(num) {
    if (num) {
        num = num.toString();
        num = num.replace(/\./g, '');
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    return 0;
}



function removeFormatNumber(num) {
    if (!num) {
        return 0;
    }
    return parseInt(num.replace(/\./g, ''));
}



