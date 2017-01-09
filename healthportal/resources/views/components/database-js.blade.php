@section('js')
            <script src="/lib/datatables.net/js/jquery.dataTables.js"></script>

            <script type="text/javascript">
                $(document).ready(function(){
                    clearInputs();

                    var dataTableColumns = [];
                    var filter_obj = {!! json_encode($filters) !!};
                    var search = {!! json_encode($search) !!};
                    var page = "{!! $page !!}";
                    var filters = {};

                    $.each(filter_obj, function(i, rows){
                        $.each(rows.fields, function(x, field){
                            filters[field["fieldname"]] = { type : field["type"], value : null };
                        });
                    });

                    $(".ui.dropdown").dropdown();
                    $(".ui.checkbox").checkbox();
                    $('.datepicker').datepicker({
                        autoclose: true
                    });

                    $("#database-table > thead > tr > th").each(function(){
                        if($(this).attr('column-name') != ""){
                            columnData = { 
                                "data" : $(this).attr('column-name'),
                                "render" : $.fn.dataTable.render.text()
                            };
                            dataTableColumns.push(columnData);
                        }
                    });

                    if($.isEmptyObject(search)){
                        enableSearch = false;
                    }else{
                        enableSearch = true;
                    }

                    if(page == 'client-db'){
                        var logic = { "risk_groups" : null, "sti_diagnosis" : null };
                        var sortingDisabled = [-1, -2];
                    }

                    var table = $("#database-table").DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": enableSearch,
                        "ajax" : {
                            "url" : "database/retrieve",
                            "type" : "GET",
                            "data" : function(d){
                                d.page = page;
                                d.filter = filters;
                                d.searchColumns = JSON.stringify(search);

                                if(page == 'client-db'){
                                    d.logic = logic;
                                }

                                return d;
                            }
                        },
                        "columns" : dataTableColumns,
                        "columnDefs": [{ orderable: false, targets: sortingDisabled }],
                        "autoWidth" : false
                    });

                    $(".dataTables_length > label > select").dropdown();
                    $(".dataTables_length > label > div").addClass('compact');

                    $pagination = $(".dataTables_length").detach();
                    $pagination.appendTo("#pagination");

                    if(enableSearch){
                        $searchBar = $("#database-table_filter").detach();
                        $searchBar.appendTo("#search-bar");

                        $searchBar = $("#database-table_filter > label > input").detach();
                        $searchBar.appendTo("#database-table_filter");

                        $("#search-bar").addClass('field');
                    }

                    $(document).on('search.dt', function() {
                        var value = $('.dataTables_filter input').val();
                        var searchf = {};
                        searchf["columns"] = JSON.stringify(search);
                        searchf["value"] = value;

                        var stringify = JSON.stringify(searchf);
                        $("#hdnsearch").val(stringify);
                    }); 

                    $(document).on('change', '.filter-field', function(){
                        field = $(this).attr('field');
                        type = filters[field].type;

                        switch(type){
                            case 'dropdown':
                            case 'combo':
                                filters[field].value = $(this).dropdown('get value');
                                break;
                            case 'checkbox':
                                checkboxValues = [];
                                $('.ui.checkbox.filter-field[field="' + field + '"]').each(function(){
                                    if($(this).hasClass('checked')){
                                        thisValue = $(this).children().val();
                                        checkboxValues.push(thisValue);
                                    }
                                });

                                filters[field].value = JSON.stringify(checkboxValues);
                                break;
                            case 'daterange':
                                if($(this).hasClass('display-all')){
                                    if($(this).checkbox('is checked')){
                                        $('input.datepicker').val('').prop('disabled', true).parent().addClass('disabled');
                                    }else{
                                        $('input.datepicker').prop('disabled', false).parent().removeClass('disabled');
                                    }

                                    dateRange = {};
                                    filters[field].value = null;
                                }

                                if($(this).hasClass('date-field') && $('.display-all').checkbox('is unchecked')){
                                    date = moment($(this).val());
                                    dateValid = moment(date).isValid();

                                    if($(this).attr('name') == 'startdate' && dateValid){
                                        endDate = moment($('input[name="enddate"]').val());
                                        endDateValid = moment(endDate).isValid();

                                        if(!endDateValid || endDate < date){
                                            endDate = moment(date).add('1', 'month').format("MM/DD/YYYY");
                                            $('input[name="enddate"]').val(endDate);
                                        }
                                        
                                        startDate = date;
                                        $(this).parent().removeClass('error');
                                    }

                                    if($(this).attr('name') == 'enddate' && dateValid){
                                        startDate = moment($('input[name="startdate"]').val());
                                        startDateValid = moment(startDate).isValid();

                                        if(!startDateValid || date < startDate){
                                            $('input[name="startdate"]').parent().addClass('error');
                                            filters[field].value = null;
                                            break;
                                        }

                                        endDate = date;
                                    }

                                    dateRange = {
                                        startdate : moment(startDate).format("YYYY-MM-DD"),
                                        enddate : moment(endDate).format("YYYY-MM-DD")
                                    };

                                    filters[field].value = dateRange;
                                }


                                break;
                        }
                        console.log("FILTERS", filters);
                        var stringify = JSON.stringify(filters);
                        // $("#importexportdiv").append('<br />' + stringify);
                        $("#hdnfilters").val(stringify);

                        table.ajax.reload();
                    });

                    //for Inventory only
                    if(page == 'inventory-db'){
                        $category_checkbox = $.find("div[field='category']");
                        var category_text = {};
                        var $dropdownItemContainer = {};

                        function itemDropdown(categories){
                            $dropdownItems = $('div[field="item_name"]').find('.item');
                            $('div[field="item_name"]').dropdown('clear');

                            if(categories.length > 0){
                                $.each(category_text, function(i, v){
                                    $rows = $(".ui.dropdown > .menu > .item[field-name='" + i + "']");
                                    if(categories.indexOf(v) == -1){
                                        $rows.detach(); 
                                    }else{
                                        if($rows.length == 0){
                                            $dropdownItemContainer[i].appendTo('div[field="item_name"] > .menu'); 
                                        }
                                    }
                                });
                            }else{
                                $.each($dropdownItemContainer, function(){
                                    $(this).appendTo('div[field="item_name"] > .menu');
                                });
                                categories = null;
                            }                            
                        }

                        if($category_checkbox){
                            var selected_categories = [];
                            $.each($category_checkbox, function(){
                                cat_text = $(this).find('label').text();
                                category_text[cat_text] =  $(this).children().val();

                                $dropdownItemContainer[cat_text] = $(".ui.dropdown > .menu > .item[field-name='" + cat_text + "']").clone();
                                $(".ui.dropdown > .menu > .item[field-name='" + cat_text + "']").detach();

                                if($(this).checkbox('is checked')){
                                    selected_categories.push($(this).children().val());
                                }
                                itemDropdown(selected_categories);
                            });

                            $(document).on('change', "div[field='category'] > input", function(){
                                if($(this).parent().checkbox('is checked')){
                                    selected_categories.push($(this).val());
                                }else{
                                    i = selected_categories.indexOf($(this).val());
                                    selected_categories.splice(i, 1);
                                }
                                itemDropdown(selected_categories);
                            });
                        }

                    }

                    //For Client only
                    if(page == 'client-db'){
                        $("#database-table").wrap("<div class='db-view-table-wrap'></div>");
                        $toggleSwitches = $('.logic-toggle > .toggle.checkbox');

                        $.each($toggleSwitches, function(){
                            $toggleText = $(this).children();
                            $thisField = $(this).attr('field');

                            if($toggleText.hasClass('toggled')){
                                thisLogic = "OR";
                            }else{
                                thisLogic = "AND";
                            }

                            logic[$thisField] = thisLogic;
                        });

                        $(document).on('click', '.logic-toggle > .toggle.checkbox', function(){
                            $toggleText = $(this).children();
                            $thisField = $(this).attr('field');

                            if($toggleText.hasClass('toggled')){
                                $toggleText.removeClass('toggled');
                                thisLogic = "AND";
                            }else{
                                $toggleText.addClass('toggled');
                                thisLogic = "OR";
                            }

                            logic[$thisField] = thisLogic;
                            table.ajax.reload();
                        });

                        $(document).on('change', '.no_rg', function(){  
                            if(logic['risk_groups'] == 'AND' && $(this).checkbox('is checked')){
                                $('.ui.checkbox.filter-field[field="risk_groups"]').addClass('disabled');
                                $('.ui.checkbox.filter-field[field="risk_groups"]:not(".no_rg")').checkbox('uncheck');
                                $(this).removeClass('disabled');
                            }else if($(this).checkbox('is unchecked')){
                                $('.ui.checkbox.filter-field[field="risk_groups"]').removeClass('disabled');
                            }
                        });

                        $(document).on('change', '.toggle.checkbox[field="risk_groups"]', function(){  
                            if($(this).checkbox('is checked')){
                                $('.ui.checkbox.filter-field[field="risk_groups"]').removeClass('disabled');
                            }else if($('.no_rg').checkbox('is checked')){
                                $('.ui.checkbox.filter-field[field="risk_groups"]').addClass('disabled');
                                $('.ui.checkbox.filter-field[field="risk_groups"]:not(".no_rg")').checkbox('uncheck');
                                $('.no_rg').removeClass('disabled');
                            }
                        });
                    }
                    
                    $(document).on('click', '.filter-display-toggle', function(){
                        visible = $("#filters").is(":visible");
                        if(visible){
                            $(".filter-display-toggle > .action").text("Show");
                            $(".filter-display-toggle").addClass("show");
                        }else{
                            $(".filter-display-toggle > .action").text("Hide");
                            $(".filter-display-toggle").removeClass("show");
                        }
                        $("#filters").transition('slide down');
                    });

                    // setTimeout(function() {
                    //     table.columns.adjust().draw();
                    // }, 5000);

                });
            </script>
@stop