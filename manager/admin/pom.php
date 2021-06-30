<?php

require_once '../include.php';

?>

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



    

    $(function () {

        var colM = [

            { title: "PID", width: 10, dataIndx: "PID", editable: false },            

            { title: "Tên Khách", width: 100, dataIndx: "name", filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "SDT", width: 100, dataIndx: "phone",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Email", width: 170, dataIndx: "email",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Status", width: 120, dataIndx: "status",

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

            {   title: "Mô Tả", width: 300, dataIndx: "summary",

                editor: {type:'textarea', attr:'rows=7'} ,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },

             { title: "Tiến Độ", width: 300, dataIndx: "steps",

                editor: {type:'textarea', attr:'rows=7'} ,

                editModel: { keyUpDown: false, saveKey: ''},

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

            },

            { title: "Địa chỉ giao", width: 200, dataIndx: "address"},            

            { title: "Giá Báo", width: 80, dataIndx: "price_out", hidden: true,render: function(ui){

                return formatNumber(ui.cellData);

            }},

            { title: "Giá Gốc", width: 80, dataIndx: "price_in", hidden: true,render: function(ui){

                return formatNumber(ui.cellData);

            }},           

            { title: "Thu Lần 1", width: 80, dataIndx: "pay_1", hidden: true,render: function(ui){

                return formatNumber(ui.cellData);

            }},

            { title: "Thu Lần 2", width: 80, dataIndx: "pay_2", hidden: true,render: function(ui){

                return formatNumber(ui.cellData);

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

            pageModel: { type: "local", rPP: 20, strRpp: "{0}", strDisplay: "{0} to {1} of {2}" },

            numberCell: { show: false },

            resizable: true,

            trackModel: { on: true }, //to turn on the track changes.            

            toolbar: {

                items: [

                    { type: 'button', icon: 'ui-icon-plus', label: 'New Project', listener:

                        { "click": function (evt, ui) {

                            //append empty row at the end.                            

                            var rowData = { status:"1"}; //empty row

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

                    

                    

                    { type: '<span>Hiện các cột: </span>' },

                    { type: 'select', cls: 'columnSelector', value:['PID','status'], options: function (ui) {

                        var CM = $(this).pqGrid('getColModel');

                        opts = [];

                        for (var i = 0; i < CM.length; i++) {

                            var obj = {},

                            column = CM[i];

                            obj[column.dataIndx] = column.title;                             

                            opts.push(obj);

                        }

                        return opts;

                    }, listener: { 'change': function (evt) {

                            var arr = $(this).val(),

                            $grid = $(this).closest('.pq-grid'),

                            CM = $grid.pqGrid('getColModel');



                            for (var i = 0; i < CM.length; i++) {

                                var column = CM[i],

                                dataIndx = column.dataIndx + "";

                                if ($.inArray(dataIndx, arr) == -1) {

                                    CM[i].hidden = true;

                                }

                                else {

                                    CM[i].hidden = false;

                                }

                            }

                            $grid.pqGrid('refresh');

                            }

                        }, attr: "multiple='multiple'", style: "height:60px;"

                    }

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

                        url: 'sProjects.php',

                        data: {

                            list: JSON.stringify({

                                updateList: updateList,

                                addList: addList,

                                deleteList: deleteList

                            })

                        },

                        dataType: "json",

                        type: "POST",

                        async: true,

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

        $.ajax({ url: "sProjects.php",

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

        $.ajax({ url: "sProjects.php",

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

        

        

    });

    

    

    

    

  

</script>    

<div id="grid_php" style="margin:5px auto;"></div>

</body>

</html>