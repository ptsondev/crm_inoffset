<?php

require_once '../include.php';

show_header_include('Thu Chi');
?>

<script class="ppjs">

    

      var curPID = $('#pid_detail_tc', window.parent.document).attr('PID');
      
        var sumPlus = 0;

        var sumMinor = 0;

    

     var arrPOM = [

                        { "value": "0", "text": "Chi" },

                        { "value": "1", "text": "Thu" }                        

                    ];

    

    $(function () {

        var colM = [

            { title: "TCID", width: 10, dataIndx: "TCID", editable: false },            

            { title: "PID", width: 100, dataIndx: "PID", hidden:true},

            { title: "Thu/Chi", width: 100, dataIndx: "pom",                    

                    editor:{

                        type: 'select',

                        init: function (ui) {

                            ui.$cell.find("select").pqSelect();

                        },

                        valueIndx: "value",

                        labelIndx: "text",                                      

                        options: arrPOM

                    },

                  render:function( ui ){

                        var pom = ui.cellData;

                        if(pom==0){

                             return '<div style="background:pink;">Chi</div>';                        

                        }else if(pom==1){                                                   

                            return '<div style="background:#84c428;">Thu</div>';                                                                             

                        }                       

                    },

                    validations: [{ type: 'minLen', value: 1, msg: "Required"}]

            },

            { title: "Diễn Giải", width: 300, dataIndx: "des"},

            { title: "Số tiền", width: 100, dataIndx: "amount",render: function(ui){

                return formatNumber(ui.cellData);

            }},      

            { title: "Ngày nhập", width: "100", dataIndx: "created", dataType: 'date',  editable:false,        

                render: function (ui) {

                    var cellData = ui.cellData;

                    var ts = new Date(cellData * 1000);

                    return ts.toLocaleDateString();                

                }              

            },    

        ];

        var dataModel = {

            dataType: "JSON",

            location: "remote",

            recIndx: "TCID",

             url: "sThuChi.php?PID="+curPID,

                getData: function (response) {

                    return { data: response.data };

                }

        }



        var obj = { 

            title: "Chi Tiết Thu Chi",

            width:'98%',

            height:'90%',

            showBottom: false,

            dataModel: dataModel,

            colModel: colM,

            selectionModel: { type: 'cell' },

            numberCell: { show: false },

            trackModel: { on: true }, //to turn on the track changes.   

            toolbar: {

                items: [

                    { type: 'button', icon: 'ui-icon-plus', label: 'Thêm Thu Chi', listener:

                        { "click": function (evt, ui) {

                            //append empty row at the end.                            

                            var rowData = {PID:curPID}; //empty row

                            var rowIndx = $grid.pqGrid("addRow", { rowData: rowData });

                            $grid.pqGrid("goToPage", { rowIndx: rowIndx });

                            $grid.pqGrid("setSelection", null);

                            $grid.pqGrid("setSelection", { rowIndx: rowIndx, dataIndx: 'des' });

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

            

            load: function (evt, ui) {

                var grid = $(this).pqGrid('getInstance').grid,

                    data = grid.option('dataModel').data;

                $(this).pqTooltip();

                var ret = grid.isValid({ data: data, allowInvalid: false });      

                //console.log(data);

                

                for (var i = 0; i < data.length; i++) {

                   

                        rowData = data[i];     

                        // tinh lai tong thu chi

                        if(rowData.pom=="1"){

                            sumPlus+=removeFormatNumber(rowData.amount);

                        }else{

                            sumMinor+=removeFormatNumber(rowData.amount);

                        }

                        $('#sumPlus').text(formatNumber(sumPlus));

                        $('#sumMinor').text(formatNumber(sumMinor));

                }

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

                        url: 'sThuChi.php',

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

                            //console.log(changes);

                            //commit the changes.                

                            grid.commit({ type: 'add', rows: changes.addList });

                            grid.commit({ type: 'update', rows: changes.updateList });

                            grid.commit({ type: 'delete', rows: changes.deleteList });    

                            //grid.refreshDataAndView();

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

            

        };

      

        

        var $grid = $("div#grid_thuchi").pqGrid(obj);

       

        



               

    });

    

    

    

    

  

</script>    

<!--<div>Tổng thu: <span id="sumPlus"></span></div>

<div>Tổng chi: <span id="sumMinor"></span></div>-->

<div id="grid_thuchi" style="margin:5px auto;"></div>

</body>

</html>