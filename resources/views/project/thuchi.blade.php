@extends('page-grid')

@section('content')
    <script class="ppjs">
        var sumPlus = 0;
        var sumMinor = 0;

        var arrPOM = [{
                "value": "0",
                "text": "Chi"
            },
            {
                "value": "1",
                "text": "Thu"
            }
        ];

        var PID = 0;
        $.paramquery.pqSelect.prototype.options.bootstrap.on = true;



        $(function() {
            var colM = [{
                    title: "ID",
                    width: 10,
                    dataIndx: "id",
                    editable: false,
                    hidden: true,
                },
                {
                    title: "Thu/Chi",
                    width: 100,
                    dataIndx: "pom",
                    editable: true,
                    filter: {
                        type: 'select',
                        condition: 'equal',
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrPOM,
                        listeners: ['change']
                    },
                    render: function(ui) {
                        var pom = ui.cellData;
                        if (pom == 0) {
                            return '<div style="background:pink;">Chi</div>';
                        } else if (pom == 1) {
                            return '<div style="background:#84c428;">Thu</div>';
                        }

                    },
                    editor: {
                        type: 'select',
                        init: function(ui) {
                            ui.$cell.find("select").pqSelect();
                        },
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrPOM
                    },
                    validations: [{
                        type: 'minLen',
                        value: 1,
                        msg: "Required"
                    }]
                },
                {
                    title: "PID",
                    width: 50,
                    dataIndx: "pid",

                },
                {
                    title: "Nội dung",
                    width: 250,
                    dataIndx: "title",

                },

                {
                    title: "Số tiền",
                    width: 100,
                    dataIndx: "amount",
                    render: function(ui) {
                        return '<div class="money">' + formatNumber(ui.cellData) + '</div>';
                    }
                },
                {
                    title: "Ngày nhập (tháng/ngày/năm)",
                    width: "200",
                    dataIndx: "created_at",
                    dataType: 'date',
                    editable: true,
                    editor: {
                        type: 'textbox',
                        init: dateEditor
                    },
                    filter: {
                        type: 'textbox',
                        condition: "between",
                        init: pqDatePicker,
                        listeners: ['change']
                    }
                }
            ];

            var dataModel = {
                dataType: "JSON",
                location: "local",
                recIndx: "id"
            }



            var obj = {
                title: "Quản lý thu chi",
                width: '98%',
                height: '70%',
                showBottom: true,
                dataModel: dataModel,
                scrollModel: {
                    lastColumn: null
                },
                filterModel: {
                    on: true,
                    mode: "AND",
                    header: true
                },
                colModel: colM,
                selectionModel: {
                    type: 'row'
                },
                pageModel: {
                    type: "local",
                    rPP: 200,
                    strRpp: "{0}",
                    strDisplay: "{0} to {1} of {2}"
                },
                numberCell: {
                    show: false
                },
                resizable: true,
                trackModel: {
                    on: true
                }, //to turn on the track changes.
                toolbar: {
                    items: [{
                            type: 'button',
                            icon: 'ui-icon-plus',
                            label: 'Thêm Thu/Chi',
                            listener: {
                                "click": function(evt, ui) {
                                    //append empty row at the end.
                                    var rowData = {
                                        title: "",
                                        pom: 1,
                                        amount: 0,
                                    }; //empty row
                                    var rowIndx = $grid.pqGrid("addRow", {
                                        rowData: rowData
                                    });
                                    $grid.pqGrid("goToPage", {
                                        rowIndx: rowIndx
                                    });
                                    $grid.pqGrid("setSelection", null);
                                    $grid.pqGrid("setSelection", {
                                        rowIndx: rowIndx,
                                        dataIndx: 'name'
                                    });
                                    $grid.pqGrid("editFirstCellInRow", {
                                        rowIndx: rowIndx
                                    });
                                }
                            }
                        },
                        {
                            type: 'separator'
                        },
                        {
                            type: 'button',
                            icon: 'ui-icon-arrowreturn-1-s',
                            label: 'Undo',
                            cls: 'changes',
                            listener: {
                                "click": function(evt, ui) {
                                    $grid.pqGrid("history", {
                                        method: 'undo'
                                    });
                                }
                            },
                            options: {
                                disabled: true
                            }
                        },
                        {
                            type: 'button',
                            icon: 'ui-icon-arrowrefresh-1-s',
                            label: 'Redo',
                            listener: {
                                "click": function(evt, ui) {
                                    $grid.pqGrid("history", {
                                        method: 'redo'
                                    });
                                }
                            },
                            options: {
                                disabled: true
                            }
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
                    saveKey: $.ui.keyCode.ENTER,
                    uponSave: 'next'
                },
                editor: {
                    select: true
                },

                change: function(evt, ui) {
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
                            var valid = grid.isValid({
                                rowData: newRow,
                                allowInvalid: true
                            }).valid;
                            if (valid) {
                                addList.push(newRow);
                            }
                        } else if (type == 'update') {
                            var valid = grid.isValid({
                                rowData: rowData,
                                allowInvalid: true
                            }).valid;
                            if (valid) {
                                if (rowData[recIndx] == null) {
                                    addList.push(rowData);
                                }
                                //else if (grid.isDirty({rowData: rowData})) {
                                else {
                                    updateList.push(rowData);
                                }
                            }
                        } else if (type == 'delete') {
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
                            async: false,
                            beforeSend: function(jqXHR, settings) {
                                $(".saving", $grid).show();
                            },
                            success: function(changes) {
                                // console.log(changes);
                                grid.commit({
                                    type: 'add',
                                    rows: changes.addList
                                });
                                grid.commit({
                                    type: 'update',
                                    rows: changes.updateList
                                });
                                grid.commit({
                                    type: 'delete',
                                    rows: changes.deleteList
                                });
                            },
                            complete: function(res) {
                                $(".saving", $grid).hide();
                            },
                            error: function(jqxhr, status, exception) {
                                console.log(exception);
                            }
                        });
                    }
                },

                history: function(evt, ui) {
                    var $grid = $(this);
                    if (ui.canUndo != null) {
                        $("button.changes", $grid).button("option", {
                            disabled: !ui.canUndo
                        });
                    }
                    if (ui.canRedo != null) {
                        $("button:contains('Redo')", $grid).button("option", "disabled", !ui.canRedo);
                    }
                    $("button:contains('Undo')", $grid).button("option", {
                        label: 'Undo (' + ui.num_undo + ')'
                    });
                    $("button:contains('Redo')", $grid).button("option", {
                        label: 'Redo (' + ui.num_redo + ')'
                    });

                },

                load: function(evt, ui) {
                    var grid = $(this).pqGrid('getInstance').grid,
                        data = grid.option('dataModel').data;
                    $(this).pqTooltip();
                    var ret = grid.isValid({
                        data: data,
                        allowInvalid: false
                    });

                },

                refresh: function() {
                    $("#grid_editing").find("button.delete_btn").button({
                            icons: {
                                primary: 'ui-icon-scissors'
                            }
                        })
                        .unbind("click")
                        .bind("click", function(evt) {
                            var $tr = $(this).closest("tr");
                            var rowIndx = $grid.pqGrid("getRowIndx", {
                                $tr: $tr
                            }).rowIndx;
                            $grid.pqGrid("deleteRow", {
                                rowIndx: rowIndx
                            });
                        });



                    var grid = $(this).pqGrid('getInstance').grid;
                    tinhLoiLo(grid);
                },

                create: function(evt, ui) {
                    var CM = $(this).pqGrid('getColModel'),
                        opts = [];
                    for (var i = 0; i < CM.length; i++) {
                        var column = CM[i];
                        if (column.hidden !== true) {
                            opts.push(column.dataIndx);
                        }
                    }

                    $(".columnSelector").val(opts);
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
            $.ajax({
                url: "sThuChi.php",
                cache: false,
                async: true,
                dataType: "JSON",
                success: function(response) {
                    var grid = $grid.pqGrid("getInstance").grid;
                    grid.option("dataModel.data", response.data);
                    grid.refreshDataAndView();
                    grid.hideLoading();
                    //  tinhLoiLo(grid);


                }
            });



        });

        function tinhLoiLo(grid) {
            var sumPlus = 0;
            var sumMinor = 0;
            data = grid.option('dataModel').data;
            for (var i = 0; i < data.length; i++) {

                rowData = data[i];
                // tinh lai tong thu chi
                if (rowData.pom == "1") {
                    sumPlus += removeFormatNumber(rowData.amount);
                } else {
                    sumMinor += removeFormatNumber(rowData.amount);
                }
            }
            var loilo = sumPlus - sumMinor;
            $('#sumPlus').text(formatNumber(sumPlus));
            $('#sumMinor').text(formatNumber(sumMinor));
            $('#loilo').text(formatNumber(loilo));
        }


        function dateEditor(ui) {

            var $inp = ui.$cell.find("input"),
                di = ui.dataIndx,
                rd = ui.rowData,
                minDate, maxDate,
                startDate = rd.startDate,
                endDate = rd.endDate,
                grid = this,
                validate = function(that) {
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
            if (di == "startDate") {
                maxDate = rd.endDate;
            } else if (di == "endDate") {
                minDate = rd.startDate;
            }

            //initialize the editor
            $inp
                .on("input", function(evt) {
                    validate(this);
                })
                .datepicker({
                    minDate: minDate,
                    maxDate: maxDate,
                    changeMonth: true,
                    changeYear: true,
                    showAnim: '',
                    onSelect: function() {
                        this.firstOpen = true;
                        validate(this);
                    },
                    beforeShow: function(input, inst) {
                        return !this.firstOpen;
                    },
                    onClose: function() {
                        this.focus();
                    }
                });
        }


        function pqDatePicker(ui) {
            var $this = $(this);
            $this
                //.css({ zIndex: 3, position: "relative" })
                .datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "c-2:c+1",
                    dateFormat: "mm/dd/yy", // đéo đổi được
                    showButtonPanel: true,
                    onClose: function(evt, ui) {
                        $(this).focus();
                    }
                });
            //default From date
            $this.filter(".pq-from").datepicker("option", "defaultDate", new Date("01/01/2021"));
            //default To date
            $this.filter(".pq-to").datepicker("option", "defaultDate", new Date("01/01/2022"));
        }
    </script>

    <div id="grid_php" style="margin:5px auto;"></div>

    <div id="thuchi-result">
        <div><label>Tổng thu: </label><span id="sumPlus" class="show-money"></span></div>
        <div><label>Tổng chi: </label><span id="sumMinor" class="show-money"></span></div>
        <div><label>Lời/Lỗ: </label><span id="loilo" class="show-money"></span></div>
    </div>

@endsection
