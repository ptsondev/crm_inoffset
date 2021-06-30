<?php

    require_once '../mylib.php';

    require_once '../include.php';
show_header_include('Quản Lý Đơn Hàng');

    if(!is_login()){        

        header("Location: /");

        die;

    }

    $user = $_SESSION['user'];

    if($user['role']!=ROLE_DESIGN && $user['role']!=ROLE_ADMIN){

        header("Location: /");

        die;    

    }



    display_site_header();



?>

<h3 id="page-title">Đang Đợi Xử Lý File...</h3>

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



    

   

     

    $(function () {

        var colM = [

            { title: "PID", width: 10, dataIndx: "PID", editable: false },     

            { title: "Tên Khách", width: 100, dataIndx: "name", editable: false},

            {   title: "Mô Tả", width: 300, dataIndx: "summary",

                editable: false,

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            }, 
            
             { title: "Ghi Chú", width: 300, dataIndx: "steps",

                 editable: false,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },            
            {   title: "Ghi Chú Riêng", width: 300, dataIndx: "note",

                editable: false,

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            }, 


            { title: "Deadline", width: 150, dataIndx: "deadline", editable: false, dataType:'text',},

            { title: "Hoàn Thành", width: 120,render: function(ui){

                return '<div class="submit_finish" project_id="'+ui.rowData['PID']+'"><input type="button" value="Hoàn Thành" /></div>';

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

        $.ajax({ url: "design_projects.php",

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

            $.ajax({ url: "design_projects.php",

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



            

        }, 60000);

        





        $(document).on('click', '.submit_finish',function(){

            var PID = $(this).attr('project_id');
            
             $.ajax({ url: "design_projects.php",

                            async: false,

                            dataType: "JSON",

                            data:{PID:PID},

                            success: function (response) {                                

                            }

            });

            $(this).parents('tr').remove();

        });

    });

    

    

    

    

  

</script>    

<div id="grid_php" style="margin:5px auto;"></div>

</body>

</html>