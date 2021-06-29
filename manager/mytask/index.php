<?php

    require_once '../mylib.php';

    require_once '../include.php';
show_header_include('Việc Cần Làm');

    if(!is_login()){        

        header("Location: /");

        die;

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

<h3 id="page-title">Task Của <?php echo $user['fullname'];?></h3>

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



    $(function () {

        var colM = [

            { title: "PID", width: 10, dataIndx: "PID", editable: false },     

            { title: "Tên Khách", width: 100, dataIndx: "name", editable: false},
             { title: "Email", width: 170, dataIndx: "email",  editable: false},

            { title: "Task", width: 100, dataIndx: "task", editable: false},

            {   title: "Mô Tả", width: 300, dataIndx: "summary",

                editable: false,

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            }, 
            
            
             { title: "Ghi Chú", width: 200, dataIndx: "steps",

                 editable: false,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },            
            {   title: "Ghi Chú Riêng", width: 150, dataIndx: "note",

                editable: false,

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            }, 


            { title: "Deadline", width: 150, dataIndx: "deadline", editable: false, dataType:'text',},

            { title: "Hoàn Thành", width: 120,render: function(ui){

                return '<div class="submit_finish" project_id="'+ui.rowData['PID']+'" tid="'+ui.rowData['TID']+'"><input type="button" value="Hoàn Thành" /></div>';

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

                

            }            

        };

        

        var $grid = $("div#grid_php").pqGrid(obj);

       

        

        //load all data at once

        $grid.pqGrid("showLoading");

        $.ajax({ url: "mytask.php",

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

            $.ajax({ url: "mytask.php",

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
            var TID = $(this).attr('tid');
            
             $.ajax({ url: "mytask.php",

                            async: false,

                            dataType: "JSON",

                            data:{TID:TID, PID:PID},

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