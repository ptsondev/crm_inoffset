function formatNumber (num) {
    if(num){
        num = num.toString();

        num = num.replace(/\./g, '');

        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    return 0;
}



function removeFormatNumber(num){

    if(!num){

        return 0;

    }

    return parseInt(num.replace(/\./g, ''));

}



function showStatus(status){  

        if(status==0){

            return 'Hủy - Không làm';            

        }else if(status==1){

            return 'Mới';            

        }else if(status==2){

            return 'Đã Báo Giá';            

        }else if(status==3){

            return 'Đã Ký';            

        }else if(status==4){

            return 'Đã Làm Xong';            

        }else if(status==5){

            return 'Đã Giao Hàng';            

        }else if(status==6){

            return 'Đã Hoàn Thành';            

        }

        return "---";  

}



function createCookie(name, value, days) {

  var expires;

  if (days) {

    var date = new Date();

    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

    expires = "; expires=" + date.toGMTString();

  }

  else {

    expires = "";

  }

  document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";

}





jQuery(document).ready(function($){ 
    $('#tbCounter').on('click', '.btnRemove', function(){
        var r = confirm("Bạn có chắc không?");
        if (r == true) {
          // gọi ajax
            var CID = $(this).attr('CID');
            var TCID = $(this).attr('TCID');
             $.ajax({
                url: '/manager/admin/ajax.php',
                data: {
                    action:'removeCounter',
                    CID:CID,
                    TCID:TCID,
                },
                async: false,
                success:function(response) {
                    //$(this).remove();             
                    $('.btnRemove[cid='+CID+']').parent().parent().remove();
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(xhr);
                        alert(xhr.status);
                    },

            }); 
        } else {
          
        }
    });
    
    $('#btnSaveCounter').click(function(){
        
        var filename = $('#txtFileName').val();
        var pid = $('#txtPID').val();
        console.log(pid);
        var vtid = $('#slPaper').val();
        var num = $('#txtNum').val();
        var matin = $('#rdMatIn:checked').val();
        var note = $('#txtNote').val();        
        var color = $('#rdClickColor:checked').val();
        $.ajax({
            url: '/manager/admin/ajax.php',
            data: {
                action:'addCounter',
                pid:pid,
                filename:filename,
                vtid:vtid,
                num:num,
                matin:matin,
                note:note,
                color:color
            },
            async: false,
            success:function(response) {
                $('#tbCounter tr.item').remove();
                $('#tbCounter').append(response);
                $('#txtFileName').val('');
                $('#txtNum').val('');
                $('#txtNote').val('');
            },
            error: function(xhr, ajaxOptions, thrownError){
                console.log(xhr);
                    alert(xhr.status);
                },

        }); 
    });
    
    

    $('#btnUpdateTimeline').click(function(){

        var pid = $(this).attr('pid');

        var saleUID = $('#slNhanDon').val();

        var saleTID = $('#slNhanDon').attr('tid');

        var xuLyFileUID = $('#slXuLyFile').val();

        var xuLyFileTID = $('#slXuLyFile').attr('tid');

        var inUID = $('#slIn').val();

        var inTID = $('#slIn').attr('tid');

        var giaCongUID = $('#slGiaCong').val();

        var giaCongTID = $('#slGiaCong').attr('tid');

        var giaoHangUID = $('#slGiaoHang').val();

        var giaoHangTID = $('#slGiaoHang').attr('tid');

        var noteSale = $('#noteSale').val();
        var noteDesign = $('#noteDesign').val();
        var notePrint = $('#notePrint').val();
        var noteProcess = $('#noteProcess').val();
        var noteDelivery = $('#noteDelivery').val();

        $.ajax({

            url: '/manager/admin/ajax.php',

            data: {

                action:'updateTimeLine',

                pid:pid,

                saleUID:saleUID,

                saleTID:saleTID,

                xuLyFileUID:xuLyFileUID,

                xuLyFileTID:xuLyFileTID,

                inUID:inUID,

                inTID:inTID,

                giaCongUID:giaCongUID,

                giaCongTID:giaCongTID,

                giaoHangUID:giaoHangUID,

                giaoHangTID:giaoHangTID,

                noteSale:noteSale,
                noteDesign:noteDesign,
                notePrint:notePrint,
                noteProcess:noteProcess,
                noteDelivery:noteDelivery,

            },

            async: false,

            success:function(response) {

                //alert(response);        

                var today = new Date();

                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

                $('#result').text('Update thành công! ' +time);

            }

        });              

    });


    $('button.tlFinish').click(function(){
        var pid = $('#PID').text();
        var tid = $(this).parent().attr('tid');
        var uid = $('select[tid='+tid+']').val();
        var note = $('.item[tid='+tid+'] .text-full').val();
        var task = $(this).attr('task');

        $.ajax({

            url: '/manager/admin/ajax.php',
            data: {
                action:'updateTimeLine2',
                tid:tid,
                uid:uid,
                note:note,
                pid:pid,
                task:task
            },

            async: false,

            success:function(response) {

                //alert(response);        

                var today = new Date();

                var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();

                $('#result').text('Update thành công! ' +time);
                $('.tlFinish[tid='+tid+']').hide();

                return false;
            }

        });   
        return false;
    });
    
    $(document).on('click', '.btn-finish-mobile',function(){

            var PID = $(this).attr('project_id');
            var TID = $(this).attr('tid');
            
             $.ajax({ url: "mytask.php",

                            async: false,

                            dataType: "JSON",

                            data:{TID:TID, PID:PID},

                            success: function (response) {                                

                            }

            });

            $('#mobile-my-task tr.'+TID).remove();

        });

    // remove picture
    $('body').on('click', '#project_pictures .rm_pic', function(e){
        var picture_id = $(this).attr('picture_id');
      
             $.ajax({url: '/manager/admin/ajax.php',

                            async: false,

                            dataType: "JSON",

                            data:{  action:'removePicture', picture_id:picture_id},

                            success: function (response) {                                
                                  
                            }

            });
          $('#project_pictures .pic-'+picture_id).remove();     
    });


    // search projects
    $('#btnSearchProject').click(function(){
            var pid = $('#txtSearchPID').val();
            var name = $('#txtSearchName').val();
            var phone = $('#txtSearchPhone').val();
            var email = $('#txtSearchEmail').val();
            $.ajax({url: '/manager/admin/ajax.php',
                async: false,
                data:{  
                    action:'searchProject', 
                    pid:pid,
                    name:name,
                    phone:phone,
                    email:email
                },
                success: function (response) {                                
                    //console.log(response);
                    $('#searchProjectResult').html(response);
                }
            });
    });
});