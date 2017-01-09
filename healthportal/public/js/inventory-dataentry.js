var count = 0;
var months = [];
var years = [];
var max = new Date().getFullYear();
var min = max - 150;

var inventory_types = [];

var newRows = 0;
var selectedType;
var operation = [];

var defaultTable = $(".inventory-table").clone();

function generateTypes(types){
    count = 0;
    inventoryTypeDropdown = "<option value=''></option>";
    $.each(types, function(i, type){
        inventoryTypeDropdown = inventoryTypeDropdown + "<option value='" + type + "'>" + type + "</option>";
    });

    $("#inventory_types").html(inventoryTypeDropdown);
    $("#inventory_types").dropdown('restore defaults');
}

function addRows(data){
    $.each(data, function(i, rowData){
        newRows++; 
        row = "<tr class='service-client-count-row existing-entry' row-number='" + newRows + "'>";
        row += "<td class='three wide column button-wrap'>";
        row += "<span class='ui circular check icon mini button yellow for-text'><i class='archive icon'></i></span>";
        row += "<span class='selected-type'>" + rowData.item_name + "</span></td>";
        row += "<td class='data-cell' data='batchno'><input class='for-selectable' value='" + rowData.batchno + "' disabled></input></td>";
        row += "<td class='data-cell' data='expiry_date'><input class='for-selectable' value='" + rowData.expiry_date + "' disabled></input></td>";
        row += "<td class='data-cell read-only' data='start_amt'><input class='for-selectable' value='" + rowData.start_amt + "' disabled></input></td>";
        row += "<td class='data-cell read-only' data='on_hand'><input class='for-selectable' value='" + rowData.on_hand + "' disabled></input></td>";
        row += "<td class='data-cell' data='remarks'><input class='for-selectable' value='" + rowData.remarks + "' disabled></input></td>";

        row += '<td class="selectable-cell" data="action"><div class="ui left labeled input">';
        row += '<div class="ui dropdown label action-dropdown add" item-type="'+ i +'" row-number="' + newRows + '">';
        row += '<div class="text action-dropdown-text" data-value="add"><i class="plus icon"></i></div><div class="menu">';
        row += '<div class="item" data-value="add"><i class="plus icon"></i></div>';
        row += '<div class="item" data-value="sub"><i class="minus icon"></i></div></div></div>';
        row += "<input class='for-selectable'></div></td></tr>";

        $(".inventory-table>tbody>tr:last").after(row);
        $(".action-dropdown[item-type='" + i + "']").dropdown('set value', 'add');
        $(".action-dropdown[item-type='" + i + "']").dropdown({
            onChange: function(){
                var cell = {};

                newRows = $(this).attr("row-number");
                cell.type = $(this).attr('item-type');
                cell.action = $(this).dropdown('get value');
                cell.amount = $(this).next("input").val();

                if(cell.action == 'add'){
                    $(this).removeClass('subtract').addClass('add');
                }else{
                    $(this).removeClass('add').addClass('subtract');
                }

                if(operation[newRows] == undefined){
                    operation.push(cell);
                }else{
                    operation[newRows] = cell;
                }
            }
        });
    });
}

function resetTable(){
    newRows = 0;

    $(".inventory-table").find('tbody').find('tr').each(function(){
        if(parseInt($(this).attr('row-number')) > 0){
            $(this).remove();
        }
    });
    $("#new-row-control").find('.for-selectable').each(function(){
        if(!$(this).hasClass('read-only')){
            $(this).val("");
        }
    });

    $("#new-row-control > td > .error").removeClass('error');
    $("#new-row-control > td[data='expiry_date']").removeClass('error');
    $("#new-row-control > td[data='action']").removeClass('error');
}

function getData(year, month, category){
    $(".inventory-table-container").fadeOut();
    $(".inventory-control").addClass('disabled');
    $(".loading-container").css("display", "flex").hide().fadeIn();
    $(".inv-type-input").val('');
    hideMessage();
    $.ajax({
        url: '/inventory/encode/retrieve/' + year + '/' + month + '/' + category,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(data){
            resetTable();
            inventory_types = data.types;
            setTimeout(generateTypes(addRows(data.data)), 1000);
            $(".inventory-table-container").css("display", "flex").hide().fadeIn();
            setTimeout(generateTypes(inventory_types), 1000);
        },
        error: function(data){
            console.log("Retrieve Error");
            console.log(data);
        }
    }).always(function(){
        $(".loading-container").fadeOut();
        $(".inventory-control").removeClass('disabled');
    });
}

