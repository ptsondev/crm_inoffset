@extends('page-grid')

@section('content')
    <script class="ppjs">
        var PID = 0;
        $.paramquery.pqSelect.prototype.options.bootstrap.on = true;

        var arrStatus = [
            @foreach ($arrStatus as $key => $value)
                {
                "value": "{{ $key }}",
                "text": "{{ $value }}"
                },
            @endforeach
        ];


        var arrSourceKhach = [
            @foreach ($arrSources as $key => $value)
                {
                "value": "{{ $key }}",
                "text": "{{ $value }}"
                },
            @endforeach
        ];

        var arrStaff = [
            @foreach ($arrStaff as $key => $value)
                {
                "value": "{{ $key }}",
                "text": "{{ $value }}"
                },
            @endforeach
        ];

        $(function() {
            var colM = [{
                    title: "PID",
                    width: 10,
                    dataIndx: "id",
                    editable: false,
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    },
                    render: function(ui) {
                        var ID = ui.cellData;
                        return '<a href="/project/' + ID + '/edit">' + ID + '</a>';
                    },

                },
                {
                    title: "Tên Khách",
                    width: 180,
                    dataIndx: "name",
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Khách Từ",
                    width: 160,
                    dataIndx: "source",
                    filter: {
                        type: 'select',
                        condition: 'equal',
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrSourceKhach,
                        listeners: ['change']
                    },

                    render: function(ui) {
                        var source = ui.cellData;
                        var result = arrSourceKhach.find(obj => {
                            return obj.value === source
                        })
                        return result.text;
                    },
                    editor: {
                        type: 'select',
                        init: function(ui) {
                            ui.$cell.find("select").pqSelect();
                        },
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrSourceKhach
                    },
                    validations: [{
                        type: 'minLen',
                        value: 1,
                        msg: "Required"
                    }]
                },
                {
                    title: "SDT",
                    width: 150,
                    dataIndx: "phone",
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Email",
                    width: 250,
                    dataIndx: "email",
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Status",
                    width: 200,
                    dataIndx: "status",
                    filter: {
                        type: 'select',
                        condition: 'equal',
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrStatus,
                        listeners: ['change']
                    },
                    render: function(ui) {
                        var status = ui.cellData;
                        var result = arrStatus.find(obj => {
                            return obj.value === status
                        })
                        return result.text;
                    },
                    editor: {
                        type: 'select',
                        init: function(ui) {
                            ui.$cell.find("select").pqSelect();
                        },
                        valueIndx: "value",
                        labelIndx: "text",
                        options: arrStatus
                    },
                    validations: [{
                        type: 'minLen',
                        value: 1,
                        msg: "Required"
                    }]
                },
                {
                    title: "Mô Tả & Quy Cách",
                    width: 400,
                    editor: {
                        type: 'textarea',
                        attr: 'rows=7'
                    },
                    dataIndx: "description",
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Đang phụ trách",
                    width: 120,
                    dataIndx: "assigned",
                    render: function(ui) {
                        var assigned = ui.cellData;
                        var result = arrStaff.find(obj => {
                            return obj.value === assigned
                        })
                        return result.text;
                    },
                },
            ];

            var dataModel = {
                dataType: "JSON",
                location: "local",
                recIndx: "id"
            }



            var obj = {
                title: "Quản lý đơn hàng",
                width: '98%',
                height: '90%',
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
                freezeCols: 2,
                selectionModel: {
                    type: 'cell'
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
                            label: 'Thêm Đơn Hàng',
                            listener: {
                                "click": function(evt, ui) {
                                    //append empty row at the end.
                                    var rowData = {
                                        name: "",
                                        status: "1",
                                        source: "4",
                                        description: "",
                                        assigned: "2",
                                        source: "4"
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
                url: "sProjects.php",
                cache: false,
                async: true,
                dataType: "JSON",
                success: function(response) {
                    var grid = $grid.pqGrid("getInstance").grid;
                    grid.option("dataModel.data", response.data);
                    var column = grid.getColumn({
                        dataIndx: "name"
                    });
                    var filter = column.filter;
                    filter.cache = null;
                    filter.options = grid.getData({
                        dataIndx: ["name"]
                    });
                    grid.refreshDataAndView();
                    grid.hideLoading();
                }
            });

            setInterval(function() {
                $grid.pqGrid("showLoading");
                $.ajax({
                    url: "sProjects.php",
                    cache: false,
                    async: true,
                    dataType: "JSON",
                    success: function(response) {
                        var grid = $grid.pqGrid("getInstance").grid;
                        grid.option("dataModel.data", response.data);
                        var column = grid.getColumn({
                            dataIndx: "name"
                        });
                        var filter = column.filter;
                        filter.cache = null;
                        filter.options = grid.getData({
                            dataIndx: ["name"]
                        });
                        grid.refreshDataAndView();
                        grid.hideLoading();
                    }
                });

            }, 30000);


        });
    </script>

    <div id="grid_php" style="margin:5px auto;"></div>

@endsection
