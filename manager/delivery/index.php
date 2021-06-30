<?php

    require_once '../mylib.php';

    require_once '../include.php';
    show_header_include('Giao Hàng');

    if(!is_login()){        

        header("Location: /");

        die;

    }
    if(is_mobile()){
         header("Location: /manager/delivery/mobile.php");
    }

    $user = $_SESSION['user'];

/*
    if($user['role']!=ROLE_DESIGN && $user['role']!=ROLE_ADMIN){

        header("Location: /");

        die;    

    }
*/


    display_site_header();



?>

<h3 id="page-title">Hàng Cần Giao</h3>

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



    $(function () {

        var colM = [

                { title: "PID", width: 10, dataIndx: "PID", editable: false },     

                { title: "Tên Khách", width: 120, dataIndx: "name", editable: false},
                { title: "Điện Thoại", width: 120, dataIndx: "phone",  editable: true},

                {   title: "Thông Tin Giao Hàng", width: 300, dataIndx: "delivery_note",
                    editable: true,                
                    render: function (ui) {
                        var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";
                        return val;
                    },
                }, 
                { title: "Đã Giao", width: 200,render: function(ui){
                    return '<div class="submit_finish chua_thu" project_id="'+ui.rowData['PID']+'" new_status="5"><input type="button" value="Đã Giao & Chưa Thu Tiền" /></div>'+
                    '<div class="submit_finish" project_id="'+ui.rowData['PID']+'" new_status="8"><input type="button" value="Đã Giao & Đã Thu Tiền" /></div>';

                }},        


        ];

        var dataModel = {

            dataType: "JSON",

            location: "local",

            recIndx: "PID"            

        }



        var obj = { 

            title: "Tasks",

            width:'98%',

            height:'90%',

            showBottom: false,

            dataModel: dataModel,

            scrollModel: {lastColumn: null},

            colModel: colM,

            numberCell: { show: false },

             

            editModel: {

                //allowInvalid: true,

                saveKey: $.ui.keyCode.ENTER,

                uponSave: 'next'

            },            

            load: function (evt, ui) {

                var grid = $(this).pqGrid('getInstance').grid,

                    data = grid.option('dataModel').data;

                $(this).pqTooltip();

                var ret = grid.isValid({ data: data, allowInvalid: false });                       

            },

            refresh: function () {

                $("#grid_editing").find("button.delete_btn").button({ icons: { primary: 'ui-icon-scissors'} })

                .unbind("click")

                .bind("click", function (evt) {

                    var $tr = $(this).closest("tr");

                    var rowIndx = $grid.pqGrid("getRowIndx", { $tr: $tr }).rowIndx;

                    $grid.pqGrid("deleteRow", { rowIndx: rowIndx });

                });

                

            },
             change: function (evt, ui) {

                //debugger;

                if (ui.source == 'commit' || ui.source == 'rollback') {

                    return;

                }

                var $grid = $(this),

                    grid = $grid.pqGrid('getInstance').grid;

                var rowList = ui.rowList,

                    addList = [],

                    recIndx = grid.option('dataModel').recIndx,

                    deleteList = [],

                    updateList = [];



                for (var i = 0; i < rowList.length; i++) {

                    var obj2 = rowList[i],

                        rowIndx = obj2.rowIndx,

                        newRow = obj2.newRow,

                        type = obj2.type,

                        rowData = obj2.rowData;                          

                    if (type == 'add') {

                        var valid = grid.isValid({ rowData: newRow, allowInvalid: true }).valid;
                        //console.log('ccc');
                        //console.log(newRow);
                        

                        if (valid) {

                            addList.push(newRow);

                        }

                    }else if (type == 'update') {

                        var valid = grid.isValid({ rowData: rowData, allowInvalid: true }).valid;

                        if (valid) {

                            if (rowData[recIndx] == null) {

                                addList.push(rowData);                                 

                            }

                            //else if (grid.isDirty({rowData: rowData})) {

                            else {

                                updateList.push(rowData);

                            }

                        }

                    }else if (type == 'delete') {

                        if (rowData[recIndx] != null) {

                            deleteList.push(rowData);

                        }

                    }

                }

                if (addList.length || updateList.length || deleteList.length) {

                    $.ajax({

                        url: 'delivery.php',

                        data: {

                            list: JSON.stringify({

                                updateList: updateList,

                                addList: addList,

                                deleteList: deleteList

                            })

                        },

                        dataType: "json",

                        type: "POST",

                        async: false,

                        beforeSend: function (jqXHR, settings) {

                            $(".saving", $grid).show();                            

                        },

                        success: function (changes) {

                            console.log(changes);

                            //commit the changes.                

                            grid.commit({ type: 'add', rows: changes.addList });

                            grid.commit({ type: 'update', rows: changes.updateList });

                            grid.commit({ type: 'delete', rows: changes.deleteList });                            

                        },

                        complete: function (res) {

                            $(".saving", $grid).hide();                            

                        },

                         error: function(jqxhr, status, exception) {

                             console.log(exception);

                         }

                    });

                }
            }
                    

        };

        

        var $grid = $("div#grid_php").pqGrid(obj);

       

        

        //load all data at once

        $grid.pqGrid("showLoading");

        $.ajax({ url: "delivery.php",

            cache: false,

            async: true,

            dataType: "JSON",

            success: function (response) {

                var grid = $grid.pqGrid("getInstance").grid;

                grid.option("dataModel.data", response.data);             
                
                grid.refreshDataAndView();

                grid.hideLoading();

            }

        });



        

        setInterval(function(){



            $grid.pqGrid("showLoading");

            $.ajax({ url: "delivery.php",

                cache: false,

                async: true,

                dataType: "JSON",

                success: function (response) {

                    var grid = $grid.pqGrid("getInstance").grid;

                    grid.option("dataModel.data", response.data);


                       //console.log(response.data);
                var cur_count_tasks = response.data.length;
                var pre_count_tasks =  jQuery('#count-tasks').data('count');
                if(cur_count_tasks > pre_count_tasks){
                    notifyMe('Bạn có task mới');
                    playSound();
                }
                jQuery('#count-tasks').data('count', cur_count_tasks);
                    
                    
                    grid.refreshDataAndView();

                    grid.hideLoading();

                }

            });


            

        }, 30000);

        





        $(document).on('click', '.submit_finish',function(){

            var PID = $(this).attr('project_id');
            var NewStatus = $(this).attr('new_status');
            
             $.ajax({ url: "delivery.php",

                            async: false,

                            dataType: "JSON",

                            data:{PID:PID, NewStatus:NewStatus},

                            success: function (response) {                                

                            }

            });

            $(this).parents('tr').remove();

        });

    });

    

    

    

    
function notifyMe(content) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    var notification = new Notification(content);
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        var notification = new Notification("Hi there!");
      }
    });
  }

  // At last, if the user has denied notifications, and you 
  // want to be respectful there is no need to bother them any more.
}
 function playSound(filename){
     filename='inflicted';
        var mp3Source = '<source src="' + filename + '.mp3" type="audio/mpeg">';
        var oggSource = '<source src="' + filename + '.ogg" type="audio/ogg">';
        var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
      }
    
</script>    

<div id="grid_php" style="margin:5px auto;"></div>
<div id="count-tasks"></div>
<div id="sound"></div>
 
</body>

</html>