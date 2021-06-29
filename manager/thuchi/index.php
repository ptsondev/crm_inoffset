<?php

require_once '../mylib.php';
require_once '../include.php';
show_header_include('Thu Chi');

if(!is_login()){        

    header("Location: /");

    die;

}

$user = $_SESSION['user'];


if($user['role']!=ROLE_ADMIN){

    header("Location: /");

    die;    

}



display_site_header();



?>

<h3 id="page-title">Thống Kê Thu Chi</h3>
<script class="ppjs">

  var sumPlus = 0;
  var sumMinor = 0;

  var arrPOM = [
    { "value": "0", "text": "Chi" },
    { "value": "1", "text": "Thu" }                        
  ];



$(function () {


     function pqDatePicker(ui) {
        var $this = $(this);
        $this
            //.css({ zIndex: 3, position: "relative" })
            .datepicker({                
                changeMonth: true,
                changeYear: true,
                yearRange: "c-1:c+1",
                dateFormat: "mm/dd/yy", // đéo đổi được
                showButtonPanel: true,
                onClose: function (evt, ui) {
                    $(this).focus();
                }
            });
        //default From date
        $this.filter(".pq-from").datepicker("option", "defaultDate", new Date("01/01/2021"));
        //default To date
        $this.filter(".pq-to").datepicker("option", "defaultDate", new Date("01/01/2022"));
    }

    var colM = [
        { title: "TCID", width: 10, dataIndx: "TCID", editable: false, hidden:true, filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},                
        { title: "Thu/Chi", width: 100, dataIndx: "pom", editable: true,
            filter: { 
                type: 'select',
                condition: 'equal',
                valueIndx: "value",
                labelIndx: "text",
                options: arrPOM, 
                listeners: ['change']
            }, 
            render:function( ui ){
                    var pom = ui.cellData;
                     if(pom==0){
                             return '<div style="background:pink;">Chi</div>';                        
                        }else if(pom==1){                                                   
                            return '<div style="background:#84c428;">Thu</div>';                                                                             
                        }        

            },
            editor:{
                type: 'select',
                init: function (ui) {
                    ui.$cell.find("select").pqSelect();
                },
                valueIndx: "value",
                labelIndx: "text",                                      
                options: arrPOM                                
            },
            validations: [{ type: 'minLen', value: 1, msg: "Required"}]    
        },                   
        { title: "PID", width: 100, dataIndx: "PID",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},
        { title: "Diễn Giải", width: 300, dataIndx: "des"},
        { title: "Số tiền", width: 100, dataIndx: "amount",render: function(ui){
            return '<div class="money">'+formatNumber(ui.cellData)+'</div>';
        }},      
        { title: "Ngày nhập (tháng/ngày/năm)", width: "200", dataIndx: "post_date", dataType: 'date', editable:true,
            editor: {
                type: 'textbox',
                init: dateEditor
            },
            filter: { type: 'textbox', condition: "between", init: pqDatePicker, listeners: ['change'] }
        }
                
    ];


    var dataModel = {
        dataType: "JSON",
        location: "local",
        recIndx: "TCID",
    }




    var obj = { 
        title: "Chi Tiết Thu Chi",
        dataModel: dataModel,
        height:"600",
        colModel: colM,
        filterModel: { 
                on: true, 
                mode: "AND", 
                header: true                
        },
        selectionModel: { type: 'cell' },
        numberCell: { show: false },
        trackModel: { on: true }, 
        toolbar: {
            items: [
                { type: 'button', icon: 'ui-icon-plus', label: 'Thêm Thu Chi', listener:
                    { "click": function (evt, ui) {
                            var rowData = {}; //empty row
                            var rowIndx = $grid.pqGrid("addRow", { rowData: rowData });
                            $grid.pqGrid("goToPage", { rowIndx: rowIndx });
                            $grid.pqGrid("setSelection", null);
                            $grid.pqGrid("setSelection", { rowIndx: rowIndx, dataIndx: 'des' });
                            $grid.pqGrid("editFirstCellInRow", { rowIndx: rowIndx });
                        }

                    }

                },

            ],
           
           
        },           

        editModel: {
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
               
               
            for (var i = 0; i < data.length; i++) {
                rowData = data[i];     
                // tinh lai tong thu chi
                if(rowData.pom=="1"){
                    sumPlus+=removeFormatNumber(rowData.amount);
                }else{
                    sumMinor+=removeFormatNumber(rowData.amount);
                }               
            }
              var loilo = sumPlus-sumMinor;
            $('#sumPlus').text(formatNumber(sumPlus));
            $('#sumMinor').text(formatNumber(sumMinor));
            $('#loilo').text(formatNumber(loilo));
        },
        refresh: function () {
            sumPlus = 0;
            sumMinor = 0;
           var grid = $(this).pqGrid('getInstance').grid,
            data = grid.option('dataModel').data;
            $(this).pqTooltip();
            var ret = grid.isValid({ data: data, allowInvalid: false });      
               
               
            for (var i = 0; i < data.length; i++) {
                rowData = data[i];     
                // tinh lai tong thu chi
                if(rowData.pom=="1"){
                    sumPlus+=removeFormatNumber(rowData.amount);
                }else{
                    sumMinor+=removeFormatNumber(rowData.amount);
                }                
            }
            var loilo = sumPlus-sumMinor;
            $('#sumPlus').text(formatNumber(sumPlus));
            $('#sumMinor').text(formatNumber(sumMinor));
            $('#loilo').text(formatNumber(loilo));
        },

        change: function (evt, ui) {
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
                            } else {
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
                        url: 'rpthuchi.php',
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

                    }); // end ajax

                } // end if list

        } // end change



    } // end obj





     var $grid = $("div#grid_thuchi").pqGrid(obj);



     $grid.pqGrid("showLoading");

        $.ajax({ url: "rpthuchi.php",

            cache: false,

            async: true,

            dataType: "JSON",

            success: function (response) {

                var grid = $grid.pqGrid("getInstance").grid;

                grid.option("dataModel.data", response.data);





                var column = grid.getColumn({ dataIndx: "TCID" });

                var filter = column.filter;

                filter.cache = null;

                filter.options = grid.getData({ dataIndx: ["TCID"] });



                grid.refreshDataAndView();

                grid.hideLoading();



           

            }

        });



        function dateEditor(ui) {
            
            var $inp = ui.$cell.find("input"),
                di = ui.dataIndx,
                rd = ui.rowData,
                minDate, maxDate,
                startDate = rd.startDate,
                endDate = rd.endDate,                
                grid = this,
                validate = function (that) {
                    var valid = grid.isValid({
                        dataIndx: ui.dataIndx,
                        value: $inp.val(),
                        rowIndx: ui.rowIndx
                    }).valid;
                    if (!valid) {
                        that.firstOpen = false;
                    }
                };

            //calculate minDate and maxDate.
            if(di == "startDate"){
                maxDate = rd.endDate;
            }
            else if(di == "endDate"){
                minDate = rd.startDate;
            }

            //initialize the editor
            $inp
            .on("input", function (evt) {
                validate(this);
            })
            .datepicker({
                minDate: minDate,
                maxDate: maxDate,
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


   

});



   


    </script>    

    <div id="grid_thuchi" style="margin:5px auto;"></div>
    <div id="thuchi-result">
        <div><label>Tổng thu: </label><span id="sumPlus" class="show-money"></span></div>
        <div><label>Tổng chi: </label><span id="sumMinor" class="show-money"></span></div>
        <div><label>Lời/Lỗ: </label><span id="loilo" class="show-money"></span></div>
    </div>
</body>

</html>