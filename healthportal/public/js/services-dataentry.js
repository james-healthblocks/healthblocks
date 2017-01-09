function toggleServiceColumn(index, service) {
    var $column = $(".service-column-header[service-type='"+index+"']");
    var $columnSex = $(".service-sex-header[service-type='"+index+"']");
    var $clientRow = $(".client-count[service-type='"+index+"']")
    if(service.show){
        $column.fadeIn();
        $columnSex.fadeIn();
        $clientRow.fadeIn();                  
    }else{
        $column.fadeOut();
        $columnSex.fadeOut();
        $clientRow.fadeOut(); 
    }
}

function getActivityVenue(){
    if($(".typeDropdown > .text").hasClass('filtered')){
        text = $(".dropdown-text-input").val();
        if(text == ""){
            value = "";
        }else{
            value = 0;
        }
    }else{
        value = $(".typeDropdown").dropdown('get value');
        text = $(".typeDropdown").dropdown('get value');
    }

    venue = {
        value : value,
        text : text
    };

    return venue;
}

$(".ui.checkbox").checkbox();

var visible = 0;
var rowNumber = 0;

$(".service-type-checkbox").change(function(){
    chkboxVal = $(this).find("input:checkbox").val() - 1;
    service = service_types[chkboxVal];
    service.show = $(this).checkbox('is checked');

    if(service.show){
        visible++;
    }else{
        visible--;
    }

    if(visible == 0){
        $(".services-content").fadeOut();
    }else{
        $(".services-content").fadeIn();
    }

    toggleServiceColumn(chkboxVal, service);
});

$(".service-type-checkbox").each(function(){
    chkboxVal = $(this).find("input:checkbox").val() - 1;
    service = service_types[chkboxVal];
    service.show = $(this).checkbox('is checked');

    if(service.show){
        visible++;
    }

    if(visible == 0){
        $(".services-content").fadeOut();
    }else{
        $(".services-content").fadeIn();
    }

    toggleServiceColumn(chkboxVal, service);
});

$.each(service_types, function(index, service){
    newColumn = "<th class='service-column-header' service-type='";
    newColumn += index + "' colspan='2'>";
    newColumn += service.label + "</th>";
    $(".service-column-headers").last().append(newColumn);

    $.each(sex, function(i, sx){
        sexColumn = "<th class='service-sex-header' service-type='";
        sexColumn += index + "'>";
        sexColumn += sx + "</th>";

        sexInput = "<td class='selectable-cell client-count' ";
        sexInput += "service-type='" + index + "' ";
        sexInput += "sex='" + i + "'>";

        hiddenInput = "<input class='for-selectable'";
        hiddenInput += "service-type='" + index + "' ";
        hiddenInput += "sex='" + i + "' value='0'></input></td>";

        $(".service-sex-headers").last().append(sexColumn);
        $(".service-client-count-row[row-number='" + rowNumber + "']").last().append(sexInput + hiddenInput);
    });

    
    if(service.show){
        dispProp = 'table-cell';
    }else{
        dispProp = 'none';
    }

    $(".service-column-header[service-type='"+index+"']").css('display', dispProp);
    $(".service-sex-header[service-type='"+index+"']").css('display', dispProp);
    $(".client-count[service-type='"+index+"']").css('display', dispProp);
});

clientEntry = "<option value=''></option>";
$(".clientDropdown").append(clientEntry);

$.each(client_types, function(index, client){
    if(!client.selected){
        clientEntry = "<option value='" + index + "'>";
        clientEntry += client.label + "</option>";  
        $("#row-" + rowNumber).append(clientEntry);                              
    }                    
});

$("button").click(function(e){
    e.preventDefault();
});

