<?php

require_once '../include.php';

?>



<script class="ppjs">

    

    $(function () {

        var books = [

          "ActionScript",

          "AppleScript",

          "Asp",

          "BASIC",

          "C",

          "C++",

          "Clojure",

          "COBOL",

          "ColdFusion",

          "Erlang",

          "Fortran",

          "Groovy",

          "Haskell",

          "Java",

          "JavaScript",

          "Lisp",

          "Perl",

          "PHP",

          "Python",

          "Ruby",

          "Scala",

          "Scheme"

        ];

        var autoCompleteEditor = function (ui) {

            var $inp = ui.$cell.find("input");



            //initialize the editor

            $inp.autocomplete({

                appendTo: ui.$cell, //for grid in maximized state.

                source: (ui.dataIndx == "books" ? books : "/pro/demos/getCountries"),

                selectItem: { on: true }, //custom option

                highlightText: { on: true }, //custom option

                minLength: 0

            }).focus(function () {

                //open the autocomplete upon focus                

                $(this).autocomplete("search", "");

            });

        }

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

        var colM = [

            { title: "Ship Country", dataIndx: "ShipCountry", width: 120,

                editor: {

                    type: "textbox",

                    init: autoCompleteEditor

                },

                validations: [

                    { type: 'minLen', value: 1, msg: "Required" },

                    { type: function (ui) {

                        var value = ui.value,

                            _found = false;

                        //remote validation

                        //debugger;

                        $.ajax({

                            url: "/pro/demos/checkCountry",

                            data: { 'country': value },

                            async: false,

                            success: function (response) {

                                if (response == "true") {

                                    _found = true;

                                }

                            }

                        });

                        if (!_found) {

                            ui.msg = value + " not found in list";

                            return false;

                        }

                    }

                    }

                ]

            },

            { title: "Books", dataIndx: "books", width: 90,

                editor: {

                    type: "textbox",

                    init: autoCompleteEditor

                    //type: function (ui) { return dropdowneditor(this, ui); }

                },

                validations: [

                    { type: 'minLen', value: 1, msg: "Required" },

                    { type: function (ui) {

                        var value = ui.value;

                        if ($.inArray(ui.value, books) == -1) {

                            ui.msg = value + " not found in list";

                            return false;

                        }

                    }, icon: 'ui-icon-info'

                    }

                ]

            },

            { title: "Fruits", dataIndx: "fruits", width: 170,

                //custom editor.

                editor: {

                    options: ['Apple', 'Orange', 'Kiwi', 'Guava', 'Grapes'],

                    type: function (ui) {

                        //debugger;

                        var str = "",

                            options = ui.column.editor.options;

                        $(options).each(function (i, option) {

                            var checked = '';

                            if (option == ui.cellData) {

                                checked = 'checked = checked';

                            }

                            str += "<input type='radio' " + checked + " name='" + ui.dataIndx + "' style='margin-left:5px;' value='" + option + "'>  " + option;

                        });

                        ui.$cell.append("<div class='pq-editor-focus' tabindex='0' style='padding:5px;'>" + str + "</div>");

                    },

                    getData: function (ui) {

                        return $("input[name='" + ui.dataIndx + "']:checked").val();

                    }

                }

            },

            { title: "Order ID", width: 100, dataIndx: "OrderID", editable: false },

		    { title: "Order Date", width: "100", dataIndx: "OrderDate", dataType: 'date',

		        editor: {

		            type: 'textbox',

		            init: dateEditor

		        },

		        render: function (ui) {

		            //return "hello";

		            var cellData = ui.cellData;

		            if (cellData) {

		                return $.datepicker.formatDate('yy-mm-dd', new Date(cellData));

		            }

		            else {

		                return "";

		            }

		        },

		        validations: [

                    { type: 'regexp', value: '^[0-9]{2}/[0-9]{2}/[0-9]{4}$', msg: 'Not in mm/dd/yyyy format' }

                ]

		    },

            { dataIndx: 'ShipViaId', hidden: true }, //hidden column to store ShipVia Id.

		    { title: "Shipping Via", dataIndx: "ShipVia", width: 110,

		        editor: {

		            type: 'select',

		            init: function (ui) {

		                ui.$cell.find("select").pqSelect();

		            },

		            valueIndx: "value",

		            labelIndx: "text",		            		            

                    mapIndices: {"text": "ShipVia", "value": "ShipViaId"},

		            options: [

                        { "value": "", "text": "" },

                        { "value": "SE", "text": "Speedy Express" },

                        { "value": "UP", "text": "United Package" },

                        { "value": "FS", "text": "Federal Shipping" }

                    ] 

		        },

		        validations: [{ type: 'minLen', value: 1, msg: "Required"}]

		    },

            { title: "Freight", dataIndx: "Freight", dataType: "float", width: 100, align: "right",

                editor: { select: true },

                validations: [{ type: 'gte', value: 1, msg: "should be >= 1"}],

                editModel: { keyUpDown: true },

                render: function (ui) {

                    return "$" + parseFloat(ui.cellData).toFixed(2);

                }

            },

            { title: "Shipping Address", width: 200, dataIndx: "ShipAddress",

                render: function (ui) {

                    var val = ui.cellData ? ui.cellData.replace(/\n/g, "<br/>") : "";

                    return val;

                },

                editor: { type: "textarea", attr: "rows=4" },

                editModel: { keyUpDown: false, saveKey: '' },

                validations: [{ type: 'minLen', value: 1, msg: "Required"}]

            }

		];

        var dataModel = {

            location: "remote",

            dataType: "JSON",

            method: "GET",

            sortIndx: "ShipCountry",

            sortDir: "up",

            url: "/content/invoice.json"

        }

        var grid1 = $("div#grid_custom_editing").pqGrid({

            title: "Shipping Orders <b>(Custom editing)</b>",

            showBottom: false,

            dataModel: dataModel,

            colModel: colM,

            selectionModel: { type: 'cell' },

            scrollModel: { autoFit: true },

           

            editModel: {

                saveKey: $.ui.keyCode.ENTER,

                //filterKeys: false,

                keyUpDown: false,

                cellBorderWidth: 0

            },

            numberCell: { show: false },

            resizable: true

        });

    });





</script>    

<div id="grid_custom_editing" style="margin:5px auto;"></div>

</body>

</html>