$(document).ready(function(){
    $("#month").dropdown();
    $("#year").dropdown();
    $("#inventory_categories").dropdown();
    $("#inventory_categories").dropdown('clear');
    $(".action-dropdown").dropdown('set value', 'add');
    $(".action-dropdown").dropdown('save defaults');
    $(".action-dropdown").dropdown({
        onChange: function(){
            var cell = {};

            newRows = $(this).attr("row-number");
            cell.type = $(this).attr('item-type');
            cell.action = $(this).dropdown('get value');
            cell.amount = $(this).next("input").val();

            if(cell.action == 'add'){
                $(this).removeClass('subtract').addClass('add');
            }else{
                $(this).removeClass('add').addClass('subtract');
            }

            if(operation[newRows] == undefined){
                operation.push(cell);
            }else{
                operation[newRows] = cell;
            }
        }
    });
    $("#inventory_types").dropdown({
        forceSelection: false
    });
    $("#inventory_types").dropdown('save defaults');

    $(document).on('focus', ".for-selectable", function(){
        $(this).parent("td").css('background', '#FFFEC2');
    });

    $(document).on('blur', ".for-selectable", function(){
        $(this).parent("td").removeAttr('style');
    });

    $(document).on('click', ".selectable-cell", function(){
        $(this).children().focus();
    });

    $('.datepicker').datepicker({
        autoclose: true
    });

    $(document).on('click', ".add-row", function(e){
        e.preventDefault();
        error = false;
        message = [];
        newRows++;
        $thisRow = $(this).closest('tr');
        actionAmount = $thisRow.find('td[data="action"]').find(".input").find("input").val();
        expiry_date = $thisRow.find('td[data="expiry_date"]').find("input").val();

        if(selectedType == '' || selectedType == undefined){
            $thisRow.find('td').first().find('.ui.dropdown').addClass('error');
            error = true;
            message.push("No Item Type Selected");
        }

        if(actionAmount == '' || isNaN(actionAmount)){
            $thisRow.find('td[data="action"]').addClass('error');
            error = true;
            message.push("No Amount in Action");
        }

        if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(expiry_date)){
            error = true;
            $thisRow.find('td[data="expiry_date"]').addClass('error');
            message.push("No or Invalid Expiry Date");
        } 

        if(error){
            showMessage('error', 'Add Row Failed', message);
            return false;
        }

        $newRow =  $thisRow.clone().removeAttr('id'); //clone input row
        $newRow.attr('row-number', newRows);
        selectedAction = $thisRow.find('td[data="action"]').find(".dropdown").dropdown('get value'); //get action
        $thisRow.find('td[data="action"]').find(".dropdown").dropdown('restore defaults');

        $thisRow.attr('row-number', newRows); // update row number
        
        //change first column
        newCell = "<td class='three wide column button-wrap'>";
        newCell += "<button class='ui circular remove icon mini button remove-row for-text'><i class='remove icon'></i></button>";
        newCell += "<span class='selected-type'>" + selectedType + "</span></td>";
        $newRow.find('td').first().replaceWith(newCell);

        //change last column
        newCell = "<td class='selectable-cell added' data='action' action='" + selectedAction + "'>";
        newCell += "<i class='" + selectedAction + " icon'></i>";
        newCell += "<span class='action-amount'>" + actionAmount + "</span></td>";
        $newRow.find('td[data="action"]').replaceWith(newCell);

        //set input fields to read-only
        $newRow.find('td.data-cell').each(function(){
            $(this).removeClass('selectable-cell');
            $cellInput = $(this).children();
            value = $cellInput.val();
            $cellInput.prop('disabled', true);
        });

        $newRow.insertAfter("#new-row-control");

        //reset new row controller
        $thisRow.find('td').first().find('.dropdown').dropdown('clear');
        $thisRow.find('.for-selectable').each(function(){
            if(!$(this).hasClass('read-only')){
                $(this).val("");
            }
        });

    });

    $(document).on('click', ".remove-row", function(e){
        e.preventDefault();
        $(this).closest('tr').remove();   
    });

    $(".typeDropdown").find("input").addClass("inv-type-input");

    $(".inv-type-input").bind("change input", function(){
        selectedType = $(this).val();
        $(this).parent().removeClass('error');
    });      

    $("td[data='expiry_date'] > input").bind("change input", function(){
        if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test($(this).val())){
            $(this).parent().removeClass('error');
        }
    }); 
});

