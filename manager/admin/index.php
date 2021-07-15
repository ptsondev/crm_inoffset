<?php

    require_once '../mylib.php';
    require_once '../include.php';
    show_header_include('Quản Lý Đơn Hàng');
    if(!is_login()){        

        header("Location: /");

        die;

    }

    $user = $_SESSION['user'];

    if($user['role']!=ROLE_ADMIN){

        header("Location: /");

        die;    

    }
    if(is_mobile()){
         header("Location: /manager/admin/sale_mobile.php");
    }

    display_site_header();

?>

<script class="ppjs">
    var PID = 0;

    $.paramquery.pqSelect.prototype.options.bootstrap.on = true;

    var arrStatus = [

                        { "value": "0", "text": "Hủy - Không Làm" },

                        { "value": "1", "text": "Mới" },

                        { "value": "2", "text": "Đã Báo Giá" },

                        { "value": "3", "text": "Đã Ký" },     
						
						{ "value": "7", "text": "Duyệt In" },

                        { "value": "4", "text": "Đã Làm Xong" },

                        { "value": "5", "text": "Đã Giao Hàng - Chưa Thu Tiền" },

                        { "value": "8", "text": "Đã Giao Hàng - Đã Thu Tiền" },
						
						{ "value": "6", "text": "Đã Hoàn Thành" },

                    ];



     var arrSourceKhach = [

                        { "value": "1", "text": "Khách Cũ" },

                        { "value": "2", "text": "Khách Quay Lại" },

                        { "value": "3", "text": "Khách Giới Thiệu" },     
                        
                        { "value": "4", "text": "Khách Mới - GG Ads" },

                        { "value": "5", "text": "Khách Mới - FB Ads" },

                        { "value": "10", "text": "Khác" },

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

            { title: "Tên Khách", width: 150, dataIndx: "name", filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

             { title: "Khách Từ", width: 150, dataIndx: "source",

             filter: { type: 'select',

                condition: 'equal',

                valueIndx: "value",

                labelIndx: "text",

                options: arrSourceKhach, 

                listeners: ['change']

            },

             render:function( ui ){

                    var source = ui.cellData;
                    if(source==1){
                        return 'Khách Cũ';
                    }else if(source==2){
                        return 'Khách Quay Lại';
                    }else if(source==3){
                        return 'Khách Giới Thiệu';
                    }else if(source==4){
                        return 'Khách Mới - GG Ads';
                    }else if(source==5){
                        return 'Khách Mới - FB Ads';
                    }else if(source==10){
                        return 'Khác';
                    }
                    return '';

                },

              editor:{

                    type: 'select',

                    init: function (ui) {

                        ui.$cell.find("select").pqSelect();

                    },

                    valueIndx: "value",

                    labelIndx: "text",                                      

                    options: arrSourceKhach

                },

                validations: [{ type: 'minLen', value: 1, msg: "Required"}]

            },

            { title: "SDT", width: 110, dataIndx: "phone",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Email", width: 180, dataIndx: "email",  filter: { type: 'textbox', condition: 'contain', listeners: ['keyup'] }},

            { title: "Status", width: 160, dataIndx: "status",

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
                         return '<div class="r-status" status="r-huy">Hủy - Không Làm</div>';                        
                    }else if(status==1){                        
                        return '<div class="r-status" status="r-moi">Mới</div>';                                   
                    }else if(status==2){
                        return '<div class="r-status" status="r-dabaogia">Đã Báo Giá</div>';           
                    }else if(status==3){
                        return '<div class="r-status" status="r-ky">Đã Ký</div>';     
                    }else if(status==4){
                        return '<div class="r-status" status="r-lamxong">Đã Làm Xong</div>';     
                    }else if(status==5){                        
                        return '<div class="r-status" status="r-giaochuathu">Đã Giao Hàng Chưa Thu Tiền</div>';     
                    }else if(status==6){
                        return '<div class="r-status" status="r-hoanthanh">Đã Hoàn Thành</div>';     
                    }else if(status==7){
                        return '<div class="r-status" status="r-duyetin">Duyệt In</div>';                             
                    }else if(status==8){
                        return '<div class="r-status" status="r-giaothutien" >Đã Giao Hàng & Thu Tiền</div>';     
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

            { title: "Timeline", width: 150, dataIndx: "",render: function(ui){
				return ui.rowData['last_process']+'<br><hr><div class="showTimeline" product_id="'+ui.rowData['PID']+'">Xem</div>';			

            }},
		
           
            { title: "Ngày đăng", width: "100", dataIndx: "created", dataType: 'date',	editable:false,	 hidden:true,      

		        render: function (ui) {

		            var cellData = ui.cellData;

                    var ts = new Date(cellData * 1000);

                    return ts.toLocaleDateString();		           

		        }		       

		    },           

        ];

        var dataModel = {

            dataType: "JSON",

            location: "local",

            recIndx: "PID"            

        }



        var obj = { 

            title: "Quản lý đơn hàng",

            width:'98%',

            height:'90%',

            showBottom: true,

            dataModel: dataModel,

            scrollModel: {lastColumn: null},

            filterModel: { on: true, mode: "AND", header: true },

            colModel: colM,

            freezeCols:2,            

            
            selectionModel: { type: 'row'},
            rowSelect: function (evt, ui) {
                //console.log('rowSelect', ui);
                PID = ui.rowData.PID;
                reloadProjectDetail(ui.rowData.PID);
            },


            pageModel: { type: "local", rPP: 150, strRpp: "{0}", strDisplay: "{0} to {1} of {2}" },


            numberCell: { show: false },

            resizable: true,

            trackModel: { on: true }, //to turn on the track changes.            

            toolbar: {

                items: [

                    { type: 'button', icon: 'ui-icon-plus', label: 'Thêm Đơn Hàng', listener:

                        { "click": function (evt, ui) {

                            //append empty row at the end.                            

                            var rowData = { status:"1", assigned:"0",source:"4"}; //empty row

                            var rowIndx = $grid.pqGrid("addRow", { rowData: rowData });

                            $grid.pqGrid("goToPage", { rowIndx: rowIndx });

                            $grid.pqGrid("setSelection", null);

                            $grid.pqGrid("setSelection", { rowIndx: rowIndx, dataIndx: 'name' });

                            $grid.pqGrid("editFirstCellInRow", { rowIndx: rowIndx });

                            resetProjectDetailForm();
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
                        //console.log('ccc');
                        //console.log(newRow);
                        

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

                
                showcolorful();
                

            },

            refresh: function () {

                $("#grid_editing").find("button.delete_btn").button({ icons: { primary: 'ui-icon-scissors'} })

                .unbind("click")

                .bind("click", function (evt) {

                    var $tr = $(this).closest("tr");

                    var rowIndx = $grid.pqGrid("getRowIndx", { $tr: $tr }).rowIndx;

                    $grid.pqGrid("deleteRow", { rowIndx: rowIndx });

                });

                
                showcolorful();
                

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
        // reload after....s



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

        

        

        

        

        

           

         $(document).on('click', '.showTimeline',function(){
            PID = $(this).attr('product_id');
            $('#popupTimeline iframe').attr('src', '/manager/admin/timeline.php?PID='+PID);
            $("#popupTimeline")            
                .dialog({
                    height: 500,
                    width: 1200,
                    modal: true,
                    open: function (evt, ui) {   },
                    close: function () {  },
                    show: {
                        effect: "blind",
                        duration: 500
                    }
                }); 
        });  



         $(document).on('click', '#btnShowThuChi',function(){
            //PID = $(this).attr('product_id');
            $('#popupThuChi iframe').attr('src', '/manager/admin/thuchi.php?PID='+PID);
            $("#popupThuChi")            
                .dialog({
                    height: 500,
                    width: 1200,
                    modal: true,
                    open: function (evt, ui) {   },
                    close: function () {  },
                    show: {
                        effect: "blind",
                        duration: 500
                    }
                }); 
        });  





         function reloadProjectDetail(PID){
            // reset form
             $('#p-summary').val();
           $('#p-summary-design').val();
           $('#p-delivery').val();
           $('#p-admin-note').val();
           $('#project_pictures').html();
           $('#tongthu, #tongchi, #loilo').html();


                $.ajax({
                        url: '/manager/admin/ajax.php',
                        data: {
                            action:'loadProjectDetail',
                            PID:PID
                        },
                        async: false,
                        success:function(response) {
                            project =  JSON.parse(response);
                            console.log(project);
                            
                            $('#p-summary').val(project.summary);
                            $('#p-summary-design').val(project.summary_design);
                            $('#p-delivery').val(project.delivery_note);
                            $('#p-admin-note').val(project.admin_note);
                            $('#project_pictures').html(project.pictures);
                            $('#tongthu').html(formatNumber(project.sum_in));
                            $('#tongchi').html(formatNumber(project.sum_out));
                            $('#loilo').html(formatNumber(project.sum_in - project.sum_out));



                            var action = '/manager/admin/picture_upload.php?PID='+PID;
                            $('#frmUploadProjectPicture').attr('action', action);
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            console.log(xhr);
                            },

                    }); 
            }

          function updateProjectDetail(PID){
                $.ajax({
                        url: '/manager/admin/ajax.php',
                        data: {
                            action:'updateProjectDetail',
                            PID:PID,
                            summary:$('#p-summary').val(),
                            summary_design: $('#p-summary-design').val(),
                            delivery_note: $('#p-delivery').val(),
                            admin_note: $('#p-admin-note').val()
                        },
                        async: false,
                        success:function(response) {
                           console.log(response);
                           $('#showResult').text(response);
                        },
                        error: function(xhr, ajaxOptions, thrownError){
                            console.log(xhr);
                        },

                    }); 
            }


        // save a project
        $('#btnUpdateProject').click(function(){
            updateProjectDetail(PID);
        });


        function resetProjectDetailForm(){
            $('#p-summary').val('');
            $('#p-summary-design').val('');
            $('#p-delivery').val('');
            $('#p-admin-note').val('');
        }

        function showcolorful(){
            $('.r-status').each(function(e){
                var status = $(this).attr('status');
                $(this).parents('tr').addClass(status);              
            });
        }
    });

  

</script>    

<div id="grid_php" style="margin:5px auto;"></div>
<div id="project-detail"><?php require_once('project_detail.php');?></div>


<div id="pid_detail_tc" PID="0"></div>

 <div title="Thu Chi" id="popupThuChi" style="overflow:hidden; display:none;">

     <iframe src="/manager/admin/thuchi.php" width="100%" height="100%"></iframe>

</div>

<div id="deadlinePicker"></div>

<div title="Timeline" id="popupTimeline" style="overflow:hidden; display:none;">

     <iframe src="/manager/admin/timeline.php" width="100%" height="100%"></iframe>

</div>

</body>

</html>