$(document).ready(function(){
    $(".ui.dropdown").dropdown();
    $("#clear").css('display', 'none');

    $(".typeDropdown").find("input").addClass("dropdown-text-input");
    $(".typeDropdown").dropdown({
        forceSelection: false
    }).dropdown('save defaults');
    
    $(".clientDropdown").attr("data-content", "").popup({on:"click"});

    clearInputs();

    $(document).on('focus', ".for-selectable", function(){
        $(this).parent("td").css('background', '#FFFEC2');
    });

    $(document).on('blur', ".for-selectable", function(){
        $(this).parent("td").removeAttr('style');
    });

    $(document).on('click', ".client-count", function(){
        $(this).children().focus();
    });

    $(document).on('change', ".clientDropdown", function(){
        $(this).removeClass('error');
        clientType = $(this).dropdown('get value');
        $closestRow = $(this).closest('.service-client-count-row');
        $closestRow.find('.client-count').children().attr('client-type', clientType);
        showSexField = client_types[clientType];
        showSexField = showSexField.sex;

        $.each(sex, function(i, sx){
            if(showSexField.indexOf(parseInt(i)) == -1){
                $closestRow.find("td[sex='" + i + "']").addClass('disabled');
                $closestRow.find("td[sex='" + i + "']").children().prop('disabled', true).val("0");
            }else{
                $closestRow.find("td[sex='" + i + "']").removeClass('disabled');
                $closestRow.find("td[sex='" + i + "']").children().prop('disabled', false);
            }
        });

        if(clientType == tgm){
            $(this).attr("data-content", "Transgender men are counted according to their birth assigned sex");
        }else if(clientType == tgw){
            $(this).attr("data-content", "Transgender women are counted according to their birth assigned sex");
        }else{
            $(this).attr("data-content", "");
        }
    });

    $(".for-selectable").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
            // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
        }
    });

    $(document).on('click', '.add-row', function(e){
        e.preventDefault();
        clientType = $(this).next('.clientDropdown').dropdown('get value');

        if(clientType){
            client_types[clientType].selected = true;
            rowNumber++;
            $(this).removeClass('add add-row').addClass('remove remove-row');
            $(this).find('i').removeClass('plus').addClass('remove');
            $(this).next('.clientDropdown').addClass('disabled');

            newButton = "<button class='ui circular add icon mini button add-row'><i class='plus icon'></i></button>";

            newRow = "<tr class='service-client-count-row' row-number='" + rowNumber + "'>";
            newRow += "<td class='three wide column button-wrap'>";
            newRow += newButton;
            newRow += "<select class='ui dropdown clientDropdown' id='row-" + rowNumber + "'></select></td></tr>";

            $(".services-table").append(newRow);   

            $.each(service_types, function(index, service){
                $.each(sex, function(i, sx){
                    sexInput = "<td class='selectable-cell client-count' ";
                    sexInput += "service-type='" + index + "' ";
                    sexInput += "sex='" + i + "'>";

                    hiddenInput = "<input type='number' class='for-selectable'";
                    hiddenInput += "service-type='" + index + "' ";
                    hiddenInput += "sex='" + i + "' value='0'></input></td>";

                    $(".service-client-count-row[row-number='" + rowNumber + "']").last().append(sexInput + hiddenInput);
                });   

                if(service.show){
                    dispProp = 'table-cell';
                }else{
                    dispProp = 'none';
                }

                $(".client-count[service-type='"+index+"']").css('display', dispProp);
            });

            optionCount = 0;
            $.each(client_types, function(index, client){
                if(!client.selected){
                    optionCount++;
                    clientEntry = "<option value='" + index + "'>";
                    clientEntry += client.label + "</option>";                                
                    $("#row-" + rowNumber).append(clientEntry);
                }    
            });

            if(optionCount == 0){
                $("tr[row-number='" + rowNumber+ "']").fadeOut();
            }

            $(".ui.dropdown").dropdown();
            $(".clientDropdown").attr("data-content", "").popup({on:"click"});
        }else{
            $(this).next('.clientDropdown').addClass('error');
            showMessage('error', 'Cannot Add Row', 'Select Client Type');
        }
        
    });

    $(document).on('click', '.remove-row', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        clientType = $(this).next('.clientDropdown').dropdown('get value');

        client = client_types[clientType];
        client.selected = false;

        clientEntry = "<option value='" + clientType + "'>";
        clientEntry += client.label + "</option>";                                
        $("#row-" + rowNumber).append(clientEntry);
        $("tr[row-number='" + rowNumber+ "']").fadeIn();
    });

    $(document).on('change', '#activity-date', function(){
        $(this).parent().removeClass('error');
    });

    $(document).on('click', '#clear', function(e){
        e.preventDefault();
        clearInputs()
    });

    $(document).on('change', '.typeDropdown',function(){
        $('.dropdown-text-input').text($(this).dropdown('get text')[0]);
        selectedType = $(this).dropdown('get text')[0];
        $(this).parent().removeClass('error');
    });

    $(document).on('click', '#save-service', function(e){
        e.preventDefault();
        data = [];
        error = false;
        message = [];

        venue = getActivityVenue();

        if(venue.value == "" && venue.text == ""){
            $(".typeDropdown").addClass('error');
            message.push("No Activity Venue");
            error = true;
        }

        if($("#activity-date").val() == ''){
            $("#activity-date").parent().addClass('error');
            message.push("No Date of Activity");
            error = true;
        }

        client = $(".clientDropdown").dropdown('get value');
        if(client == ''){
            $(".clientDropdown").addClass('error');
            message.push("No Client Type")
            error = true;
        }

        if(error){
            showMessage('error', 'Cannot Save', message);
            return false;
        }

        $(".services-table").find("tr").each(function(){
            row = [];
            cell = {};
            count = 0;
            error = false;
            $(this).find("td").each(function(){
                if($(this).is(':visible')){
                    cell = {};
                    count++;
                    if(count == 1){
                        client = $(this).find(".clientDropdown").dropdown('get value');
                        if(client == ''){
                            $(this).find(".clientDropdown").addClass('error');
                            error = true;
                            return false;
                        }
                    }else{
                        cell = { 
                            'client_type' : client,
                            'sdate' : $("#activity-date").val(),
                            'service_type' : parseInt($(this).attr("service-type")) + 1,
                            'venue' : venue.text,
                            'sex' : $(this).attr("sex"),
                            'count' : parseInt($(this).find("input").val()) ,
                            'invalid' : 0
                        };

                        data.push(cell);
                    }  
                }
            });
        });

        if(!error){
            $("#save-service").addClass('loading');

            if(!error){
                $.ajax({
                    url: '/services/encode',
                    method: 'POST',
                    data: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(data){
                        showMessage('success', 'Saving Successful!', 'Database has been updated.')
                    },
                    error: function(data){
                        showMessage('error', 'Error Saving', 'Backend error. Error code to follow')
                    }
                }).always(function(){
                    $("#save-service").removeClass('loading');
                    $("#clear").css('display', 'inline-block');
                });
            }
        }

        return false;
    });

    $('.datepicker').datepicker({
        autoclose: true
    });
});