inventoryTypeDropdown = "<option value=''></option>";


$("#inventory_types").change(function(){
    category = $("#inventory_categories").dropdown('get text')[0];
    $('.inv-category').text(category);
    $('.inv-type-input').text($("#inventory_types").dropdown('get text')[0]);
    selectedType = $("#inventory_types").dropdown('get text')[0];
    $(this).parent().removeClass('error');
});

$(document).on('change', ".amount-input[row-number='0']", function(){
    $(this).parent().removeClass('error');
});

$(document).on('change', '.inventory-control', function(e){
    month = $("#month").val();
    year = $("#year").val();
    category = $("#inventory_categories").dropdown('get value')[0];

    error = false;

    if(month == ''){
        error = true;
    }

    if(year == ''){
        error = true;
    }

    if(category == ''){
        error = true;
    }

    if(error){
        return false;
    }

    getData(year, month, category);
    
});

$(document).on("click", ".save-inventory", function(e){
    e.preventDefault();
    error = false;
    message = [];

    month = $("#month").val();
    year = $("#year").val();
    category = $("#inventory_categories").dropdown('get value')[0];

    updatedRows = [];
    newInventory = [];

    $(".save-inventory").addClass('loading').prop('disabled', true);
    $(".inventory-control").addClass('disabled');

    $(".inventory-table").find("tr").each(function(){
        if($(this).attr('row-number') > 0){
            row = {
                    month : month,
                    year : year,
                    category : category,
                    item_name : '',
                    batchno : '' ,
                    expiry_date : '' ,
                    start_amt : '' ,
                    on_hand : '' ,
                    remarks : '',
                    procuredcount : 0,
                    distributedcount : 0
            };


            row["item_name"] = $(this).find('td').first().find('.selected-type').text();

            $(this).find('td.data-cell').each(function(){
                header = $(this).attr('data');
                row[header] = $(this).children().val();
            });

            $actionCell = $(this).find('td').last();

            if($(this).hasClass('existing-entry')){
                amount = $actionCell.find('input.for-selectable ').val();

                if((amount != '' && !isNaN(amount)) || amount > 0){
                    action = $actionCell.find('.action-dropdown').dropdown('get value');

                    switch(action){
                        case 'add':
                            row["procuredcount"] = amount;
                            break;
                        case 'sub':
                            row["distributedcount"] = amount;
                            break;
                        default: 
                            error = true;
                            message.push("No Amount in Action Cell");
                            return false;
                    }

                    updatedRows.push(row);
                }
            }else{
                amount = parseInt($actionCell.find('.action-amount').text());
                    action = $actionCell.attr('action');

                if((amount != '' && !isNaN(amount)) || amount > 0){

                    switch(action){
                        case 'add':
                            row["procuredcount"] = amount;
                            break;
                        case 'sub':
                            row["distributedcount"] = amount;
                            break;
                        default: 
                            error = true;
                            message.push("No Amount in Action Cell");
                            return false;
                    }

                    newInventory.push(row);
                }
            }
        }
    });

    if(updatedRows.length === 0 && newInventory.length === 0){
        message = "Nothing to save. Click '+' button on the left, then click save.";
        showMessage('warning', 'Nothing to save.', message);
        error = true;

        $(".save-inventory").removeClass('loading').prop('disabled', false);
        $(".inventory-control").removeClass('disabled');
        return false;
    }

    if(!error){
        data = { toUpdate: updatedRows, newEntries: newInventory };

        $.ajax({
            url: '/inventory/encode',
            method: 'POST',
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json'
        }).done(function(data){
            getData(year, month, category);
            showMessage('success', 'Save successful!', 'Database has been updated.');
        }).fail(function(data){
            showMessage('error', 'Failed to save', 'Backend error. Error code to follow haha');
        }).always(function(){
            $(".save-inventory").removeClass('loading').prop('disabled', false);
            $(".inventory-control").removeClass('disabled');
        });
    }else{
        showMessage('error', 'Invalid input', message);

        $(".save-inventory").removeClass('loading').prop('disabled', false);
        $(".inventory-control").removeClass('disabled');
    }

    return false;

});