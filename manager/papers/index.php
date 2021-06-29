<?php

    require_once '../mylib.php';
    require_once '../include.php';
    show_header_include('Quản Lý Vật Tư');

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

<script class="ppjs">

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



   
     

    $(function () {

        var colM = [

            { title: "VTID", width: 10, dataIndx: "VTID", editable: false,  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] } },          

            { title: "Tên", width: 200, dataIndx: "name", filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Số Lượng Tồn", width: 120, dataIndx: "quantity", 
				render: function(ui){ return formatNumber(ui.cellData);}, 
				filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }
			},

            { title: "Đơn Giá Tờ", width: 100, dataIndx: "don_gia_to",  
				render: function(ui){ return formatNumber(ui.cellData);}, 
				filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }
			},
			
			 { title: "Clicks", width: 100, dataIndx: "click_num"}
			 

        ];

        var dataModel = {

            dataType: "JSON",

            location: "local",

            recIndx: "VTID"            

        }



        var obj = { 

            title: "Quản lý giấy",

            width:'98%',

            height:'98%',

            showBottom: false,

            dataModel: dataModel,

            scrollModel: {lastColumn: null},

            filterModel: { on: true, mode: "AND", header: true },

            colModel: colM,
         
            selectionModel: { type: 'cell' },

            pageModel: { type: "local", rPP: 100, strRpp: "{0}", strDisplay: "{0} to {1} of {2}" },

            numberCell: { show: false },

            resizable: true,

            trackModel: { on: true }, //to turn on the track changes.            

          

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

           
		    toolbar: {

                items: [

                    { type: 'button', icon: 'ui-icon-plus', label: 'Thêm Loại Giấy Mới', listener:

                        { "click": function (evt, ui) {

                            //append empty row at the end.                            

                            var rowData = { name:"x", don_gia_to:"0", quantity:"0"}; //empty row

                            var rowIndx = $grid.pqGrid("addRow", { rowData: rowData });

                            $grid.pqGrid("goToPage", { rowIndx: rowIndx });

                            $grid.pqGrid("setSelection", null);

                            $grid.pqGrid("setSelection", { rowIndx: rowIndx, dataIndx: 'name' });

                            $grid.pqGrid("editFirstCellInRow", { rowIndx: rowIndx });

                        }

                        }

                    },

                    {

                        type: "<span class='saving'>Saving...</span>"

                    },


                ]

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
                        console.log(newRow);
                        

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

                        url: 'sPapers.php',

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
							console.log('success');

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
			

        };

        

        var $grid = $("div#grid_php").pqGrid(obj);

       

        

        //load all data at once

        $grid.pqGrid("showLoading");

        $.ajax({ url: "sPapers.php",

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
	});	




        

        

        

        

        

  

</script>    

<div id="grid_php" style="margin:5px auto;"></div>


</body>

</html>