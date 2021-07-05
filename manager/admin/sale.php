<?php

    require_once '../mylib.php';

    require_once '../include.php';
    show_header_include('Quản Lý Đơn Hàng');

    if(!is_login()){        

        header("Location: /");

        die;

    }

      if(is_mobile()){
         header("Location: /manager/admin/sale_mobile.php");
    }

    $user = $_SESSION['user'];

    if($user['role']!=ROLE_ADMIN && $user['role']!=ROLE_SALE){

        header("Location: /");

        die;    

    }

    display_site_header();

?>

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



    

    var arrStatus = [

                        { "value": "0", "text": "Hủy - Không Làm" },

                        { "value": "1", "text": "Mới" },

                        { "value": "2", "text": "Đã Báo Giá" },

                        { "value": "3", "text": "Đã Ký" },     

                        { "value": "4", "text": "Đã Làm Xong" },

                        { "value": "5", "text": "Đã Giao Hàng" },

                        { "value": "6", "text": "Đã Hoàn Thành" }

                    ];

    var users = [];

             $.ajax({

                  url: 'users.php',

                  dataType: 'json',

                  async: false,

                  success: function(data) {

                        users=data;

                  }

            });

    

     var dateEditor = function (ui) {

            var $inp = ui.$cell.find("input"),

                $grid = $(this),

                validate = function (that) {

                    var valid = $grid.pqGrid("isValid", {

                        dataIndx: ui.dataIndx,

                        value: $inp.val(),

                        rowIndx: ui.rowIndx

                    }).valid;

                    if (!valid) {

                        that.firstOpen = false;

                    }

                };



            //initialize the editor

            $inp

            .on("input", function (evt) {

                validate(this);

            })

            .datepicker({

                changeMonth: true,

                changeYear: true,

                showAnim: '',

                onSelect: function () {

                    this.firstOpen = true;

                    validate(this);

                },

                beforeShow: function (input, inst) {

                    return !this.firstOpen;

                },

                onClose: function () {

                    this.focus();

                }

            });

        }

     

    $(function () {

        var colM = [

            { title: "PID", width: 10, dataIndx: "PID", editable: false,  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] } },          

            { title: "Tên Khách", width: 100, dataIndx: "name", filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "SDT", width: 90, dataIndx: "phone",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Email", width: 170, dataIndx: "email",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Status", width: 110, dataIndx: "status",

             filter: { type: 'select',

                condition: 'equal',

                valueIndx: "value",

                labelIndx: "text",

                      options: arrStatus, 

                listeners: ['change']

            },

             render:function( ui ){

                    var status = ui.cellData;

                    if(status==0){

                         return '<div style="background:pink;">Hủy - Không Làm</div>';                        

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

                        return '<div style="background:#84c428;">Đã Hoàn Thành</div>';                                                                             

                    }                       

                },

              editor:{

		            type: 'select',

		            init: function (ui) {

		                ui.$cell.find("select").pqSelect();

		            },

		            valueIndx: "value",

		            labelIndx: "text",		            		            

                    options: arrStatus

		        },

		        validations: [{ type: 'minLen', value: 1, msg: "Required"}]

            },

             { title: "Giá Báo", width: 80, dataIndx: "price_out",render: function(ui){

                return formatNumber(ui.cellData);

            }},
            
            {   title: "Mô Tả", width: 300, dataIndx: "summary",

                editor: {type:'textarea', attr:'rows=7'} ,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },

             { title: "Ghi Chú Chung", width: 200, dataIndx: "steps",

                editor: {type:'textarea', attr:'rows=7'} ,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },


            { title: "Deadline", width: "150", dataIndx: "deadline",type:'text',dataType:'text',
                    editor: {
                     type: 'textbox', attr:'type=datetime-local',                                          
                 },
                 editModel: { clicksToEdit: 1, saveKey: ''},
                 
            },            

            { title: "Timeline", width: 80, dataIndx: "",render: function(ui){

                return '<div class="showTimeline" product_id="'+ui.rowData['PID']+'">Xem</div>';

            }},


            { title: "Ngày đăng", width: "100", dataIndx: "created", dataType: 'date',	editable:false,	       

		        render: function (ui) {

		            var cellData = ui.cellData;

                    var ts = new Date(cellData * 1000);

                    return ts.toLocaleDateString();		           

		        }		       

		    },            

            /*{ title: "Giá Gốc", width: 80, dataIndx: "price_in", render: function(ui){

                return formatNumber(ui.cellData);

            }},*/           

            { title: "Tổng Thu", width: 80, dataIndx: "sum_in",render: function(ui){

                return '<div class="showTCDetail" product_id="'+ui.rowData['PID']+'">'+formatNumber(ui.cellData)+'</div>';

            }},

            { title: "Tổng Chi", width: 80, dataIndx: "sum_out", render: function(ui){

                return '<div class="showTCDetail" product_id="'+ui.rowData['PID']+'">'+formatNumber(ui.cellData)+'</div>';

            }}                       

        ];

        var dataModel = {

            dataType: "JSON",

            location: "local",

            recIndx: "PID"            

        }



        var obj = { 

            title: "Quản lý dự án",

            width:'98%',

            height:'98%',

            showBottom: false,

            dataModel: dataModel,

            scrollModel: {lastColumn: null},

            filterModel: { on: true, mode: "AND", header: true },

            colModel: colM,

            freezeCols:2,            

            selectionModel: { type: 'cell' },

            pageModel: { type: "local", rPP: 300, strRpp: "{0}", strDisplay: "{0} to {1} of {2}" },

            numberCell: { show: false },

            resizable: true,

            trackModel: { on: true }, //to turn on the track changes.            

            toolbar: {

                items: [

                    { type: 'button', icon: 'ui-icon-plus', label: 'New Project', listener:

                        { "click": function (evt, ui) {

                            //append empty row at the end.                            

                            var rowData = { status:"1", assigned:"0"}; //empty row

                            var rowIndx = $grid.pqGrid("addRow", { rowData: rowData });

                            $grid.pqGrid("goToPage", { rowIndx: rowIndx });

                            $grid.pqGrid("setSelection", null);

                            $grid.pqGrid("setSelection", { rowIndx: rowIndx, dataIndx: 'name' });

                            $grid.pqGrid("editFirstCellInRow", { rowIndx: rowIndx });

                        }

                        }

                    },

                    { type: 'separator' },

                    { type: 'button', icon: 'ui-icon-arrowreturn-1-s', label: 'Undo', cls: 'changes', listener:

                        { "click": function (evt, ui) {

                            $grid.pqGrid("history", { method: 'undo' });

                        }

                        },

                        options: { disabled: true }

                    },

                    { type: 'button', icon: 'ui-icon-arrowrefresh-1-s', label: 'Redo', listener:

                        { "click": function (evt, ui) {

                            $grid.pqGrid("history", { method: 'redo' });

                        }

                        },

                        options: { disabled: true }

                    },

                    {

                        type: "<span class='saving'>Saving...</span>"

                    },


                ]

            },           

            historyModel: {

                checkEditableAdd: true

            },

            editModel: {

                //allowInvalid: true,

                saveKey: $.ui.keyCode.ENTER,

                uponSave: 'next'

            },

            editor: {

                select: true

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

                        console.log(valid);

                        if (valid) {

                            addList.push(newRow);

                        }

                    }

                    else if (type == 'update') {

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

                    }

                    else if (type == 'delete') {

                        if (rowData[recIndx] != null) {

                            deleteList.push(rowData);

                        }

                    }

                }

                if (addList.length || updateList.length || deleteList.length) {

                    $.ajax({

                        url: 'sProjects_sale.php',

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

            },

            history: function (evt, ui) {

                var $grid = $(this);

                if (ui.canUndo != null) {

                    $("button.changes", $grid).button("option", { disabled: !ui.canUndo });

                }

                if (ui.canRedo != null) {

                    $("button:contains('Redo')", $grid).button("option", "disabled", !ui.canRedo);

                }

                $("button:contains('Undo')", $grid).button("option", { label: 'Undo (' + ui.num_undo + ')' });

                $("button:contains('Redo')", $grid).button("option", { label: 'Redo (' + ui.num_redo + ')' });

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

            create: function (evt, ui) {

                var CM = $(this).pqGrid('getColModel'),

                        opts = [];

                for (var i = 0; i < CM.length; i++) {

                    var column = CM[i];

                    if (column.hidden !== true) {

                        opts.push(column.dataIndx);

                    }

                }

                $(".columnSelector").val(opts);

                //disable the ShipCountry column.

                //$(".columnSelector").find("option[value='PID'], option[value='name']").prop('disabled', true);

                $(".columnSelector").pqSelect({

                    checkbox: true,

                    multiplePlaceholder: 'Select visible columns',

                    maxDisplay: 100,

                    width: 'auto'

                });

            },

        };

        

        var $grid = $("div#grid_php").pqGrid(obj);

       

        

        //load all data at once

        $grid.pqGrid("showLoading");

        $.ajax({ url: "sProjects_sale.php",

            cache: false,

            async: true,

            dataType: "JSON",

            success: function (response) {

                var grid = $grid.pqGrid("getInstance").grid;

                grid.option("dataModel.data", response.data);





                var column = grid.getColumn({ dataIndx: "name" });

                var filter = column.filter;

                filter.cache = null;

                filter.options = grid.getData({ dataIndx: ["name"] });



                grid.refreshDataAndView();

                grid.hideLoading();

            }

        });



        

        setInterval(function(){



            $grid.pqGrid("showLoading");

            $.ajax({ url: "sProjects_sale.php",

                cache: false,

                async: true,

                dataType: "JSON",

                success: function (response) {

                    var grid = $grid.pqGrid("getInstance").grid;

                    grid.option("dataModel.data", response.data);





                    var column = grid.getColumn({ dataIndx: "name" });

                    var filter = column.filter;

                    filter.cache = null;

                    filter.options = grid.getData({ dataIndx: ["name"] });



                    grid.refreshDataAndView();

                    grid.hideLoading();

                }

            });



            

        }, 60000);

        

        

        

        

        

        $(document).on('click', '.showTCDetail',function(){

            var PID = $(this).attr('product_id');

            $('#pid_detail_tc').attr('PID', PID);

            $("#popup")            

                .dialog({

                    height: 500,

                    width: 800,

                    //width: 'auto',

                    modal: true,

                    open: function (evt, ui) {   

                        

                    },

                    close: function () {

                         $grid.pqGrid("showLoading");

                        $.ajax({ url: "sProjects_sale.php",

                            cache: false,

                            async: true,

                            dataType: "JSON",

                            success: function (response) {

                                var grid = $grid.pqGrid("getInstance").grid;

                                grid.option("dataModel.data", response.data);





                                var column = grid.getColumn({ dataIndx: "name" });

                                var filter = column.filter;

                                filter.cache = null;

                                filter.options = grid.getData({ dataIndx: ["name"] });



                                grid.refreshDataAndView();

                                grid.hideLoading();

                            }

                        });

                    },

                    show: {

                        effect: "blind",

                        duration: 500

                    }

                }); 

        });

           

           

         $(document).on('click', '.showTimeline',function(){

            var PID = $(this).attr('product_id');

            $('#pid_timeline').attr('PID', PID);

            $("#popupTimeline")            

                .dialog({

                    height: 500,

                    width: 800,

                    //width: 'auto',

                    modal: true,

                    open: function (evt, ui) {   

                        

                    },

                    close: function () {

                        

                    },

                    show: {

                        effect: "blind",

                        duration: 500

                    }

                }); 

        });  

        

    });

  

</script>    

<div id="grid_php" style="margin:5px auto;"></div>

<div id="pid_detail_tc" PID="0"></div>

<div id="pid_timeline" PID="0"></div>

 <div title="Thu Chi" id="popup" style="overflow:hidden; display:none;">

     <iframe src="/manager/admin/thuchi.php" width="100%" height="100%"></iframe>

</div>

<div id="deadlinePicker"></div>

<div title="Timeline" id="popupTimeline" style="overflow:hidden; display:none;">

     <iframe src="/manager/admin/timeline.php" width="100%" height="100%"></iframe>

</div>


</body>

</html>